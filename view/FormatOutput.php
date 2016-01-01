<?php

namespace view;

class FormatOutput {
    
    public function formatDropDownControllers($formatedCommunesAndCodes) {
        $FormatedDropDowns = "";
        $formatedDropDownsCommunes = "";
        $formatedDropDownsCounties = "";
        
        foreach($formatedCommunesAndCodes as $communeName => $communeCode)
        {
            //If the code is two numbers then it's a county
            if(strlen($communeCode) === 2 )
            {
                $formatedDropDownsCounties .= '<option value="'. $communeCode .'">' . $communeName . '</option>';
            }
        }
        
        $FormatedDropDowns = '
            <select id="counties">
                ' . $formatedDropDownsCounties . '
            </select>
            <select id="communes" name="commune">
                
            </select>
            ';
        return $FormatedDropDowns;
    }
    
}