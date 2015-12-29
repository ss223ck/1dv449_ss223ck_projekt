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
    
    document.getElementById("counties").addEventListener("change", getSCBCommunes);
    document.getElementById("submitbutton").addEventListener("click", getBooliHousings);
};

function getSCBCommunes() {
    var counties = document.getElementById("counties").value;
    var result;
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {
            county: counties
        },
        success: function( data ) {
            result = JSON.parse(data);
            createCommuneOptions(result);
        }
    });
    
}

function createCommuneOptions(result) {
    
    var dropDownCommunes = document.getElementById("communes");
    
    dropDownCommunes.innerHTML = "";
    for(var key in result)
    {
        var option = document.createElement("option");
        option.value = result[key];
        option.innerHTML = key;

        dropDownCommunes.appendChild(option);
    }
}

function getBooliHousings() {
    var communes = document.getElementById("communes").value;
    
    var result;
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {
            commune: communes
        },
        success: function( data ) {
            result = JSON.parse(data);
            renderHousingData(result);
        }
    });
}

function renderHousingData(data) {
    

    for(var key in data[0]["listings"]) 
    {
        renderHousingObjects(data[0]["listings"][key]);
        
    }
}
var infowindow;
var markersOnMap = [];
function renderHousingObjects(data) {
    infowindow = new google.maps.InfoWindow({
                                  content: ""
                                  });
    var marker = new google.maps.Marker({
            position: {lat: data["location"]["position"]["latitude"] , lng: data["location"]["position"]["longitude"]},
            map: map,
            title: data["location"]["address"]["streetAddress"]
        });
        
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<h1 id="firstHeading" class="firstHeading">'+ data["location"]["address"]["streetAddress"] +'</h1>'+
            '<div id="bodyContent">'+
            '<p>' + data["objectType"] + '</p>'+
            '<p>Antal rum: ' + data["rooms"] + '</p>' + 
            '<p>Boarea: ' + data["livingArea"] + ' kvadratmeter</p>' + 
            '<p>Pris i kr: ' + data["listPrice"] + '</p>' + 
            '<a href="' + data["url"]+'">Till objektets webbplats</a>' + 
            '</div>'+
            '</div>';


        marker.addListener('click', function() {
            infowindow.close();
            infowindow = new google.maps.InfoWindow({
                content: ""
                });
            infowindow.content = contentString;
            infowindow.open(map, marker);
            });
        document.getElementById("submitbutton").addEventListener("click", function(){
            marker.setMap(null);
        });
        markersOnMap.push(marker);
}