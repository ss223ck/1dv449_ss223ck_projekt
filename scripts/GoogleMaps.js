"use strict";


var map;

function initMap() {
    
    map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 59.329333, lng: 18.068633},
      zoom: 8
    });
    
    var xmlhttp = new XMLHttpRequest();
    var url = "http://localhost/1dv449_ss223ck_projekt/cache/listings.txt";
    
    xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var jsonReturn = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
    
    document.getElementById("submitbutton").addEventListener("click", getBooliValues);
};

function getBooliValues() {
    var counties = document.getElementById("counties").value;
    
    var xmlhttp = new XMLHttpRequest();
    var url = "index.php";
    
    xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var jsonReturn = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}