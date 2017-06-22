<?php
header('Content-Type: application/json');

require_once "config.php";
require_once "FindMyiPhone.php";

$location_data = array();

try {
  $find_iphone = new FindMyiPhone(APPLE_ID_USERNAME, APPLE_ID_PASSWORD);

  $device_index = FALSE;

  foreach ($find_iphone->devices as $index => $device) {
    $deviceModel = strtolower($device->rawDeviceModel);
    if (strpos($deviceModel, 'iphone') !== FALSE) {
      $device_index = $index;
    }
  }

  if ($device_index !== FALSE) {
    $location_data = $find_iphone->locate_device($device_index, 30);
    file_put_contents("last.txt", json_encode($location_data), LOCK_EX);
  } else {
    http_response_code(503);
    echo json_encode(array("message" => "Could not find an iPhone to locate."));
    return;
  }

} catch (exception $e) {
  http_response_code(503);
  echo json_encode($e->getMessage());
  return;
}

echo json_encode($location_data);