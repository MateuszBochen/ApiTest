<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 17.11.18
 * Time: 19:38
 */

namespace Library\DependencyInjection;


class PrepareFunctionArguments extends Container
{
    private $arguments = [];
    private $allParams = 0;
    private $resolve = 0;

    private $hardTypes = ['int', 'string', 'bool', 'double', 'float', 'array'];

    public function __construct(array $params, array $data = null)
    {
        $this->allParams = count($params);
        $classLoader = new ClassLoader();

        foreach($params as $param) {
            $type = (string)$param->getType();
            $hasType = $param->hasType();
            $name = $param->getName();

            if($hasType && $this->has($type)) {
                $this->arguments[] = $this->get($type);
                $this->resolve += 1;
            } elseif($hasType) {
                if(!in_array($type, $this->hardTypes)) {
                    $this->arguments[] = $classLoader->loadClass($type, ['param' => $name]);
                    $this->resolve += 1;
                } else {
                    if ($data && isset($data[$name])) {
                        $this->arguments[] = $data[$name];
                    } else {
                        $this->arguments[] = $name;
                    }
                }
            } else {
                $this->arguments[] = $param->getName();
            }

            /*if ($param->isPassedByReference()) {
                $callArguments[] = &$controllerArgs[$param->getName()];
            } else {
                $callArguments[] = $controllerArgs[$param->getName()];
            }*/
        }
    }

    public function allArgumentsResolve()
    {
        return $this->resolve === $this->allParams;
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}
