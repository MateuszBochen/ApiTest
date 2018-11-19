<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 17.11.18
 * Time: 19:46
 */

namespace Library\DependencyInjection;


class LoadYamlServices
{
    private static $yamlServices = [];
    private static $yamlServicesIsLoaded = false;

    public function __construct()
    {
        if (self::$yamlServicesIsLoaded === false) {

        }
    }
}