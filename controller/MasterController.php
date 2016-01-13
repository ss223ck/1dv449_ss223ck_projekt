<?php

namespace controller;

class MasterController {
    
    public function __construct($handleRequest, $renderView, $formatView) {
        $this->handleRequestModel = $handleRequest;
        $this->renderOutput = $renderView;
        $this->formatOutput = $formatView;
    }
    
    public function StartApplication() {
        $returnValues;
        //If the client sent a ajax-request to get the diffrent communes of the county
        if(isset($_POST["county"]))
        {
            $returnValues = $this->handleRequestModel->getSpecificCommunes($_POST["county"]);
            $this->renderOutput->sendAjaxResponse($returnValues);
        }
        //Gets the data for the commune and the housings
        else if(isset($_POST["commune"]))
        {
            $housingAndSCBData = $this->handleRequestModel->StartGatherInfo($_POST["commune"]);
            $this->renderOutput->sendAjaxResponse($housingAndSCBData);
        }
        //Sends the normal html page to the client
        else
        {
            $dropDowns = $this->formatOutput->formatDropDownControllers($this->handleRequestModel->getCombinedCommuneNameAndCode());
            $this->renderOutput->renderMap($dropDowns);
        }
    }
}