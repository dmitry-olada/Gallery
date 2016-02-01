<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:13
 */

namespace Core\Controller;

use Core\Controller;
use Core\Model\Models\Users;

class SettingsController extends Controller implements _ControllerInterface
{

    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function DefaultAction($data = null)
    {
        $user = $this->auth->getUser();
        $layout = $this->makeLayout($user->id);

        return $this->view->render('views::settings.html', $layout);
    }

    public function AllAction()
    {
        $user = new Users();
        $user = $user->selectAll(array('id', 'nick', 'reg_date'));
        $layout = $this->makeLayout();
        return $this->view->set('info', $user)->render('views::all_users.html', $layout);
    }

    public function ChangeAction($param){

        $curr_user = $this->auth->getUser();

        if($this->request->isGet()){
            $this->response->redirect('/settings/'.$curr_user->id);
        }

        $user = new Users();
        $user = $user->selectObj(array('id', $curr_user->id));
        switch ($param){
            case 1:
                $user->nick = $this->request->get('new_nick');
                $property = 'Nick';
                break;
            case 2:
                $user->avatar = $this->request->get('new_avatar');
                $property = 'Avatar';
                break;
            case 3:
                $user->email = $this->request->get('new_email');
                $property = 'Email';
                break;
            case 4:
                $old_pass = $this->auth->hash($this->request->get('old_password'));
                if($old_pass !== $user->password){
                    $this->session->setFlash('Old password was incorrect', 'danger');
                    $this->response->redirect('/settings/'.$curr_user->id);
                    //А нужен ли тут break?
                }
                $new_password = $this->request->get('new_password');
                $conf_new_password = $this->request->get('conf_new_password');
                if($new_password !== $conf_new_password){
                    $this->session->setFlash("New passwords don't match", 'danger');
                    $this->response->redirect('/settings/'.$curr_user->id);
                }
                $user->password = $this->auth->hash($new_password);
                $property = 'Password';
                break;
            default:
                $this->session->setFlash('Unknown error', 'danger');
                $this->response->redirect('/settings/'.$curr_user->id);
                break;
        }
        $user->update('id', $curr_user->id);
        $this->session->setFlash($property.' has been changed successfully', 'success');
        $this->response->redirect('/settings/'.$curr_user->id);
    }

}