<?php

namespace view;

class RenderOutput {
    
    public function renderMap($dropDowns) {
        echo '<!DOCTYPE html>
                <html>
                  <head>
                    <meta charset="utf-8">
                    <link rel="stylesheet" type="text/css" href="style/stylesheet.min.css">
                    
                    
                  </head>
                  <body>
                    <div id="sidePanelWrapepr">
                        <div id="searchFieldDiv" class="innerSidePanelWrappers">
                                <label id="searchLabel" for="searchField">Sök på ett område:</label>
                                ' . $dropDowns . '
                                <input id="submitbutton" type="submit" value="Sök">
                        </div>
                        <div class="innerSidePanelWrappers">
                            <table id="tableForInformationAboutHousing">

                            </table>
                        </div>
                        <div id="errorDisplayWrapper">
                        </div>
                    </div>
                    <div id="map"></div>
                    <script src="scripts/jquery-2.1.4.min.js" ></script>
                    <script src="scripts/GoogleMaps.min.js" ></script>
                    <script async defer
                      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAez0UBVTT-gu5oNjpUmDK42WxmwOpV7xk&callback=initMap">
                    </script>
                  </body>
                </html>
            ';
    }
    
    //Sends the ajaxresponse to the client
    public function sendAjaxResponse($values) {
        $values = json_encode($values);
        echo $values;
    }
}