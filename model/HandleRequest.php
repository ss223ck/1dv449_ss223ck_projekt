<?php

namespace model;

class HandleRequest{
    //starts to gather the information for the specific commune
    public function startGatherInfo($areaOfSearch){
        $housingsAndSCBData;
        $communeToSearch = $this->matchCodeToCommune($areaOfSearch);
        $booliUrl = $this->createURLForBooli($communeToSearch);
        $housingsAndSCBData[] = json_decode($this->gatherInformation($booliUrl));
        
        $housingsAndSCBData[] = json_decode($this->gatherInformationPost("http://api.scb.se/OV0104/v1/doris/sv/ssd/START/BO/BO0104/BO0104T01", $areaOfSearch));
        return $housingsAndSCBData;
    }
    
    private function matchCodeToCommune($code) {
        $communesAndCodes = $this->getCombinedCommuneNameAndCode();
        $communeName = array_search($code, $communesAndCodes);
        $communeName = str_replace(" ", "-", $communeName);
        return $communeName;
    }
    private function createURLForBooli($areaOfSearch) {
        $auth = array();
        $auth['callerId'] = "Mashup_booli_scb";
        $auth['time'] = time();
        $auth['unique'] = rand(0, PHP_INT_MAX);
        $auth['hash'] = sha1($auth['callerId'] . $auth['time'] . "iXEOWZpoW8E7MWaAsmJ4FPCaWPBtONfFzQjClJZo" . $auth['unique']);

        return "http://api.booli.se/listings/?q=". $areaOfSearch ."&" . http_build_query($auth);
    }
    
    //Saves the diffrent communes and commune codes
    private function saveCommuneCodes() {
        $codesAndCommunes;
        
        $scbKoder = json_decode($this->gatherInformation("http://api.scb.se/OV0104/v1/doris/sv/ssd/START/BO/BO0104/BO0104T01"), true);
        $codesAndCommunes["values"] = $scbKoder["variables"][0]["values"];
        $codesAndCommunes["valueTexts"] = $scbKoder["variables"][0]["valueTexts"];
        
        $communeListings = fopen("cache/communeListings.txt", "w");
        fwrite($communeListings, serialize($codesAndCommunes));
        fwrite($communeListings, PHP_EOL);
        fwrite($communeListings, date('Y-m-d'));
        fclose($communeListings);
    }
    
    //Gets the specific communes within the county
    public function getSpecificCommunes($county) {
        $communesAndCounties = $this->getCommuneCodes();
        $endOfCounty = false;
        $arrayOfCommunes;
        
        $indexOfCounty = array_search($county, $communesAndCounties["values"]);
        
        while(!$endOfCounty) {
            $indexOfCounty++;
            if(strlen($communesAndCounties["values"][$indexOfCounty]) != 2)
            {
                $arrayOfCommunes[$communesAndCounties["valueTexts"][$indexOfCounty]] = $communesAndCounties["values"][$indexOfCounty];
            }
            else
            {
                $endOfCounty = true;
            }
        }
        return $arrayOfCommunes;
    }
    
    //Returns all the commune names and commune codes
    public function getCommuneCodes() {
        $lastUpdateDate = "";
        $communeListings = fopen("cache/communeListings.txt", "r");
        
        if(filesize("cache/communeListings.txt") > 0)
        {
            $array = explode(PHP_EOL, fread($communeListings, filesize("cache/communeListings.txt")));
            $lastUpdateDate = date('Y-m-d', strtotime($array[1]));
        }
        
        if($lastUpdateDate < date("Y-m-d"))
        {
            $this->saveCommuneCodes();
            $array = explode(PHP_EOL, fread($communeListings, filesize("cache/communeListings.txt")));
        }
        fclose($communeListings);
        $communesAndNames = unserialize($array[0]);
        
        return $communesAndNames;
    }
    //Combines the commune names with their codes
    public function getCombinedCommuneNameAndCode() {
        $formatedArrayForCommunes;
        
        $communesAndNames = $this->getCommuneCodes();
        
        for($i = 0; $i < count($communesAndNames["values"]); $i++) {
            
            $formatedArrayForCommunes[$communesAndNames["valueTexts"][$i]] = $communesAndNames["values"][$i];
            
        }
        return $formatedArrayForCommunes;
    }

    // Gather the data from SCB. This should be made a more generall function
    private function gatherInformationPost($url, $areaOfSearch) {
                                                                  
        $data_string = '{
                    "query": [
                      {
                        "code": "Region",
                        "selection": {
                          "filter": "vs:RegionKommun07",
                          "values": [
                            "' . $areaOfSearch . '"
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
                $data = json_encode("Fel när hämtningen av data skedde från scb");
            }
            
        } catch (Exception $ex) {
            curl_close($ch);
            $data = json_encode($ex);
        }
        return $data;
    }

    //Gathers information from  booli
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
                $data = json_encode("Fel när hämtningen av data skedde från booli");
            }
            
        } catch (Exception $ex) {
            curl_close($ch);
            $data = json_encode($ex);
        }
        return $data;
    }
}