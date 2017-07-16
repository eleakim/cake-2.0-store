<?php

App::uses('Controller', 'Controller');

/**
 * Application Controller
 */
class AppController extends Controller {
    
    public $components = array(
        'Flash',
        'Auth' => array(
            'autoRedirect' => true,
            'loginRedirect' => array('admin' => false, 'controller' => 'users', 'action' => 'dashboard'),
            'logoutRedirect' => array('admin' => false, 'controller' => 'users', 'action' => 'login'),
            'fields' => array(
                'username' => 'username',
                'password' => 'password'
            ),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'User',
                    'fields' => array(
                        'username' => 'username',
                        'password' => 'password'
                    )
                ),
            ),
        )
    );

    public function beforeFilter(){
        $this->set_auth();
    }

    protected function set_auth(){
        //is admin domain?
        if($this->is_admin()):
            $this->theme = 'admin';

            AuthComponent::$sessionKey = 'Auth.User';

            $this->Auth->autoRedirect   = false;
            $this->Auth->loginAction    = array('admin' => true, 'controller' => 'users', 'action' => 'login');
            $this->Auth->loginRedirect  = array('admin' => true, 'controller' => 'dashboard', 'action' => 'index');
            $this->Auth->logoutRedirect = array('admin' => true, 'controller' => 'users', 'action' => 'login');

            $this->Auth->authenticate   = array(
                'Form' => array(
                    'userModel' => 'User',
                    'fields' => array(
                        'username' => 'username',
                        'password' => 'password'
                    )
                )
            );
        else:
            AuthComponent::$sessionKey = 'Auth.Customer';
            $this->Auth->allow();
        endif;
    }

    protected function is_admin(){
        return (isset($this->params['prefix']) && $this->params['prefix'] == 'admin');
    }
}
