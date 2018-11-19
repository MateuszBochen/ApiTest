<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 13:00
 */

namespace Library\Config;


final class ConfigParam
{
    private $param = null;

    public function __construct(ConfigParser $configParser, string $param)
    {
        // $this->configParser = $configParser;

        $param = $configParser->getParam($param);
        $this->param = $param;
    }

    public function getParam()
    {
        return $this->param;
    }
}
