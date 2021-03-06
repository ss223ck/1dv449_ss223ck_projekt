<?php

require_once("Controller/MasterController.php");
require_once("Model/HandleRequest.php");
require_once("View/RenderOutput.php");
require_once("View/FormatOutput.php");

error_reporting(E_ALL);
ini_set('display_errors', 'Off');

$handleRequest = new \model\HandleRequest();

$renderView = new \view\RenderOutput();
$formatOutput = new \view\FormatOutput();

$controller = new \controller\MasterController($handleRequest, $renderView, $formatOutput);
$controller->StartApplication();
