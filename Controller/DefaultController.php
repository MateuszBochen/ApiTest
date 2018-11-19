<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 14.11.18
 * Time: 20:20
 */

namespace Controller;

use Library\Controller\BaseController;
use Library\Http\UrlParam;

class DefaultController extends BaseController
{
    public function getIndexAction()
    {
        echo 'ok';
    }

    public function getUrlAction(UrlParam $id)
    {
        $rm = $this->get(\Library\DataBase\RepositoryManager::class);
        $repo = $rm->getRepository(\Model\Person::class);
    }
}
