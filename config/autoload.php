<?php

spl_autoload_register(function ($class) {
    $map = include 'classmap.php';

    $moduleName = substr($class, 0, strpos($class, '\\'));
    $classPath = substr($class, strlen($moduleName));
   
    if (isset($map[$moduleName])) {
        require_once $map[$moduleName] . '' . str_replace('\\', DIRECTORY_SEPARATOR, $classPath).'.php';
    } else {
        throw new \Exception('Unable to load "'.$class.'" (module "'.$moduleName.'" - class "'.$classPath.'")', 1);
    }
});
