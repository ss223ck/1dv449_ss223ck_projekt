<?php

namespace view;

class RenderOutput {
    
    public function renderMap($dropDowns) {
        echo '<!DOCTYPE html>
                <html>
                  <head>
                    <meta charset="utf-8">
                    <link rel="stylesheet" type="text/css" href="style/stylesheet.css">
                    
                    
                  </head>
                  <body>
                    <div id="searchFieldDiv">
                            <label for="searchField">Sök på ett område:</label>
                            ' . $dropDowns . '
                            <input id="submitbutton" type="submit" value="Sök">
                    </div>
                    <div id="map"></div>
                    <script src="scripts/jquery-2.1.4.min.js" ></script>
                    <script src="scripts/GoogleMaps.js" ></script>
                    <script async defer
                      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAez0UBVTT-gu5oNjpUmDK42WxmwOpV7xk&callback=initMap">
                    </script>
                  </body>
                </html>
            ';
    }
    
    public function sendAjaxResponse($values) {
        $values = json_encode($values);
        echo $values;
    }
}