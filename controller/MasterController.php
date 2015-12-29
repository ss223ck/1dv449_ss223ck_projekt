<?php

namespace contoller;

class MasterController {
    
    public function __construct($handleRequest, $renderView, $formatView) {
        $this->handleRequestModel = $handleRequest;
        $this->renderOutput = $renderView;
        $this->formatOutput = $formatView;
    }
    
    public function StartApplication() {
        $returnValues;
        if(isset($_POST["county"]))
        {
            $returnValues = $this->handleRequestModel->getSpecificCommunes($_POST["county"]);
            $this->renderOutput->sendAjaxResponse($returnValues);
        }
        else if(isset($_POST["commune"]))
        {
            $housingAndSCBData = $this->handleRequestModel->StartGatherInfo($_POST["commune"]);
            $this->renderOutput->sendAjaxResponse($housingAndSCBData);
        }
        else
        {
            $dropDowns = $this->formatOutput->formatDropDownControllers($this->handleRequestModel->getCombinedCommuneNameAndCode());
            $this->renderOutput->renderMap($dropDowns);
        }
    }
}