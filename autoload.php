<?php

function autocargar($classname) {
    if (file_exists('controllers/' . $classname . '.php')) {
        include 'controllers/' . $classname . '.php';
    } elseif (file_exists('models/' . $classname . '.php')) {
        include 'models/' . $classname . '.php';
    }
}

spl_autoload_register('autocargar');