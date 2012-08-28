<?php
/*
Simple Geo Points
https://github.com/alexscottmoore/Simple-Geo-Points

by Alex Moore
8/28/2012
_____________

USAGE:
include "simple-geo-points.php";

$locations = array("30 Rockefeller Plaza, New York, NY 10012", "1600 Pennsylvania Avenue, Washington, DC 20500", "Cathedral of Learning, Pittsburgh, PA 15260", "44432");

echo "Distance (km): ".geoDistance($locations,"km");
echo "<br />";
echo "Distance as the crow flies (mi): ".geoDistance($locations,"m",true);
echo "<br />";
echo "Duration (h:m): ".geoDuration($locations,"hm");
echo "<br />";
echo "Duration (m): ".geoDuration($locations,"m");

*/
function geoDistance($locations, $unit="km", $crowFlies=false) {
	foreach ($locations as $location) {
		if (!isset($start)) {
			$distance = 0;
			$start = urlencode($location);
		}
		else {
			$end = urlencode($location);
			$xml = simplexml_load_file("http://maps.google.com/maps/api/directions/xml?origin=".$start."&destination=".$end."&sensor=false");
			if ($crowFlies==true) {
				$lat1 = (float)$xml->route->leg->start_location->lat;
				$lng1 = (float)$xml->route->leg->start_location->lng;
				$lat2 = (float)$xml->route->leg->end_location->lat;
				$lng2 = (float)$xml->route->leg->end_location->lng;
				$distance += latLngDistance($lat1, $lng1, $lat2, $lng2);
			}
			else { $distance += $xml->route->leg->distance->value; }
			$start = $end;
		}
	}
	if ($unit=="km" || $unit=="k") { return $distance / 1000; }
	else { return ($distance * .621371192) / 1000; }
}
function geoDuration($locations, $unit="hm") {
	foreach ($locations as $location) {
		if (!isset($start)) {
			$duration = 0;
			$start = urlencode($location);
		}
		else {
			$end = urlencode($location);
			$xml = simplexml_load_file("http://maps.google.com/maps/api/directions/xml?origin=".$start."&destination=".$end."&sensor=false");
			$duration += $xml->route->leg->duration->value;
			$start = $end;
		}
	}
	if ($unit=="hm") { return hoursMinutes($duration / 60); }
	elseif ($unit=="h") { return $duration / 3600; }
	else { return $duration / 60; }
}
function latLngDistance($lat1, $lng1, $lat2, $lng2) {
	$pi80 = M_PI / 180;
	$dlat = ($lat2 - $lat1) * $pi80;
	$dlng = ($lng2 - $lng1) * $pi80;
	$lat1 *= $pi80;
	$lat2 *= $pi80;
	$r = 6372.797;
	$a = SIN($dlat / 2) * SIN($dlat / 2) + SIN($dlng / 2) * SIN($dlng / 2) * COS($lat1) * COS($lat2);
	$c = 2 * ATAN2(SQRT($a), SQRT(1 - $a));
	$m = $r * $c * 1000;
	return $m;
}
function hoursMinutes($mins) {
	$minutes=$mins % 60;
	$number=explode('.',($mins / 60));
	$hours=$number[0];
	return str_pad($hours,2,"00",STR_PAD_LEFT).":".str_pad($minutes,2,"00",STR_PAD_LEFT);
}