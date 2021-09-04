<?php
App::uses('CakeEmail', 'Network/Email');

class PromoCodesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	function admin_index()
	{
		$sort = ['PromoCode.created' => 'DESC'];

		$conditions = [
			'PromoCode.site_id' => $this->Session->read('Site.id'),
			'PromoCode.deleted' => 0
		];
		$promoCodes = $this->PromoCode->find('all', ['conditions' => $conditions, 'order' => $sort]);

		$this->set(compact('promoCodes'));
	}

	function admin_add()
	{
		$errorMsg = null;

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (empty($data['PromoCode']['name'])) {
				$errorMsg = 'Promo Code is required';
			} else {
				$conditions = [
					'PromoCode.name' => $data['PromoCode']['name'],
					'PromoCode.site_id' => $this->Session->read('Site.id'),
					'PromoCode.deleted' => 0
				];

				if ($this->PromoCode->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Promo Code already exist.";
				}
			}

			if (!$errorMsg) {
				$data['PromoCode']['name'] = $data['PromoCode']['name'];
				$data['PromoCode']['site_id'] = $this->Session->read('Site.id');

				if ($this->PromoCode->save($data)) {
					$promocodeInfo = $this->PromoCode->read();

					$this->successMsg('PromoCode created successfully.');
					$this->redirect('/admin/promo_codes/');
				} else {
					$errorMsg = 'An error occurred while updating data';
				}
			}
		}

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg'));
	}

	function admin_edit($promoCodeId)
	{
		if (!$contentInfo = $this->isSitePromoCode($promoCodeId)) {
			$this->errorMsg('Promo Code Not Found');
			$this->redirect('/admin/promo_codes/');
		}

		$errorMsg = null;

		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['PromoCode']['name'])) {
				$errorMsg = 'name is a required field';
			} else {
				$conditions = [
					'PromoCode.name' => $data['PromoCode']['name'],
					'PromoCode.id NOT' => $promoCodeId,
					'PromoCode.site_id' => $this->Session->read('Site.id'),
					'PromoCode.deleted' => 0
				];

				if ($this->PromoCode->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Promo Code with same name already exist.";
				}
			}

			if (!$errorMsg) {
				$data['PromoCode']['id'] = $promoCodeId;

				if ($this->PromoCode->save($data)) {
					$this->successMsg('Promo Code updated successfully');
					$this->redirect('/admin/promo_codes/edit/'.$promoCodeId);
				} else {
					$errorMsg = 'An errorMsg occurred while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}
		$this->set(compact('errorMsg', 'contentInfo'));
	}

	public function admin_activate($promoCodeId, $type)
	{
		if (!$contentInfo = $this->isSitePromoCode($promoCodeId)) {
			$this->errorMsg('PromoCode Not Found');
			$this->redirect('/admin/promo_codes/');
		}

		$data['PromoCode']['id'] = $promoCodeId;
		$data['PromoCode']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->PromoCode->save($data)) {
			$this->successMsg('PromoCode modified successfully');
		} else {
			$this->errorMsg('An error occurred while updating data');
		}
		$this->redirect('/admin/promo_codes/');
	}

	public function admin_delete($promoCodeId)
	{
		if (!$contentInfo = $this->isSitePromoCode($promoCodeId)) {
			$this->errorMsg('PromoCode Not Found');
		} else {
			$tmp['PromoCode']['id'] = $promoCodeId;
			$tmp['PromoCode']['deleted'] = 1;
			$this->PromoCode->save($tmp);
			$this->successMsg('Promo Code deleted successfully');
		}
		$this->redirect('/admin/promo_codes/');
	}

}
