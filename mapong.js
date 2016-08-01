function init () {
  map = L.map('map').setView([-34.6,-58.9], 8);

  L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1Ijoid21hcmNvcyIsImEiOiIxdm9FUGM0In0.DQQ0YAPejBWJOQKveMl4pw', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
    maxZoom: 18,
    id: 'streets-v9',
    accessToken: 'pk.eyJ1Ijoid21hcmNvcyIsImEiOiIxdm9FUGM0In0.DQQ0YAPejBWJOQKveMl4pw'
  }).addTo(map);

  load_data();
}

function load_data () {

  if (!GPS) {
    console.log("No coordinate column provided assuming 0");
    GPS = 0;
  }

  $.getJSON('data.php',
  {
    'spreadsheetid': SPREADSHEETID,
    'range': RANGE
  },function(d){

    // mapping thingy
    var points = L.featureGroup();
    $.each(d, function (i,v) {
      try {

        //parse coordinates
        var c = v[GPS].split(',').map(Number);
          if( c.length == 2 )

            //if labels provided
            var l = '';
            if(LABEL) { l = v[LABEL]; }

            var i = L.divIcon({className: 'div-icon', html: l});
            var m = L.marker(c, {icon: i});
            points.addLayer(m);
      }
      catch (e) {
        //console.log(c);
      }
      points.addTo(map);

    });

    map.fitBounds(points.getBounds());
  });
}
