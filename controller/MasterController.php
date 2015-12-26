<?php

namespace contoller;

class MasterController {
    
    public function __construct($handleRequest, $renderView) {
        $this->handleRequestModel = $handleRequest;
        $this->renderOutput = $renderView;
    }
    
    public function StartApplication() {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->handleRequestModel->StartGatherInfo($_POST["searchField"]);
        }
        
        $this->renderOutput->renderMap();
    }
}