<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 20:17
 */

namespace Library\Form;


use Library\Http\Request;
use Library\Http\Response;

class Form
{
    private $dataObject;
    private $request;
    private $isValid = false;

    public function __construct(Request $request, $dataObject)
    {
        $this->dataObject = $dataObject;
        $this->request = $request;

        $this->prepareData();
    }


    /**
     * For now always return true
     * @return bool
     */
    public function isValid() : bool
    {
        return $this->isValid;
    }

    /**
     * @throws FormException
     */
    private function prepareData()
    {
        $method = strtolower($this->request->getMethod());

        switch($method)
        {
            case Request::METHOD_POST:
            case Request::METHOD_PUT:
                $this->preparePost($method);
                break;
            case Request::METHOD_PATCH:
                $this->preparePatch();
                break;
            default:
                throw new FormException();

        }
    }

    private function preparePost(string $method)
    {
        $objectVars = get_object_vars($this->dataObject);
        $this->isValid = true;
        foreach($objectVars as $property => $propertyVale) {
            /**
             * skip id property is unnecessary
            */
            if($property === 'id') {
                continue;
            }

            $requestData = $this->request->getRequestData();

            if(isset($requestData[$property]) && !empty($requestData[$property])) {
                $this->dataObject->$property = $requestData[$property];
            } else {
                $this->isValid = false;
            }
        }

    }

    private function preparePatch()
    {
        $objectVars = get_object_vars($this->dataObject);
        $requestData = $this->request->getRequestData();
        $this->isValid = true;

        foreach ($requestData as $propertyToUpdate => $newValue) {
            /**
             * don't allow change model id
             */
            if ($propertyToUpdate === 'id') {
               continue;
            }

            if(isset($objectVars[$propertyToUpdate]) && !empty($newValue)) {
                $this->dataObject->$propertyToUpdate = $newValue;
            } else {
                $this->isValid = false;
            }
        }
    }
}
