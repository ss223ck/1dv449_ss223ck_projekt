<?php

namespace contoller;

class MasterController {
    
    public function __construct($handleRequest, $renderView, $formatView) {
        $this->handleRequestModel = $handleRequest;
        $this->renderOutput = $renderView;
        $this->formatOutput = $formatView;
    }
    
    public function StartApplication() {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->handleRequestModel->StartGatherInfo($_POST["commune"]);
        }
        $dropDowns = $this->formatOutput->formatDropDownControllers($this->handleRequestModel->getCommuneCodes());
        $this->renderOutput->renderMap($dropDowns);
    }
}