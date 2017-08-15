<?php

App::uses('Controller', 'Controller');

/**
 * Application Controller
 */
class AppController extends Controller {

    // public $helpers = array('Html', 'Form', 'Session', 'Image', 'Text', 'Breadcrumb', 'BootstrapForm', 'ScriptCombiner', 'Language');
    public $helpers = array('Html', 'Form', 'BootstrapForm');

    public $components = array(
        'Flash',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'posts', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home')
        )
    );

    public function beforeFilter(){
        $this->set_auth();
        
        // $this->Tools->run($this->request);

        $this->model = Inflector::classify($this->params['controller']);
        $this->plugin = Inflector::classify($this->params['plugin']);
        $this->set("model", $this->model);
        $this->set("plugin", $this->plugin);
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
