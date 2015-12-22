'use strict'


var map;

function initMap() {
    
    map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 59.329333, lng: 18.068633},
      zoom: 8
    });
            
    var xmlhttp = new XMLHttpRequest();
    var url = "http://api.sr.se/api/v2/traffic/messages?format=json&size=100";
    var trafficInformation;
    
    var a = Math.abs(new Date() - new Date(localStorage["timeOfLastCall"]));
    a = a/1000;
    a = a/60;
    if(localStorage["timeOfLastCall"] == null || a > 5){
        xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                localStorage["messages"] = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
        localStorage["timeOfLastCall"] = new Date();
    }
    if(localStorage["messages"] != null)
    {
        renderMarkers();
    }
    else
    {
        alert("sidan kunde inte ladda in resurser, testa att ladda om sidan!")
    }
    
};

function renderMarkers(){
        var infowindow = new google.maps.InfoWindow({
                      content: ""
                      });;
        var trafficInformation = JSON.parse(localStorage["messages"]);
            trafficInformation["messages"].sort(function(a, b){
                if (a.createddate > b.createddate)
                    return -1;
                if (a.createddate < b.createddate)
                    return 1;
                return 0; 
            });
            
            var i = 0;
            trafficInformation["messages"].forEach(function(obj){
                i++;
                var date = new Date(parseInt(obj.createddate.replace('/Date(', '')));
                var localtime = date.toDateString('sv-SE');
                var category;
                var priority;
                var marker = new google.maps.Marker({
                    position: {lat: obj.latitude , lng: obj.longitude},
                    map: map,
                    title: obj.title
                    });
                var trafficM = document.getElementById("trafficMessages");
                var message = document.createElement("div");
                var text = document.createElement("p");
                
                message.setAttribute("id", "messageDiv" + i);
                switch(obj.category) {
                    case 0:
                        category = "Vägtrafik"
                        document.getElementById("roadTraffic").addEventListener('click', function(){
                            if(marker.visible)
                            {
                                message.className = "hidden";
                                marker.setVisible(false);
                                
                            }
                            else
                            {
                                message.className = "trafficMessageDiv";
                                marker.setVisible(true);
                            }
                        });
                        break;
                    case 1:
                        category = "Kollektivtrafik"
                        document.getElementById("collectivTraffic").addEventListener('click', function(){
                            if(marker.visible)
                            {
                                message.className = "hidden";
                                marker.setVisible(false);
                                
                            }
                            else
                            {
                                message.className = "trafficMessageDiv";
                                marker.setVisible(true);
                            }
                        });
                        break;
                    case 2:
                        category = "Planerad störning"
                        document.getElementById("PlanedDisturbance").addEventListener('click', function(){
                            if(marker.visible)
                            {
                                message.className = "hidden";
                                marker.setVisible(false);
                                
                            }
                            else
                            {
                                message.className = "trafficMessageDiv";
                                marker.setVisible(true);
                            }
                        });
                        break;
                    case 3:
                        category = "Övrigt"
                        document.getElementById("other").addEventListener('click', function(){
                            if(marker.visible)
                            {
                                message.className = "hidden";
                                marker.setVisible(false);
                                
                            }
                            else
                            {
                                message.className = "trafficMessageDiv";
                                marker.setVisible(true);
                            }
                        });
                        break;
                }
                switch(obj.priority) {
                    case 1:
                        priority = "Mycket allvarlig händelse";
                        break;
                    case 2:
                        priority = "Stor händelse";
                        break;
                    case 3:
                        priority = "Störning";
                        break;
                    case 4:
                        priority = "Information";
                        break;
                    case 5:
                        priority = "Mindre störning";
                        break;
                }
                var contentString = '<div id="content">'+
                    '<div id="siteNotice">'+
                    '</div>'+
                    '<h1 id="firstHeading" class="firstHeading">'+ obj.title +'</h1>'+
                    '<div id="bodyContent">'+
                    '<p>' + obj.exactlocation + '</p>'+
                    '<p>' + localtime + '</p>' + 
                    '<p>' + category + '</p>' + 
                    '<p>' + priority + '</p>' + 
                    '<p>' + obj.subcategory + '</p>' + 
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
                    
                
                
                message.className = "trafficMessageDiv";
                text.innerHTML = obj.title + " " + localtime;
                message.appendChild(text);
                trafficM.appendChild(message);
                
                message.addEventListener('click', function(){
                    infowindow.close();
                    infowindow = new google.maps.InfoWindow({
                      content: ""
                      });
                    infowindow.content = contentString;
                    infowindow.open(map, marker);
                });
            });
    }

function openInfoWindow(contentString, marker){
                    infowindow.close();
                    infowindow = new google.maps.InfoWindow({
                      content: ""
                      });
                    infowindow.content = contentString;
                    infowindow.open(map, marker);
}
