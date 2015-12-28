<?php

namespace contoller;

class MasterController {
    
    public function __construct($handleRequest, $renderView, $formatView) {
        $this->handleRequestModel = $handleRequest;
        $this->renderOutput = $renderView;
        $this->formatOutput = $formatView;
    }
    
    public function StartApplication() {
        if(isset($_POST["county"]))
        {
            
        }
        else if(isset($_POST["commune"]))
        {
            $this->handleRequestModel->StartGatherInfo($_POST["commune"]);
        }
        $dropDowns = $this->formatOutput->formatDropDownControllers($this->handleRequestModel->getCommuneCodes());
        $this->renderOutput->renderMap($dropDowns);
    }
}