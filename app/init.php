<?php

/**
 * Here, any core file will be required in.
 * The actual MVC-Files will be required in dynamicly via the controller class.
 * For communicating with the database, we'll use Laravel\Eloquent, so we autoload Composer.
 */
require_once "Core/App.php";
require_once "Core/Controller.php";

//Composer Autoloader
require_once "../vendor/autoload.php";
require_once "config/database.php";