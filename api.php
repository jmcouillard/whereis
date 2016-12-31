<?php
header('Content-Type: application/json');

require_once "config.php";
require_once "FindMyiPhone.php";

$location_data = array();

try {
  $find_iphone = new FindMyiPhone(APPLE_ID_USERNAME, APPLE_ID_PASSWORD);
  $device_id = $find_iphone->devices[0]->id;
  $location_data = $find_iphone->locate_device(0, 3);
  file_put_contents("last.txt", json_encode($location_data), LOCK_EX);

} catch (exception $e) {
  http_response_code(503);
  echo json_encode($e->getMessage());
  return;
}

echo json_encode($location_data);