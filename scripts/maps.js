
var gmap_data = Array();
var gmap_centered = false;
var gmap_bounds = new GLatLngBounds();

function gmap_loader(){
    if (!GBrowserIsCompatible()) {
    	//return;
    };

    var map = new GMap2(document.getElementById("google_map"));
    var geocoder = new GClientGeocoder();
    map.addControl(new GLargeMapControl());
    map.addControl(new GMapTypeControl());
    map.setCenter(new GLatLng(52.514863,13.381863),10);
    for (var i=0; i<gmap_data.length; i++){
        var t = 1;
        if(i>10) t = 1000;
        if(i>20) t = 2000;
        gmap_add(map,geocoder,gmap_data[i].adr,gmap_data[i].info,t);

    }
}

function gmap_add(map,gc,adr,info,t){
    setTimeout(function(){
        gc.getLatLng(
            adr,
            function(point) {
                if (!point) {
                    //alert(address + " not found");
                } else {
                    gmap_bounds.extend(point);
                    var zoom = map.getBoundsZoomLevel(gmap_bounds);
                    if (zoom > 17) zoom = 17;
                    map.setCenter(gmap_bounds.getCenter(), zoom);

                    var marker = new GMarker(point);
                    map.addOverlay(marker);
                    GEvent.addListener(marker, "click", function(){
                        this.openInfoWindowHtml( info );
                    });
                }
            }
        );
    },t);
}

