<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $components = array(
        'Session',
        'CommonFunctions',
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email')
                )
            ),
            'loginRedirect' => array('controller' => 'stores', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
        )
    );

    public function beforeFilter()
    {
        // $this->Auth->allow('index', 'view');


        // check if store is about to expire in 1 month
        if ($this->Session->check('Store.created')) {
            $storeCreatedOn = $this->Session->read('Store.created');
            $storeCreatedOn = date('Y-m-d', strtotime($storeCreatedOn));

            $unixTimeStoreActualExpiry = strtotime($storeCreatedOn . " +1 year");
            $unixTimeStoreExpiryNotice = strtotime($storeCreatedOn . " +1 year -1 month");
            $unixTimeNow = strtotime("now");

            $storeExpiryDate = date('d-m-Y', $unixTimeStoreActualExpiry);
            if ($unixTimeNow > $unixTimeStoreExpiryNotice) {
                $this->noticeMsg("This Store will expire on '$storeExpiryDate'. Contact software owner to renew this store before expiry date.");
            }
        }

        // check if store is expired
        if ($this->Session->check('Store.created')) {
            $storeCreatedOn = $this->Session->read('Store.created');
            $storeCreatedOn = date('Y-m-d', strtotime($storeCreatedOn));
            $unixTimeStoreExpiry = strtotime($storeCreatedOn . " +1 year +1 day");
            $unixTimeNow = strtotime("now");
            if ($unixTimeNow > $unixTimeStoreExpiry) {
                $this->errorMsg("This Store is expired. Contact software owner to renew this store. <br> You are only allowed to access 'Reports'.");
                if (($this->request->params['controller'] != 'reports') and ($this->request->params['controller'] != 'stores') and ($this->request->params['controller'] != 'users')) {
                    $this->redirect(array('controller' => 'reports', 'action' => 'home'));
                }
            }
        }
    }

    /** function to check if store information is set */
    public function getStoreInfo()
    {
        $storeInfo = array();
        if ($this->Session->check('Store')) {
            $storeInfo = $this->Session->read('Store');
        }

        return $storeInfo;
    }

    public function checkStoreInfo()
    {
        if (!$storeInfo = $this->getStoreInfo()) {
            $this->Session->setFlash('Select a Store', 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('controller' => 'stores', 'action' => 'index'));
        }
        return true;
    }

    public function deleteStoreInfo()
    {
        App::uses('Store', 'Model');
        $this->Store = new Store;

        $this->Store->query("delete from cashbook where store_id='$storeID'");    // remove records from cashbook table
        $this->Store->query("delete from categories where store_id='$storeID'");    // remove records from categories table
        $this->Store->query("delete from employees where store_id='$storeID'");    // remove records from employees table
        $this->Store->query("delete from invoices where store_id='$storeID'");    // remove records from invoices table
        $this->Store->query("delete from product_categories where store_id='$storeID'");    // remove records from product_categories table
        $this->Store->query("delete from products where store_id='$storeID'");    // remove records from products table
        $this->Store->query("delete from purchases where store_id='$storeID'");    // remove records from purchases table
        $this->Store->query("delete from salaries where store_id='$storeID'");    // remove records from salaries table
        $this->Store->query("delete from sales where store_id='$storeID'");    // remove records from sales table
        $this->Store->query("delete from suppliers where store_id='$storeID'");    // remove records from suppliers table
        // $this->Store->query("delete from stores where id='$storeID'");	// remove records from stores table
    }

    public function userIsManager()
    {
        if ($this->Session->read('manager') == '1') {
            return true;
        }
        return false;
    }

    public function onlyManagerCanAccess()
    {
        if (!$this->userIsManager()) {
            $this->redirect('/stores/');
        }
        return true;
    }

    public function errorMsg($msg)
    {
        if ($msg) {
            $this->Session->setFlash($msg, 'default', array('class' => 'alert alert-danger'));
        }
        return true;
    }

    public function noticeMsg($msg)
    {
        if ($msg) {
            $this->Session->setFlash($msg, 'default', array('class' => 'alert alert-notice'));
        }
        return true;
    }

    public function successMsg($msg)
    {
        if ($msg) {
            $this->Session->setFlash($msg, 'default', array('class' => 'alert alert-success'));
        }
        return true;
    }

    public function updateInvoice($invoiceID)
    {
        if ($invoiceID) {
            App::uses('Invoice', 'Model');
            $this->Invoice = new Invoice();
            App::uses('Purchase', 'Model');
            $this->Purchase = new Purchase();

            $invoice_info = $this->Invoice->findById($invoiceID);
            //$tcs_percent = $invoice_info['Invoice']['tcs_percent'];

            $this->Purchase->clear();
            $purchase_products = $this->Purchase->findAllByInvoiceId($invoiceID);
            if ($purchase_products) {
                $invoice_value = 0;
                $tcs_value = $invoice_info['Invoice']['tcs_value'];
                $retail_shop_excise_turnover_tax = $invoice_info['Invoice']['retail_shop_excise_turnover_tax'];
                $special_excise_cess = $invoice_info['Invoice']['special_excise_cess'];
                $special_margin = 0;

                foreach ($purchase_products as $row) {
                    $invoice_value += $row['Purchase']['total_amount'];
                    $special_margin += $row['Purchase']['total_special_margin'];
                }
                $net_invoice_value = $invoice_value + $special_margin;
                //$tcs_value = ($tcs_percent > 0) ? ceil((($net_invoice_value*$tcs_percent)/100)) : 0;

                $invoice_data['Invoice']['id'] = $invoiceID;
                $invoice_data['Invoice']['invoice_value'] = $invoice_value;
                $invoice_data['Invoice']['special_margin'] = $special_margin;
                //$invoice_data['Invoice']['tcs_value'] = $tcs_value;

                $dd_purchase = $invoice_value + $special_margin + $tcs_value;
                $invoice_data['Invoice']['dd_purchase'] = $dd_purchase;
                $invoice_data['Invoice']['credit_balance'] = (
                    $invoice_info['Invoice']['dd_amount']
                    + $invoice_info['Invoice']['prev_credit']
                    - $dd_purchase
                    - $retail_shop_excise_turnover_tax
                    - $special_excise_cess
                    - $invoice_info['Invoice']['mrp_rounding_off']
                );
                $this->Invoice->save($invoice_data);
            }
        }
    }
}
