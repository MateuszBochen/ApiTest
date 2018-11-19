<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 14.11.18
 * Time: 19:40
 */

namespace Library\Http;


class Request
{

    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_PATCH = 'patch';


    private $applicationPath;
    private $urlParams;
    private $requestData = [];
    private $currentUrl;
    private $headers = [];

    public function __construct()
    {
        $this->applicationPath = implode('/', explode('/', $_SERVER['SCRIPT_NAME']));
        $string = str_replace($this->applicationPath, '', (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''));
        $this->currentUrl = trim($string, '/');
        $this->urlParams = explode('/', $this->currentUrl);
        $this->headers = getallheaders();
    
        $this->prepareRequestData();
    }

    public function getHeader(string $name) : string
    {
        if(isset($this->headers[$name])) {
            return $this->headers[$name];
        }
        return '';
    }

    public function getMethod()
    {
        return $this->getServerValue('REQUEST_METHOD');
    }

    public function getCurrentUrl()
    {
        return $this->currentUrl;
    }

    public function getUrlParam($index)
    {
        if (isset($this->urlParams[$index])) {
            return $this->urlParams[$index];
        }
        return null;
    }

    public function getUrlParams()
    {
        return $this->urlParams;
    }

    public function post($name)
    {
        return $this->getFromArray($name, $this->requestData);
    }

    public function get($name)
    {
        return $this->getFromArray($name, $this->requestData);
    }

    public function getRequestData()
    {
        return $this->requestData;
    }

    public function getServerValue($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        return null;
    }

    public function getApplicationPath()
    {
        return $this->applicationPath;
    }

    public function isPost()
    {
        return $this->isMethod('POST');
    }

    public function isGet()
    {
        return $this->isMethod('GET');
    }

    public function isPut()
    {
        return $this->isMethod('PUT');
    }

    public function isHead()
    {
        return $this->isMethod('HEAD');
    }

    public function isDelete()
    {
        return $this->isMethod('DELETE');
    }

    public function isOptions()
    {
        return $this->isMethod('OPTIONS');
    }

    private function isMethod($check)
    {
        $method = $this->getServerValue('REQUEST_METHOD');
        if($method === $check) {
            return true;
        }
        return false;
    }

    private function prepareRequestData()
    {
        $inputData = file_get_contents("php://input");

        if($this->getHeader("Content-Type") === 'application/json') {
            $this->requestData = json_decode($inputData, true);
        } else {
            $this->requestData = $inputData;
        }
    }

    private function getFromArray($index, &$array)
    {
        $paramAsArray = explode('.', $index);
        $lastValue = $array;
        foreach ($paramAsArray as $index) {
            if (!isset($lastValue[$index])) {
                return '';
            }
            $lastValue = $lastValue[$index];
        }
        return $lastValue;
    }
}
