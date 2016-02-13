<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:12
 */

namespace Core\Controller;

use Core\Controller;
use Core\Model\Models\Albums;
use Core\Model\Models\Users_has_Albums;

class ProfileController extends Controller
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function defaultAction($data = null)
    {
        $layout = $this->makeLayout($data);
        $album = new Albums();
        $user_albums = new Users_has_Albums();
        $user_albums = $user_albums->selectAll('albums_id', array('users_id', $layout['id']), \PDO::FETCH_NUM);
        $albums = array();
        foreach ($user_albums as $item){
            $curr_albums = $album->selectAll($album->getColumns(), array('id', $item[0]));
            foreach ($curr_albums as $key => $value){
                if(!$layout['profile_owner'] && $layout['main_id'] !== $curr_albums[$key]['owner']){
                    if(!empty($json = json_decode($curr_albums[$key]['available']))){
                        $available = explode(',', $json);
                        if(false === array_search($layout['main_id'], $available)){
                            unset($curr_albums[$key]);
                            continue;
                        }
                    }
                }
                $curr_albums[$key]['isliked'] = false;
                $buhlikes = json_decode($curr_albums[$key]['buhlikes']);
                $buhlikes = explode(',', $buhlikes);
                $curr_albums[$key]['buhlikes'] = count($buhlikes);
                if(array_search($layout['main_id'], $buhlikes)){
                    $curr_albums[$key]['isliked'] = true;
                }
            }
            $albums = array_merge($albums, $curr_albums);
        }

        $albums = array('user_albums' => $albums);
        $data = array_merge_recursive($layout, $albums);

        return $this->view->render('views::profile.html', $data);
    }

}