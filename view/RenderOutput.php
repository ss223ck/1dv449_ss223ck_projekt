<?php

namespace view;

class RenderOutput {
    
    public function renderMap() {
        echo '<!DOCTYPE html>
                <html>
                  <head>
                    <meta charset="utf-8">
                    <link rel="stylesheet" type="text/css" href="style/stylesheet.css">
                    
                    
                  </head>
                  <body>
                    <div id="searchFieldDiv">
                        <form method="post">
                            <label for="searchField">Sök på ett område:</label>
                            <input type="text" id="searchField" name="searchField">
                            <input type="submit" value="Sök">
                        </form>
                    </div>
                    <div id="map"></div>
                    <script src="scripts/GoogleMaps.js" ></script>
                    <script async defer
                      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAez0UBVTT-gu5oNjpUmDK42WxmwOpV7xk&callback=initMap">
                    </script>
                  </body>
                </html>
            ';
    }
}