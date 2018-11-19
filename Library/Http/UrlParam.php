<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 17.11.18
 * Time: 20:11
 */

namespace Library\Http;


use Library\Routing\Routing;

final class UrlParam
{
    private $param = null;

    public function __construct(Routing $routing, string $param)
    {
        $params = $routing->getRequestParams();

        if(isset($params[$param])) {
            $this->param = $params[$param];
        }
    }

    public function __toString() : string
    {
        return (string) $this->param;
    }

    public function getParam()
    {
        return $this->param;
    }
}
