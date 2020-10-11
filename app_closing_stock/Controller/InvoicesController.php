<?php
class InvoicesController extends AppController {
	function beforeFilter() {
		parent::beforeFilter();
		$this->checkStoreInfo();
	}
	
	// Get logged in user's selected invoice information
	public function getInvoiceInfo($invoiceID=null) {		
		if(!$invoiceID) {
			return array();
		}
		return $this->Invoice->find('first', array('conditions'=>array('Invoice.id'=>$invoiceID, 'Invoice.store_id'=>$this->Session->read('Store.id'))));	
	}
	
	public function add() {
		$errorMsg = null;
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier;
		$suppliersList = $this->Supplier->find('list', array('conditions'=>array('Supplier.store_id'=>$this->Session->read('Store.id'))));
        $invoiceDate = Date('Y-m-d');
		
		if($this->request->data) {
			$data = $this->request->data;
			if(isset($data['Invoice']['name'])) {				
				if(Validation::blank($data['Invoice']['dd_amount'])) {
					$errorMsg = 'Enter DD Amount';
				}
				else {
					if((!Validation::decimal($data['Invoice']['dd_amount'])) OR ($data['Invoice']['dd_amount']<=0)) {
						$errorMsg = 'Enter Valid DD Amount';
					}
				}
				
				if(!empty($data['Invoice']['tcs_value'])) {
					if((!Validation::decimal($data['Invoice']['tcs_value'])) OR ($data['Invoice']['tcs_value']<0)) {
						$errorMsg = 'Enter Valid TCS Value';
					}					
				}
				if(!empty($data['Invoice']['prev_credit'])) {
					if((!Validation::decimal($data['Invoice']['prev_credit'])) OR ($data['Invoice']['prev_credit']<0)) {
						$errorMsg = 'Enter Valid Previous Credit Value';
					}					
				}

				if(!$errorMsg) {
					if(!empty($data['Invoice']['invoice_date'])) {
//						$invoiceDate = $data['Invoice']['invoice_date']['year'].'-'.$data['Invoice']['invoice_date']['month'].'-'.$data['Invoice']['invoice_date']['day'];
//						$data['Invoice']['invoice_date'] = $invoiceDate;
                        $invoiceDate = $data['Invoice']['invoice_date'];
						
						if($data['Invoice']['name']=trim($data['Invoice']['name'])) {
							$conditions = array('Invoice.name'=>$data['Invoice']['name'], 'Invoice.store_id'=>$this->Session->read('Store.id'));
							if($this->Invoice->find('first', array('conditions'=>$conditions))) {
								$errorMsg = "'".$data['Invoice']['name']."'". ' already exists';
							}
							else {
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = ($data['Invoice']['supplier_id']) ? $suppliersList[$data['Invoice']['supplier_id']] : '';
								if($this->Invoice->save($data)) {
									$invoiceInfo = $this->Invoice->read();
									$msg = 'Invoice created successfully';
									$this->successMsg($msg);
									$this->redirect(array('controller'=>'invoices', 'action'=>'selectInvoice', $invoiceInfo['Invoice']['id']));
								}
								else {
									$errorMsg = 'An error occurred while communicating with the server';
								}
							}
						}
						else {
							$errorMsg = 'Enter Invoice Name';
						}
					}
					else {
						$errorMsg = 'Select Invoice Date';					
					}
				}
			}
		}
		($errorMsg) ? $this->errorMsg($errorMsg) : null;
		$this->set(compact('suppliersList', 'invoiceDate'));
	}
	
	public function index() {
		$this->Session->delete('Invoice');
		$invoices = $this->Invoice->find('all', array('conditions'=>array('Invoice.store_id'=>$this->Session->read('Store.id')), 'order'=>array('Invoice.invoice_date DESC', 'Invoice.created DESC')));
		
		// get invoice amount from purchases
		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase;
			
		$purchaseInfo = $this->Purchase->find('all', array('conditions'=>array('Purchase.store_id'=>$this->Session->read('Store.id'), 'Purchase.invoice_id NOT'=>'NULL'), 'fields'=>array('SUM(Purchase.total_amount) as total_amount', 'SUM(Purchase.total_special_margin) as total_special_margin', 'Purchase.invoice_id'), 'group'=>array('Purchase.invoice_id')));
		$invoiceAmount = array();
		if($purchaseInfo) {
			foreach($purchaseInfo as $row) {
				$invoiceAmount[$row['Purchase']['invoice_id']] = $row[0]['total_amount']+$row[0]['total_special_margin'];
			}
		}
		
		$this->set('invoices', $invoices);
		$this->set('invoiceAmount', $invoiceAmount);
	}

	public function edit($invoiceID=null) {
		$errorMsg = null;
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier;
		$suppliersList = $this->Supplier->find('list', array('Supplier.store_id'=>$this->Session->read('Store.id')));	
		
		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
			$this->redirect('/invoices/');
		}
			
