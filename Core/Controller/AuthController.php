<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.16
 * Time: 19:33
 */

namespace Core\Controller;

use Core\Controller;
use Core\Model\Models\Users;

class AuthController extends Controller
{

    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function authAction(){
        if($this->request->isGet()) {
            if($this->cookie->hasCookie('email')){
                $user = new Users();
                $curr_user = $user->selectObj(array('email', $this->cookie->getCookie('email')), \PDO::FETCH_OBJ, true);
                if($curr_user->password === $this->cookie->getCookie('password')){
                    $this->auth->setUser($curr_user);
                    $this->response->redirect('/');
                }else{
                    $this->response->redirect('/');
                }
            }else {
                $flash = $this->session->has('flash')?$this->di->get('session')->getFlash():null;
                return $this->view->render('views::auth.html', [ 'alert' => $flash ]);
            }
        }else{
            if($this->di->get('auth')->isAuthenticated()) {
                $this->response->redirect('/');
            }elseif($this->di->get('request')->has('nick')) {
                //REGISTER
                $this->registerAction();
            }else{
                //LOGIN
                $this->loginAction();
            }
        }
    }

    public function registerAction(){
        $user = new Users();
        $email = $this->di->request->get('email');
        $nick = $this->request->get('nick');

        if($user->selectObj(array('email', $email), \PDO::FETCH_OBJ, true) !== false){
            $this->session->setFlash('This email is already registered!', 'danger');
            $this->response->redirect('/');
        }
        if($user->selectObj(array('nick', $nick), \PDO::FETCH_OBJ, true) !== false){
            $this->session->setFlash('This nick is already used!', 'danger');
            $this->response->redirect('/');
        }
        $user->email = strtolower($email);
        $user->password = $this->auth->hash($this->request->get('password'));
        $user->nick = $nick;
        $date = new \DateTime(null, new \DateTimeZone('Europe/Kiev'));
        $user->reg_date = $date->format('Y-m-d');
        $user->insert();
        $this->session->setFlash('Registration has been successful!', 'success');
        $this->response->redirect('/');
    }

    public function loginAction($reg_email = null, $reg_pass = null)
    {

        $pass = $this->request->get('password');
        $user = new Users();
        $email = $reg_email?$reg_email:$this->request->get('email');
        $email = strtolower($email);
        if ($curr_user = $user->selectObj(array('email', $email), \PDO::FETCH_OBJ, true)) {
            $password = $reg_pass ? $reg_pass : $this->request->get('password');
            $password = $this->auth->hash($password);
            if ($curr_user->password === $password) {
                $this->auth->setUser($curr_user);
                if ($this->request->get('remember') === 'yes') {
                    $this->cookie->setCookie('email', $curr_user->email);
                    $this->cookie->setCookie('password', $curr_user->password);
                }
                $this->response->redirect('/');
            }
        }
        $this->session->setFlash('Email or password entered incorrectly!', 'danger');
        $this->response->redirect('/');
    }

    public function logoutAction(){
        $this->cookie->deleteCookie('email');
        $this->cookie->deleteCookie('password');
        $this->auth->clear();
        $this->response->redirect('/');
    }

}