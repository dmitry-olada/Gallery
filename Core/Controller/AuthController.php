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

    public function defaultAction(){
        return 'AuthDefaultAction';
    }

    public function authAction()
    {
        if($this->di->get('request')->isGet()) {
            return $this->di->get('view')->render(ROOT.'Web/views/auth.html.php');
        }else{
            if(!$this->di->get('request')->get('nick')){
                $user = new Users();
                //var_dump($user->selectAll('email', $this->di->get('request')->get('email')));
                if ($curr_user = $user->selectAll('email', $this->di->get('request')->get('email'))) {
                    if ($curr_user->password == $this->di->get('request')->get('password')) {
                        $this->di->get('auth')->setUser($curr_user);
                        if ($this->di->get('request')->get('remember') == 'yes') {
                            $this->di->get('cookie')->setCookie('email', $curr_user->email);
                            $this->di->get('cookie')->setCookie('password', $curr_user->password);
                        }
                    }
                }
            }else {
                $user = new Users();
                if($user->selectAll('email', $this->di->get('request')->get('email'))){

                }

            }
        }
    }

    public function registerAction(){

    }

}