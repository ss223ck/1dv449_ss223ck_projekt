<?php

require_once("Controller/MasterController.php");
require_once("Model/HandleRequest.php");
require_once("View/RenderOutput.php");

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$handleRequest = new \model\HandleRequest();

$renderView = new \view\RenderOutput();

$controller = new \contoller\MasterController($handleRequest, $renderView);
$controller->StartApplication();