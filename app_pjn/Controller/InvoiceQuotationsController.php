<?php

class InvoiceQuotationsController extends AppController
{
    var $name = 'InvoiceQuotations';

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->checkStoreInfo();
    }

    function index($type = 'invoice')
    {
        $conditions = ['InvoiceQuotation.type' => ($type ? $type : 'template'), 'InvoiceQuotation.store_id' => $this->Session->read('Store.id')];
        $params = array(
            'order' => array('InvoiceQuotation.created' => 'desc'),
            'conditions' => $conditions
        );
        $quotations = $this->InvoiceQuotation->find('all', $params);

        $this->set(compact('quotations', 'type'));
    }

    function createTemplate()
    {
        if ($this->request->isPost()) {
            $data = $this->request->data;
            $data['InvoiceQuotation']['store_id'] = $this->Session->read('Store.id');

            if($this->InvoiceQuotation->save($data)) {
                $this->successMsg('Template saved successfully');
                $this->redirect('/invoice_quotations/index/template');
            } else {
                $this->errorMsg('An error occurred. Please try again');
            }
        }
    }


    function editTemplate($invoiceQuotationId)
    {
        if ($this->request->isPut()) {
            $data = $this->request->data;
            $this->InvoiceQuotation->id = $invoiceQuotationId;
            if($this->InvoiceQuotation->save($data)) {
                $this->successMsg('Template saved successfully');
                $this->redirect('/invoice_quotations/index/template');
            } else {
                $this->errorMsg('An error occurred. Please try again');
            }
        } else {
            $quotations = $this->InvoiceQuotation->findById($invoiceQuotationId);
            $this->data = $quotations;
        }
    }

    function create($invoiceQuotationId = null)
    {
        $template = 'default';
        if ($this->Session->check('QuotationTemplate')) {
            $template = $this->Session->read('QuotationTemplate');
        }

        if ($this->request->isPost() || $this->request->isPut()) {
            $data = $this->request->data;
            $qData['InvoiceQuotation'] = $data['InvoiceQuotation'];
            $qData['InvoiceQuotation']['store_id'] = $this->Session->read('Store.id');
            $itemsData['EntityItem'] = $data['EntityItem'];

            if ($this->InvoiceQuotation->save($qData)) {
                $quotationInfo = $this->InvoiceQuotation->read();
                if (!empty($itemsData['EntityItem'])) {
                    App::uses('EntityItem', 'Model');
                    $this->EntityItem = new EntityItem;
                    foreach ($itemsData['EntityItem']['item'] as $index => $row) {
                        $item = array();
                        $item['EntityItem']['id'] = null;
                        $item['EntityItem']['user_id'] = $this->Auth->user('id');
                        $item['EntityItem']['store_id'] = $this->Session->read('Store.id');
                        $item['EntityItem']['item'] = htmlentities($itemsData['EntityItem']['item'][$index], ENT_QUOTES);
                        $item['EntityItem']['description'] = htmlentities($itemsData['EntityItem']['description'][$index], ENT_QUOTES);
                        $item['EntityItem']['quantity'] = $itemsData['EntityItem']['quantity'][$index];
                        $item['EntityItem']['unitrate'] = $itemsData['EntityItem']['unitrate'][$index];
                        $item['EntityItem']['amount'] = $itemsData['EntityItem']['amount'][$index];
                        $item['EntityItem']['size'] = (isset($itemsData['EntityItem']['size'][$index])) ? $itemsData['EntityItem']['size'][$index] : '';
                        $item['EntityItem']['age'] = (isset($itemsData['EntityItem']['age'][$index])) ? $itemsData['EntityItem']['age'][$index] : '';
                        $item['EntityItem']['invoice_quotation_id'] = $quotationInfo['InvoiceQuotation']['id'];
                        $this->EntityItem->save($item);
                    }
                    $this->successMsg('Invoice / Quotation created successfully');
                    $this->redirect('/invoice_quotations/');
                } else {
                    $this->errorMsg('You need to add atleast one item');
                }
            }
        } else {
            $quotation = $this->InvoiceQuotation->findById($invoiceQuotationId);
            $this->data = $quotation;
        }
    }

    function edit($invoiceQuotationId)
    {
        $template = 'default';

        if ($this->request->isPost() || $this->request->isPut()) {
            $data = $this->request->data;
            $storeID = $this->Session->read('Store.id');
            $qData['InvoiceQuotation'] = $data['InvoiceQuotation'];
            $qData['InvoiceQuotation']['store_id'] = $storeID;
            $itemsData['EntityItem'] = $data['EntityItem'];
            $this->InvoiceQuotation->id = $invoiceQuotationId;

            if ($this->InvoiceQuotation->save($qData)) {
                $quotationInfo = $this->InvoiceQuotation->read();
                if (!empty($itemsData['EntityItem'])) {

                    App::uses('EntityItem', 'Model');
                    $this->EntityItem = new EntityItem;


                    $this->EntityItem->query("delete from entity_items where store_id='$storeID' and invoice_quotation_id='$invoiceQuotationId'");

                    foreach ($itemsData['EntityItem']['item'] as $index => $row) {
                        $itemId = (isset($itemsData['EntityItem']['id'][$index]) and !empty($itemsData['EntityItem']['id'][$index])) ? $itemsData['EntityItem']['quantity'][$index] : null;

                        $item = array();
                        $item['EntityItem']['id'] = $itemId;
                        $item['EntityItem']['user_id'] = $this->Auth->user('id');
                        $item['EntityItem']['store_id'] = $storeID;
                        $item['EntityItem']['item'] = htmlentities($itemsData['EntityItem']['item'][$index], ENT_QUOTES);
                        $item['EntityItem']['description'] = htmlentities($itemsData['EntityItem']['description'][$index], ENT_QUOTES);
                        $item['EntityItem']['quantity'] = $itemsData['EntityItem']['quantity'][$index];
                        $item['EntityItem']['unitrate'] = $itemsData['EntityItem']['unitrate'][$index];
                        $item['EntityItem']['amount'] = $itemsData['EntityItem']['amount'][$index];
                        $item['EntityItem']['size'] = (isset($itemsData['EntityItem']['size'][$index])) ? $itemsData['EntityItem']['size'][$index] : '';
                        $item['EntityItem']['age'] = (isset($itemsData['EntityItem']['age'][$index])) ? $itemsData['EntityItem']['age'][$index] : '';
                        $item['EntityItem']['invoice_quotation_id'] = $quotationInfo['InvoiceQuotation']['id'];
                        $this->EntityItem->save($item);
                    }
                    $this->successMsg('Invoice / Quotation created successfully');
                    $this->redirect('/invoice_quotations/');
                } else {
                    $this->errorMsg('You need to add atleast one item');
                }
            }
        } else {
            $this->InvoiceQuotation->bindModel(array('hasMany' => array('EntityItem')));
            $conditions = array('InvoiceQuotation.store_id' => $this->Session->read('Store.id'), 'InvoiceQuotation.id' => $invoiceQuotationId);
            $quotation = $this->InvoiceQuotation->find('first', array('conditions'=>$conditions));
            $this->data = $quotation;
        }
    }

    function selectTemplate()
    {
        $conditions = ['InvoiceQuotation.type' => 'template', 'InvoiceQuotation.store_id' => $this->Session->read('Store.id')];
        $params = array(
            'order' => array('InvoiceQuotation.created' => 'desc'),
            'conditions' => $conditions
        );
        $quotations = $this->InvoiceQuotation->find('all', $params);

        $this->set(compact('quotations', 'type'));
    }

    function details($quotationID, $downloadType = 'quotation')
    {
        $this->layout = 'print_layout';
        $storeId = $this->Session->read('Store.id');

        $this->InvoiceQuotation->bindModel(array('hasMany' => array('EntityItem')));
        $conditions = array('InvoiceQuotation.store_id' => $storeId, 'InvoiceQuotation.id' => $quotationID);

        $quotationInfo = $this->InvoiceQuotation->find('first', array('conditions' => $conditions));
        if (empty($quotationInfo)) {
            $this->Session->setFlash('Quotation not found', 'default', array('class' => 'error'));
            $this->redirect('/invoice_quotations/');
        }

        $this->set('quotationID', $quotationID);
        $this->set('quotationInfo', $quotationInfo);
        $this->set('downloadType', $downloadType);
    }

    function download($quotationID, $downloadType = 'quotation')
    {
        $this->layout = 'download_word';
        $companyID = $this->Session->read('Store.id');
        $this->InvoiceQuotation->bindModel(array('hasMany' => array('EntityItem'), 'belongsTo' => array('Store', 'User')));
        $conditions = array('InvoiceQuotation.store_id' => $companyID, 'InvoiceQuotation.id' => $quotationID);

        $quotationInfo = $this->InvoiceQuotation->find('first', array('conditions' => $conditions));
        if (empty($quotationInfo)) {
            $this->Session->setFlash('Quotation not found', 'default', array('class' => 'error'));
            $this->redirect('/invoice_quotations/');
        }

        $this->set('quotationInfo', $quotationInfo);
        $this->set('quotationID', $quotationID);
        $this->set('downloadType', $downloadType);
    }

    function delete($quotationID)
    {
        $companyID = $this->Session->read('Store.id');
        $this->InvoiceQuotation->bindModel(array('hasMany' => array('EntityItem')));
        $conditions = array('InvoiceQuotation.store_id' => $companyID, 'InvoiceQuotation.id' => $quotationID);

        $quotationInfo = $this->InvoiceQuotation->find('first', array('conditions' => $conditions));
        if ($quotationInfo) {
            App::uses('EntityItem', 'Model');
            $this->EntityItem = new EntityItem;
            $conditions = array('EntityItem.quotation_id' => $quotationID);
            $this->InvoiceQuotation->delete($quotationID);
            $this->EntityItem->deleteAll($conditions);
            $this->Session->setFlash('Quotation deleted successfully', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('Quotation not found', 'default', array('class' => 'error'));
        }
        $this->redirect('/invoice_quotations/');
    }
}

?>