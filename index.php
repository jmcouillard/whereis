<?php
require_once "config.php";
$lastData = file_get_contents("last.txt");
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
	<link rel="stylesheet" href="main.css">
</head>
<body>
<div id="map"></div>
<div id="locating"><span>Relocalisation en cours...</span></div>
<time id="timeago"></time>
<script>

	var map, marker;
	var lastData = <?= $lastData ?>;
	var updateDelay = 60; // seconds

	function initMap() {

		var myLatlng = new google.maps.LatLng(lastData.latitude, lastData.longitude);

		map = new google.maps.Map(document.getElementById('map'), {
			scrollwheel: false,
			center: myLatlng,
			zoom: 13
		});

		var image = {
			url: 'custompin_s.png',
			scaledSize: new google.maps.Size(34, 55)
		};

		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: image
		});

		updateTimestamp(lastData.timeStamp);
		jQuery("#timeago").timeago();

		setInterval(updateMap, updateDelay * 1000);
		updateMap();
	}

	function updateMap() {
		$.ajax({
			dataType: "json",
			url: "api.php",
			success: apiCallback,
			error: errorCallback
		});
	}

	function errorCallback(data) {
		jQuery("#locating span").text(data.responseText);

		setTimeout(function () {
			jQuery("#locating").fadeOut();
		}, 3000);

	}

	function apiCallback(data) {
		var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
		marker.setPosition(myLatlng);
		map.setCenter(myLatlng);

		updateTimestamp(data.timeStamp);

		jQuery("#locating").fadeOut();

	}

	function updateTimestamp(timeStamp) {
		var time = new Date(timeStamp).toISOString();
		jQuery("#timeago").attr("datetime", time).timeago("update", time);
	}


</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="jquery.timeago.js"></script>
<script src="jquery.timeago.fr.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= GMAPS_API_KEY ?>&callback=initMap"
		async defer></script>
</body>
</html>