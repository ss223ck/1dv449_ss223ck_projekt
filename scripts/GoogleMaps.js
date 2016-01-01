"use strict";


var map;

function initMap() {
    
    map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 59.329333, lng: 18.068633},
      zoom: 8
    });
    
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
            if($.type(result[0]) === 'string' || $.type(result[1]) === 'string')
            {
                displayErrorMessage(result);
            }
            else
            {
                renderHousingData(result);
            }
        }
    });
}
function displayErrorMessage(results) {
    createErrorMessage(results[0]);
    createErrorMessage(results[1]);
}
function createErrorMessage(result) {
    if($.type(result) === 'string')
    {
        var elementErrorMessage = document.createElement("p");
        var errorDiv = document.getElementById("errorDisplayWrapper");
        elementErrorMessage.innerHTML = result;
        elementErrorMessage.className = "errorMessage";
        
        errorDiv.appendChild(elementErrorMessage);
    }
}
function renderHousingData(data) {
    var errorDiv = document.getElementById("errorDisplayWrapper");
    errorDiv.innerHTML = "";
    for(var key in data[0]["listings"]) 
    {
        renderHousingObjects(data[0]["listings"][key]);
    }
    renderScbInformation(data[1]["dataset"]);
}
function renderScbInformation(dataScb) {
    var table = document.getElementById("tableForInformationAboutHousing");
    var tableHead = document.createElement("thead");
    var tableRow = document.createElement("tr");
    var tableColHeadOne = document.createElement("th");
    var tableColHeadTwo = document.createElement("th");
    
    table.innerHTML = "";
    tableColHeadTwo.innerHTML = first(dataScb["dimension"]["Tid"]["category"]["label"]);
    tableRow.appendChild(tableColHeadOne);
    tableRow.appendChild(tableColHeadTwo);
    tableHead.appendChild(tableRow);
    table.appendChild(tableHead);
    
    var arrayOfHouseAndIndex = [];
    for(var key in dataScb["dimension"]["Hustyp"]["category"]["index"])
    {
        arrayOfHouseAndIndex[dataScb["dimension"]["Hustyp"]["category"]["index"][key]] = key;
    }
    for(var key in dataScb["dimension"]["Hustyp"]["category"]["label"])
    {
        if(key == arrayOfHouseAndIndex[0])
        {
            arrayOfHouseAndIndex[0] = dataScb["dimension"]["Hustyp"]["category"]["label"][key];
        }
        else if(key == arrayOfHouseAndIndex[1])
        {
            arrayOfHouseAndIndex[1] = dataScb["dimension"]["Hustyp"]["category"]["label"][key];
        }
        else if(key == arrayOfHouseAndIndex[2])
        {
            arrayOfHouseAndIndex[2] = dataScb["dimension"]["Hustyp"]["category"]["label"][key];
        }
        else
        {
            arrayOfHouseAndIndex[3] = dataScb["dimension"]["Hustyp"]["category"]["label"][key];
        }
    }
    
    var tableBody = document.createElement("tbody");
    for(var i=0; i < 4; i++)
    {
        var tableRow = document.createElement("tr");
        var tableColOne = document.createElement("td");
        var tableColTwo = document.createElement("td");
        tableColOne.innerHTML = arrayOfHouseAndIndex[i];
        tableColTwo.innerHTML = dataScb["value"][i];
        
        tableRow.appendChild(tableColOne);
        tableRow.appendChild(tableColTwo);
        tableBody.appendChild(tableRow);
    }
    
    table.appendChild(tableBody);
    
}
function first(array){
    for(var value in array)
    {
        return value;
    }
}
var infowindow;
function renderHousingObjects(dataBooli) {
    infowindow = new google.maps.InfoWindow({
                                  content: ""
                                  });
    var marker = new google.maps.Marker({
            position: {lat: dataBooli["location"]["position"]["latitude"] , lng: dataBooli["location"]["position"]["longitude"]},
            map: map,
            title: dataBooli["location"]["address"]["streetAddress"]
        });
    var center = new google.maps.LatLng(dataBooli["location"]["position"]["latitude"], dataBooli["location"]["position"]["longitude"]);
    map.panTo(center);
    var contentString = '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h1 id="firstHeading" class="firstHeading">'+ dataBooli["location"]["address"]["streetAddress"] +'</h1>'+
        '<div id="bodyContent">'+
        '<p>' + dataBooli["objectType"] + '</p>'+
        '<p>Antal rum: ' + dataBooli["rooms"] + '</p>' + 
        '<p>Boarea: ' + dataBooli["livingArea"] + ' kvadratmeter</p>' + 
        '<p>Pris i kr: ' + dataBooli["listPrice"] + '</p>' + 
        '<a href="' + dataBooli["url"]+'">Till objektets webbplats</a>' + 
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
}