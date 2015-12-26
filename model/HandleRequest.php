<?php

namespace model;

class HandleRequest{
    
    public function startGatherInfo($areaOfSearch){
        $auth = array();
        $auth['callerId'] = "Mashup_booli_scb";
        $auth['time'] = time();
        $auth['unique'] = rand(0, PHP_INT_MAX);
        $auth['hash'] = sha1($auth['callerId'] . $auth['time'] . "iXEOWZpoW8E7MWaAsmJ4FPCaWPBtONfFzQjClJZo" . $auth['unique']);

        $url = "http://api.booli.se/listings/?q=". $areaOfSearch ."&" . http_build_query($auth);
        $returnedData = $this->gatherInformation($url);
        $this->saveJsonData($returnedData);
        
        $scbKoder = json_decode($this->gatherInformation("http://api.scb.se/OV0104/v1/doris/sv/ssd/START/BO/BO0104/BO0104T01"));
        
        $scbData = $this->gatherInformationPost("http://api.scb.se/OV0104/v1/doris/sv/ssd/START/BO/BO0104/BO0104T01");
        $scbData = json_decode($scbData);
    }
    
    private function saveJsonData($data){
        $jsonListings = fopen("cache/listings.txt", "w");
        fwrite($jsonListings, $data);
        fclose($jsonListings);
    }
    
    private function gatherInformationPost($url) {
                                                                  
        $data_string = '{
                    "query": [
                      {
                        "code": "Region",
                        "selection": {
                          "filter": "vs:RegionKommun07",
                          "values": [
                            "0163"
                          ]
                        }
                      },
                      {
                        "code": "Hustyp",
                        "selection": {
                          "filter": "item",
                          "values": [
                            "SMÅHUS",
                            "FLERBOST",
                            "ÖVRHUS",
                            "SPEC"
                          ]
                        }
                      },
                      {
                        "code": "Tid",
                        "selection": {
                          "filter": "item",
                          "values": [
                            "2014"
                          ]
                        }
                      }
                    ],
                    "response": {
                      "format": "json-stat"
                    }
                  }';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                       
        );         
        
        try
        {
            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpCode != 200)
            {
                throw new Exception("Felkod när hämtningen av data skedde");
            }
            
        } catch (Exception $ex) {
            curl_close($ch);
            throw new Exception("not implemented");
        }
        return $data;
    }

    private function gatherInformation($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        try
        {
            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpCode != 200)
            {
                throw new Exception("Felkod när hämtningen av data skedde");
            }
            
        } catch (Exception $ex) {
            curl_close($ch);
            throw new Exception("not implemented");
        }
        return $data;
    }
        
    private function gatherElementData($data, $Element, $returnNode = false){
        $dataArray = array();
        
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument;
        $dom->loadHTML($data);
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query($Element);
        
        
        
        if($returnNode) {
            return $nodes;
        }
        else
        {
            foreach($nodes as $elemntToFetch) {
                $dataArray[] = $elemntToFetch->nodeValue;
            }
            return $dataArray;
        }
    }
}