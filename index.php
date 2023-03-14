<?php

declare(strict_types=1);


spl_autoload_register(function(string $classNamespace) {
    $path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
    $path = "src/$path.php";
    require_once($path);
});

require_once("src/Utils/debug.php");
$configuration = require_once("config/config.php");


require_once("src/Request.php");
require_once("src/Exception/AppException.php");
require_once("src/Exception/ConfigurationException.php");

use App\Request;
use App\Controller\AbstractController;
use App\Controller\NoteController;
use App\Exception\AppException;
use App\Exception\ConfigurationException;





$request = new Request($_GET, $_POST, $_SERVER);

try {
    AbstractController::initConfiguration($configuration);
    (new NoteController($request))->run();
} catch (ConfigurationException $e) {
    //mail('xxx@xxx.com', 'Errro', $e->getMessage());
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo 'Problem z applikacją, proszę spróbować za chwilę.';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (\Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    dump($e);
}






