<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 15:29
 */

namespace Library\DataBase;


class Helper
{
    public static function transformToSnakeCase(string $string, bool $plural = false) : string
    {
        return strtolower(preg_replace('/\B[A-Z]/', "_$0", $string)).($plural ? 's' : '');
    }

    public static function transformToCamelCase(string $string) : string
    {
        return preg_replace_callback(
            '/\B(_([a-z]))/',
            function($matches){
                if (isset($matches[2])) {
                    return strtoupper($matches[2]);
                }
            },
            $string
        );
    }

    public static function getModelName(string $modelClassName) : string
    {
        $modelNameArray = explode('\\', $modelClassName);

        return end($modelNameArray);
    }

    public static function getTableName(string $modelClassName)
    {
        $modelName = self::getModelName($modelClassName);

        return strtolower(self::transformToSnakeCase($modelName));
    }

}
