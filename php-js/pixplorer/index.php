<?php

/**
*
* @author Ivan Ivković <ivan.ivkovichh@gmail.com>
* @version 1.0
*
*
* Route rules:
*
* MAIN ROUTE STANDARD: http://www.pixpres.so/page/action/criteria1/criteria2/criteria3/criteria4
*
* ROUTE PRIORITY: http://www.pixpres.so/page/action/userID/categoryID/cityID/picID
* The above url does not represent an example of URL standard, but priority of models that are passed via URL. I.E. user must * go before categoryID. If user is not specified, category goes first.
*
*
*/

/**
* Za početak ćemo prijaviti sve errore.
*/

ini_set('display_errors', true);
error_reporting(E_ALL);

/**
* Pokreni sesiju.
*/

session_start();

/**
* Base path, includanje osnovnih fajlova.
*/

define('ROOT_SITE_PATH', realpath(dirname(__FILE__)));

include(ROOT_SITE_PATH . '/includes/functions.php'); # Globalne funkcije.
include(ROOT_SITE_PATH. '/application/Config.php'); # Konstante, pathovi etc.


/**
* Initialize config.
*/

Conf::include_app();
Conf::init();

/**
* Registry nam drži sve objekte za MVC.
*/

$registry = new Registry;

/**
* Facebook init, includanje svega vezano za facebook sdk.
*/
include(ROOT_SITE_PATH. '/includes/fbconfig.php'); # FB connect includes.

$registry -> user = new User($fbconfig); # User authorization.


/**
* Autorizacija korisnika
*/
include(ROOT_SITE_PATH. '/includes/auth.php');


/**
* Rročitaj router / url path.
*/
$registry -> router = new Router($registry);
$registry -> router -> setDirPath(ROOT_SITE_PATH . '/views/pages');

$registry -> template = new TPL($registry);
$registry -> router -> loader(); # Controller loader.


/**
* Diskonekt s bazom.
*/
DB::disconnect();

?>