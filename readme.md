# Simple Geo Points

Simple Geo Points contains two PHP functions which return total driving distance (or actual geographic distance) between any number of points, or driving times between those points.  The functions rely on the Google Maps public API.  No key is required for moderate usage and there is no fee.  You may geocode 2,500 locations per day, as per Google's terms: https://developers.google.com/maps/documentation/geocoding/

Simple Geo Points aims to simplify the common task, "How do I get the distance between two points?"  It answers this question in terms of distance between two latitude/longitude pairs ("as the crow flies"), or using real driving routes, or by approximate driving time.  The functions return simple numerical values, in the units of your choice.

#### geoDistance($locations, $unit="km", $asTheCrowFlies=false)
#### geoDuration($locations, $unit="hm")


In theory, any number of locations may be included in the locations array, but each location will register as a hit against the Google Maps API geocoding limit of 2,500 per day.  The locations array can be as diverse as Google's geocoder will accept; ie. you can include full addresses, zip codes, landmarks, lat/lng values, etc.

### Important Legal Reminder

According to the Google Maps API Terms of Service License Restrictions, you must display these results in conjuction with a Google Map.  Again: you may NOT simply return these results without displaying them on a map.  This is meant to be a helper function, and I am not responsible for any violation of Google's Terms of Service.  Terms are available here: https://developers.google.com/maps/terms#section_10_12

## Usage

``` php

<?php
include "simple-geo-points.php";

$locations = array("30 Rockefeller Plaza, New York, NY 10012", "1600 Pennsylvania Avenue, Washington, DC 20500", "Cathedral of Learning, Pittsburgh, PA 15260", "44432");

echo "Distance (km): ".geoDistance($locations,"km");
// Distance (km): 850.478

echo "Distance as the crow flies (mi): ".geoDistance($locations,"m",true);
// Distance as the crow flies (mi): 441.13483543075

echo "Duration (h:m): ".geoDuration($locations,"hm");
// Duration (h:m): 10:16

echo "Duration (m): ".geoDuration($locations,"m");
// Duration (m): 616.11666666667

?>

```