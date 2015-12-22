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
        $decodedReturned = json_decode($returnedData);
        $decodedReturned;
    }
    
    private function gatherInformation($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        try
        {
            $data = curl_exec($ch);
            curl_close($ch);
            
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