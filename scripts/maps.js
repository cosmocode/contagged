function drawMap(coords) {
    var map = L.map('map');
    if (coords.length == 1) {
        map.setView([coords[0].lat, coords[0].lon], 14);
    }

    // create the tile layer with correct attribution
    var osmUrl = "http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.jpg";
    var subDomains = ['otile1','otile2','otile3','otile4'];

    var osmAttrib = '&copy; <a href="http://open.mapquest.co.uk" target="_blank">MapQuest</a>, <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> and contributors.';
    L.tileLayer(
        osmUrl,
        {attribution: osmAttrib, subdomains: subDomains}
    ).addTo(map);

    var markers = [];
    $(coords).each(function(key, coord) {
        var marker = L.marker([coord.lat, coord.lon]).addTo(map);
        marker.bindPopup(coord.address);
        markers.push(marker);
    });
    if (coords.length > 1) {
        var group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds());
    }
}