<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Montreal');

header('Content-Type: application/json');

require_once "config.php";
require_once "FindMyiPhone.php";

$location_data = array();

try {
  $find_iphone = new FindMyiPhone(APPLE_ID_USERNAME, APPLE_ID_PASSWORD);

  // get the device id for first device found
  $device_id = $find_iphone->devices[0]->id;
  $location_data = $find_iphone->locate_device(0, 30);
  file_put_contents("last.txt", json_encode($location_data), LOCK_EX);

} catch (exception $e) {
  http_response_code(503);
  echo json_encode($e->getMessage());
  return;
}

echo json_encode($location_data);