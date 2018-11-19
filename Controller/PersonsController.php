<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 15:45
 */

namespace Controller;

use Library\Controller\BaseController;
use Library\Http\Response;
use Library\Http\UrlParam;

class PersonsController extends BaseController
{
    public function getAction() : Response
    {
        $rm = $this->get(\Library\DataBase\RepositoryManager::class);

        $repo = $rm->getRepository(\Model\Person::class);

        return new Response(Response::OK, $repo->findAll());
    }

    public function getWebsiteAction()
    {
        $rm = $this->get(\Library\DataBase\RepositoryManager::class);
        $repo = $rm->getRepository(\Model\Person::class);

        return new Response(Response::OK, $repo->findAllPersonsWithWebsite());
    }

    public function getPersonAction(UrlParam $id)
    {
        $rm = $this->get(\Library\DataBase\RepositoryManager::class);
        $repo = $rm->getRepository(\Model\Person::class);

        return new Response(Response::OK, $repo->findOneBy(['id' => (string) $id]));
    }

    public function postAction()
    {
        $model = new \Model\Person();
        $form = $this->createForm($model);

        if($form->isValid()) {
            $rm = $this->get(\Library\DataBase\RepositoryManager::class);
            $repo = $rm->getRepository($model);
            $repo->save();
            return new Response(Response::CREATED, $model);
        }
        return new Response(Response::NOT_ACCEPTABLE, ['form validation error']);
    }

    public function putPersonAction(UrlParam $id)
    {
        $repo = $this->getRepository(\Model\Person::class);
        $model = $repo->findOneBy(['id' => (string) $id]);
        $form = $this->createForm($model);

        if($form->isValid()) {
            $repo->save();
            return new Response(Response::ACCEPTED, $model);
        }
        return new Response(Response::NOT_ACCEPTABLE, ['form validation error']);
    }

    public function patchPersonAction(UrlParam $id)
    {
        $repo = $this->getRepository(\Model\Person::class);
        $model = $repo->findOneBy(['id' => (string) $id]);
        $form = $this->createForm($model);

        if($form->isValid()) {
            $repo->save();
            return new Response(Response::ACCEPTED, $model);
        }
        return new Response(Response::NOT_ACCEPTABLE, ['form validation error']);
    }

    public function deletePersonAction(UrlParam $id)
    {
        $repo = $this->getRepository(\Model\Person::class);
        $model = $repo->findOneBy(['id' => (string) $id]);

        $repo->delete();

        return new Response(Response::NO_CONTENT, null);
    }
}
