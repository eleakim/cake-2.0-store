<?php 

class UsersController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('admin_login', 'admin_logout');
    }

    public function admin_login() {
        if($this->request->is_post()){
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }

        $this->layout = 'login';
    }

    public function admin_logout() {
        $this->redirect($this->Auth->logout());
    }
}