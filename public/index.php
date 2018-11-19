<?php

define('ROOT_DIR', realpath(dirname(__FILE__).'/..'));


/*function exception_handler($exception) {
    echo '<div class="alert alert-danger">';
    echo '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . '\' with message ';
    echo $exception->getMessage() . '<br>';
    echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
    echo 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>';
    echo '</div>';
}
set_exception_handler('exception_handler');*/

include(ROOT_DIR . '/Library/AutoLoader/AutoLoader.php');


$autoLoader = new Library\AutoLoader\AutoLoader();
$autoLoader->register();

$kernel = new \Library\Kernel();
$kernel->init();