		if($this->request->data) {
			$data = $this->request->data;
			if(isset($data['Invoice']['name'])) {
				if($data['Invoice']['name']=trim($data['Invoice']['name'])) {
					if(!empty($data['Invoice']['dd_no'])) {
						if(Validation::blank($data['Invoice']['dd_no'])) {
							$errorMsg = 'Enter DD No';
						}
						else {
							if((!Validation::decimal($data['Invoice']['dd_amount'])) OR ($data['Invoice']['dd_amount']<=0)) {
								$errorMsg = 'Enter Valid DD Amount';
							}					
							elseif((!Validation::decimal($data['Invoice']['dd_purchase'])) OR ($data['Invoice']['dd_purchase']<=0)) {
								$errorMsg = 'Enter valid DD Purchase Amount';
							}
						}
					}
					if(!empty($data['Invoice']['tcs_value'])) {
						if((!Validation::decimal($data['Invoice']['tcs_value'])) OR ($data['Invoice']['tcs_value']<0)) {
							$errorMsg = 'Enter Valid TCS Value';
						}					
					}
					if(!empty($data['Invoice']['prev_credit'])) {
						if((!Validation::decimal($data['Invoice']['prev_credit'])) OR ($data['Invoice']['prev_credit']<0)) {
							$errorMsg = 'Enter Valid Previous Credit Value';
						}					
					}
					
					if(!$errorMsg) {
						if(!empty($data['Invoice']['invoice_date'])) {
//							$invoiceDate = $data['Invoice']['invoice_date']['year'].'-'.$data['Invoice']['invoice_date']['month'].'-'.$data['Invoice']['invoice_date']['day'];
//							$data['Invoice']['invoice_date'] = $invoiceDate;
                            $invoiceDate = $data['Invoice']['invoice_date'];
							$conditions = array('Invoice.name'=>$data['Invoice']['name'], 'Invoice.store_id'=>$this->Session->read('Store.id'), 'Invoice.id <>'=>$invoiceID);
							if($this->Invoice->find('first', array('conditions'=>$conditions))) {
								$errorMsg = "'".$data['Invoice']['name']."'". ' already exists';
							}
							else {
								$data['Invoice']['id'] = $invoiceID;
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = ($data['Invoice']['supplier_id']) ? $suppliersList[$data['Invoice']['supplier_id']] : '';								
								if($this->Invoice->save($data)) {
									$this->updateInvoice($invoiceID);
									
									$msg = 'Invoice details updated successfully';
									
									// update purchase products date with this invoice date.
									App::uses('Purchase', 'Model');
									$this->Purchase = new Purchase;
									$fields = array('Purchase.purchase_date'=>"'".$invoiceDate."'");
									$conditions = array('Purchase.invoice_id'=>$invoiceID);
									$this->Purchase->recursive = '-1';
									$this->Purchase->updateAll($fields, $conditions);
									
									$this->successMsg($msg);
									$this->redirect('/invoices/selectInvoice/'.$invoiceID);
								}
								else {
									$errorMsg = 'An error occurred while communicating with the server';
								}
							}
						}
						else {
							$errorMsg = 'Enter Invoice Date';
						}
					}
				}
				else {
					$errorMsg = 'Enter Invoice Name';
				}
			}
		}
		else {
			$this->data = $invoiceInfo;
		}
		($errorMsg) ? $this->errorMsg($errorMsg) : null;
		$this->set(compact('suppliersList'));
	}

	public function delete($invoiceID=null) {
		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
		}
		else {
			// delete purchase data of the selected Invoice.
			App::uses('Purchase', 'Model');
			$this->Purchase = new Purchase;
			$conditions = array('Purchase.invoice_id'=>$invoiceID);			
			$this->Purchase->deleteAll($conditions);
			
			// delete Invoice information
			$this->Invoice->delete($invoiceID);
			
			$this->successMsg('Invoice "'.$invoiceInfo['Invoice']['name'].'" has been removed');
			
		}
		$this->redirect($this->request->referer());
	}
	
	public function selectInvoice($invoiceID=null) {
		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
			$this->redirect('/invoices/');
		}
		
		$this->Session->write('Invoice', $invoiceInfo['Invoice']);	
		$this->redirect('/purchases/addProduct/');	
	}
	
	public function details($invoiceID=null) {
		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
			$this->redirect('/invoices/');
		}
		
		// find invoice products
		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase;
		$conditions = array('Purchase.invoice_id'=>$invoiceID);
		$invoiceProducts = $this->Purchase->find('all', array('conditions'=>$conditions, 'recursive'=>2));
		$this->set(compact('invoiceInfo', 'invoiceProducts'));
	}

}
