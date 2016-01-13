<?php

namespace view;

class FormatOutput {
    
    //Formating the dropdowns for the client page
    public function formatDropDownControllers($formatedCommunesAndCodes) {
        $FormatedDropDowns = "";
        $formatedDropDownsCommunes = "";
        $formatedDropDownsCounties = "";
        
        foreach($formatedCommunesAndCodes as $communeName => $communeCode)
        {
            //If the code is two numbers long then it's a county
            if(strlen($communeCode) === 2 )
            {
                $formatedDropDownsCounties .= '<option value="'. $communeCode .'">' . $communeName . '</option>';
            }
        }
        
        $FormatedDropDowns = '
            <select id="counties" class="dropDowns">
                ' . $formatedDropDownsCounties . '
            </select>
            <select id="communes" name="commune" class="dropDowns">
                
            </select>
            ';
        return $FormatedDropDowns;
    }
    
}