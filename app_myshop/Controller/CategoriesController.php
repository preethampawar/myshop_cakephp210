<?php
App::uses('CakeEmail', 'Network/Email');

class CategoriesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		// $this->Auth->allow('getCategories');
	}


	public function getCategories()
	{
		$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.active' => '1', 'Category.parent_id' => null, 'Category.deleted' => '0'];
		$categories = $this->Category->find('all', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC']);
		return $categories;
	}

	public function admin_getCategories()
	{
		$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.parent_id' => null];
		$categories = $this->Category->find('all', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC', 'Category.deleted' => '0']);
		return $categories;
	}

	public function admin_index()
	{
		$categories = $this->Category->admin_getCategories();

		$this->set('categories', $categories);
	}

	public function admin_add()
	{
		$errorMsg = '';
		if ($this->request->isPost()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Category']['name'])) {
				$errorMsg = 'Enter Category Name';
			}
			// Sanitize data
			$data['Category']['name'] = trim($data['Category']['name']);
			$data['Category']['name'] = Sanitize::paranoid($data['Category']['name'], [' ', '-', '.', '&', '(', ')', ',']);
			if (!$errorMsg) {
				$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.name' => $data['Category']['name']];
				if ($this->Category->find('first', ['conditions' => $conditions])) {
					$errorMsg = 'Category "' . $data['Category']['name'] . '" already exists';
				} else {
					$data['Category']['site_id'] = $this->Session->read('Site.id');
					if ($this->Category->save($data)) {
						$this->deleteCategoryListFromCache();
						$this->successMsg('Category successfully added');
					} else {
						$errorMsg = 'An error occurred while communicating with the server';
					}
				}
			}
		}

		$errorMsg ? $this->errorMsg($errorMsg) : '';

		$this->redirect('/admin/categories/index');
	}

	public function admin_edit($categoryID)
	{
		$errorMsg = [];
		$categoryInfoLinkActive = true;
		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', ['class' => 'default']);
			$this->redirect('/admin/categories/');
		}

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Category']['name'])) {
				$errorMsg[] = 'Enter Category Name';
			}
			// Sanitize data
			$data['Category']['name'] = Sanitize::paranoid($data['Category']['name'], [' ', '-', '.', '&', '(', ')', ',']);
			if (!$errorMsg) {
				$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.name' => $data['Category']['name'], 'Category.id NOT' => $categoryID];
				if ($this->Category->find('first', ['conditions' => $conditions])) {
					$errorMsg[] = 'Category "' . $data['Category']['name'] . '" already exists';
				} else {
					$data['Category']['id'] = $categoryID;
					if ($this->Category->save($data)) {
						$this->deleteCategoryListFromCache();
						$this->successMsg('Category successfully updated');
						$this->redirect('/admin/categories/add');
					} else {
						$errorMsg[] = 'An error occured while communicating with the server';
					}
				}
			}
		} else {
			$this->data = $categoryInfo;
		}

		$errorMsg = implode('<br>', $errorMsg);

		$errorMsg ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg', 'categoryInfo', 'categoryInfoLinkActive'));
	}

	public function admin_delete($categoryID)
	{
		if ($categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->deleteCategory($categoryID);
			$this->successMsg('Category successfully deleted');
		} else {
			$this->errorMsg('Category Not Found');
		}

		$this->redirect('/admin/categories/');
	}

	public function admin_showProducts($categoryID)
	{
		$errorMsg = null;
		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', ['class' => 'default']);
			$this->redirect('/admin/categories/');
		}

		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct;
		$conditions = ['CategoryProduct.category_id' => $categoryID];

		$this->CategoryProduct->unbindModel(['belongsTo' => ['Category']]);
		$categoryProducts = $this->CategoryProduct->findAllByCategoryId($categoryID, [], ['Product.name']);

		$tmp = [];
		$productsList = [];
		if (!empty($categoryProducts)) {
			foreach ($categoryProducts as $row) {
				$tmp[$row['Product']['id']] = $row;
				$productsList[$row['Product']['id']] = $row['Product']['name'];
			}
			// asort($productsList);
			$categoryProducts = $tmp;
		}

		$productsLimitExceeded = $this->productsLimitExceeded();

		$this->set(compact('errorMsg', 'categoryInfo', 'categoryProducts', 'productsList', 'productsLimitExceeded'));
	}


	public function admin_updateImage($categoryId)
	{
		$this->layout = false;
		$msg = 'Invalid request';
		$error = true;

		$isImageUrlSet = $this->request->data['imagePath'] ?? false;

		if ($isImageUrlSet && ($this->request->isPost() || $this->request->isPut())) {
			if ($categoryInfo = $this->isSiteCategory($categoryId)) {
				$images = [];

				if ($categoryInfo['Category']['images']) {
					$images = json_decode($categoryInfo['Category']['images']);
				}
				$images[] = [
					'imagePath' => $this->request->data['imagePath'],
					'type' => $this->request->data['imageType'],
					'commonId' => $this->request->data['commonId'] ?? rand(1, 10000),
					'caption' => '',
					'highlight' => 0,
				];

				$tmp['Category']['id'] = $categoryId;
				$tmp['Category']['images'] = json_encode($images);

				if ($this->Category->save($tmp)) {
					$this->deleteCategoryListFromCache();
					$error = false;
					$msg = 'Category image updated successfully';
				} else {
					$msg = 'Category image update failed';
				}
			} else {
				$msg = 'Category not found';
			}
		}

		$this->response->header('Content-type', 'application/json');
		$this->response->body(json_encode([
				'error' => $error,
				'msg' => $msg,
			], JSON_THROW_ON_ERROR)
		);
		$this->response->send();
		exit;
	}


	public function admin_highlightImage($categoryId, $imageCommonId)
	{
		$redirectURL = $this->request->referer();
		if (!$categoryInfo = $this->isSiteCategory($categoryId)) {
			$this->errorMsg('Image not found');
		} else {

			if (!$categoryInfo['Category']['images']) {
				$this->redirect($redirectURL);
			}

			$images = json_decode($categoryInfo['Category']['images']);

			foreach ($images as &$image) {
				$image->highlight = 0;
				if ($image->commonId == $imageCommonId) {
					$image->highlight = 1;
				}
			}

			$tmp['Category']['id'] = $categoryId;
			$tmp['Category']['images'] = json_encode($images);

			if ($this->Category->save($tmp)) {
				$this->deleteCategoryListFromCache();
				$msg = 'Category image updated successfully';
				$this->successMsg($msg);
			} else {
				$msg = 'Category image update failed';
				$this->errorMsg($msg);
			}

		}

		$this->redirect($redirectURL);
	}

	public function admin_deleteImage($categoryId, $imageCommonId)
	{
		$redirectURL = $this->request->referer();
		if (!$categoryInfo = $this->isSiteCategory($categoryId)) {
			$this->errorMsg('Image not found');
		} else {

			if (!$categoryInfo['Category']['images']) {
				$this->redirect($redirectURL);
			}

			$images = json_decode($categoryInfo['Category']['images']);
			$tmpImages = [];

			foreach ($images as $index => $image) {
				if ($image->commonId != $imageCommonId) {
					$tmpImages[] = $image;
				}
			}

			$tmp['Category']['id'] = $categoryId;
			$tmp['Category']['images'] = $tmpImages ? json_encode($tmpImages) : null;
			if ($this->Category->save($tmp)) {
				$this->deleteCategoryListFromCache();
				$msg = 'Category image updated successfully';
				$this->successMsg($msg);
			} else {
				$msg = 'Category image update failed';
				$this->errorMsg($msg);
			}

		}
		$this->redirect($redirectURL);
	}

	public function admin_activate($categoryId, $type)
	{
		$this->layout = false;
		if (!$categoryInfo = $this->isSiteCategory($categoryId)) {
			$this->errorMsg('Category Not Found');
			$this->redirect('/admin/categories/');
		}

		$data['Category']['id'] = $categoryId;
		$data['Category']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->Category->save($data)) {
			$this->successMsg('PromoCode modified successfully');
		} else {
			$this->errorMsg('An error occurred while updating data');
		}
		$this->redirect('/admin/categories/');
	}

	public function admin_updateBasePrice($categoryId)
	{
		$this->layout = false;
		if (!$categoryInfo = $this->isSiteCategory($categoryId)) {
			$this->errorMsg('Category Not Found');
			$this->redirect('/admin/categories/');
		}

		if ($this->request->isPost() or $this->request->isPut()) {
			$updated = 0;
			$data = $this->request->data;

			$productsBasePrice = (float)($data['Category']['products_base_price'] ?? 0);

			if ($productsBasePrice > 0) {
				App::uses('Product', 'Model');
				$productModel = new Product;

				App::uses('CategoryProduct', 'Model');
				$categoryProductModel = new CategoryProduct;
				$conditions = ['CategoryProduct.category_id' => $categoryId];

				$categoryProductModel->unbindModel(['belongsTo' => ['Category']]);
				$categoryProducts = $categoryProductModel->findAllByCategoryId($categoryId, ['Product.id', 'Product.mrp', 'Product.relative_base_price', 'Product.allow_relative_price_update'], ['Product.name']);

				if ($categoryProducts) {
					foreach($categoryProducts as $row) {
						if ((bool)$row['Product']['allow_relative_price_update'] === true) {
							$tmp = [];
							$tmp['Product']['id'] = $row['Product']['id'];
							$tmp['Product']['mrp'] = (float)$row['Product']['relative_base_price'] + $productsBasePrice;

							$productModel->save($tmp);
							$updated++;
						}
					}
				}

				// update category products base price
				$tmp = [];
				$tmp['Category']['id'] = $categoryId;
				$tmp['Category']['products_base_price'] = $productsBasePrice;
				$this->Category->save($tmp);

				$this->successMsg('Updated ' . $updated . ' product(s).');
			} else {
				$this->errorMsg('Products Base Price should be greater than 0.');
			}
		} else {
			$this->errorMsg('Invalid request');
		}

		$this->redirect($this->referer());
	}

}
