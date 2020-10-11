<?php

class FranchisesController extends AppController
{

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->checkStoreInfo();
        $hasFranchise = $this->Session->read('StoreSetting.hasFranchise');
        if(!$hasFranchise) {
            $this->Session->setFlash('Unauthorized access', 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'stores', 'action' => 'home'));
        }
    }

    public function index()
    {
        $this->Franchise->recursive = 0;

        $conditions = array('Franchise.store_id' => $this->Session->read('Store.id'), 'Franchise.is_deleted' => 0);
        $franchises = $this->Franchise->find('all', $conditions);

        $this->set(compact('franchises'));
    }

    public function view($franchiseId = null)
    {
        if (!($franchiseInfo = $this->CommonFunctions->getFranchiseInfo($franchiseId))) {
            $this->Session->setFlash('Franchise not found', 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'franchises', 'action' => 'index'));
        }
        $this->set(compact('franchiseInfo'));
    }

    public function add()
    {
        $errorMsg = null;
        if ($this->request->data) {
            $data = $this->request->data;
            if (isset($data['Franchise']['name'])) {

                if ($data['Franchise']['name'] = trim($data['Franchise']['name'])) {
                    $conditions = array('Franchise.name' => $data['Franchise']['name'], 'Franchise.store_id' => $this->Session->read('Store.id'));
                    if ($this->Franchise->find('first', array('conditions' => $conditions))) {
                        $errorMsg = "'" . $data['Franchise']['name'] . "'" . ' already exists';
                    } else {
                        $data['Franchise']['store_id'] = $this->Session->read('Store.id');
                        if ($this->Franchise->save($data)) {
                            $franchiseInfo = $this->Franchise->read();
                            $msg = 'Franchise added successfully';
                            $this->Session->setFlash($msg, 'default', array('class' => 'success'));
                            $this->redirect(array('controller' => 'franchises', 'action' => 'index'));
                        } else {
                            $errorMsg = 'An error occurred while communicating with the server';
                        }
                    }
                } else {
                    $errorMsg = 'Enter Franchise Name';
                }

            }
        }
        ($errorMsg) ? $this->Session->setFlash($errorMsg, 'default', array('class' => 'error')) : null;

    }

    public function edit($franchiseId = null)
    {
        $errorMsg = null;

        if (!($franchiseInfo = $this->CommonFunctions->getFranchiseInfo($franchiseId))) {
            $this->Session->setFlash('Franchise not found', 'default', array('class' => 'error'));
            $this->redirect('/franchises/');
        }

        if ($this->request->data) {
            $data = $this->request->data;
            if (isset($data['Franchise']['name'])) {
                if ($data['Franchise']['name'] = trim($data['Franchise']['name'])) {

                    $conditions = array('Franchise.name' => $data['Franchise']['name'], 'Franchise.store_id' => $this->Session->read('Store.id'), 'Franchise.id <>' => $franchiseId);
                    if ($this->Franchise->find('first', array('conditions' => $conditions))) {
                        $errorMsg = "'" . $data['Franchise']['name'] . "'" . ' already exists';
                    } else {
                        $data['Franchise']['id'] = $franchiseId;
                        $data['Franchise']['store_id'] = $this->Session->read('Store.id');
                        if ($this->Franchise->save($data)) {
                            $msg = 'Franchise updated successfully';
                            $this->Session->setFlash($msg, 'default', array('class' => 'success'));
                            $this->redirect('/franchises/');
                        } else {
                            $errorMsg = 'An error occurred while communicating with the server';
                        }
                    }
                } else {
                    $errorMsg = 'Enter Franchise Name';
                }
            }
        } else {
            $this->data = $franchiseInfo;
        }
        ($errorMsg) ? $this->Session->setFlash($errorMsg, 'default', array('class' => 'error')) : null;
        $this->set(compact('franchiseInfo'));
    }

    public function remove($franchiseId = null)
    {
        if ($this->request->isPost()) {
            if (!($franchiseInfo = $this->CommonFunctions->getFranchiseInfo($franchiseId))) {
                $this->Session->setFlash('Franchise not found', 'default', array('class' => 'error'));
            } else {
                // delete franchise information
                $this->Franchise->softDelete($franchiseId);
                $this->Session->setFlash('Franchise "' . $franchiseInfo['Franchise']['name'] . '" has been removed', 'default', array('class' => 'success'));
            }
        } else {
            $this->Session->setFlash('Unauthorized access', 'default', array('class' => 'error'));
        }
        $this->redirect(array('action' => 'index'));
    }

}