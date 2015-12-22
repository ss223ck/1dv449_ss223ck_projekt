<?php

namespace contoller;

class MasterController {
    
    public function __construct($handleRequest, $renderView) {
        $this->handleRequestModel = $handleRequest;
        $this->renderOutput = $renderView;
    }
    
    public function StartApplication() {
        $this->handleRequestModel->StartGatherInfo("stockholm");
        $this->renderOutput->renderMap();
    }
}