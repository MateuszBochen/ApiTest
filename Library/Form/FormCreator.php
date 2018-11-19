<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 20:24
 */

namespace Library\Form;


use Library\Http\Request;

class FormCreator
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function create($dataObject)
    {
        return new Form($this->request, $dataObject);
    }
}