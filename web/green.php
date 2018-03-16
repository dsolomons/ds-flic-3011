<?php
// ****************************************************************************************************
// SFMC API Looper - Looping PHP API Trigger Script
// Purpose: To allow copy and paste of Postman API calls and create loops for execution during demo
//
// Author: Martin Andrew <martin.andrew@salesforce.com>
// v1.0 - Initial release for REST API (can be adapted for SOAP as needed)
//
// TODO
// - add total execution timer and compare against auth "expiresIn" and re-auth if required
//
// NOTE: Use Generate Code link in Postman and select PHP cURL  
//
// ****************************************************************************************************

// *****************************
// ***** VARIABLES - START *****
// *****************************

// set variable for loop timing and optional iteration count

$loop_timer = 5;      // time in seconds to wait between loops
$max_loop_count = 1;  // max loop count, optional, 0 = unlimited
$exit = FALSE;        // initialise loop exit flag (used for counter)

// *****************************
// ****** VARIABLES - END ******
// *****************************

// convert seconds to microseconds
$loop_timer = $loop_timer * 1000000;

echo "SFMC API Looping Trigger Script\n";
echo " - Authenticating with API\n";

// ****************************************************************************************************
// INSERT AUTH CODE HERE - START
// ****************************************************************************************************

$curl = curl_init();
print_r($_REQUEST);

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://auth.exacttargetapis.com/v1/requestToken",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n    \"clientId\":\"7ohd5lr10h8p8rvpdx6174lc\",\n    \"clientSecret\":\"MwwZBJll4poa4oBHApT0lkDw\"\n}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 74A121BA-45D9-4B16-8419-E2D95E2A90E9"

    
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

// ****************************************************************************************************
// INSERT AUTH CODE HERE - END
// ****************************************************************************************************

$data = json_decode($response , true);
$accessToken = $data['accessToken'];

if ($max_loop_count > 0) {
  $counter = 0;
  echo "\n - Looping " . $max_loop_count . " times with wait time of " . $loop_timer . " microseconds\n";
} else {
  echo "\n - Looping indefinitely with wait time of " . $loop_timer . " microseconds\n";
}

while ($exit != TRUE) {

// ****************************************************************************************************
// INSERT API CODE HERE - START
// ****************************************************************************************************

$message = ($_REQUEST['message'] ? $_REQUEST['message'] : "message");
$title = ($_REQUEST['title'] ? $_REQUEST['title'] : "title");
$subtitle = ($_REQUEST['subtitle'] ? $_REQUEST['subtitle'] : "subtitle");
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.exacttargetapis.com/push/v1/messageContact/MTE6MTE0OjA/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"SubscriberKeys\": [\n    \"0036A00000Pe4nYQAR\"\n  ],\n  \"Override\": true,\n  \"MessageText\": \"$message\",\n  \"title\": \"title\",\n  \"subtitle\": \"$subtitle\",\n  \"Badge\": \"+1\"\n}",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer $accessToken",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 9b9ff3f4-ca2f-4b85-b7f7-71819ec9eacc"
    
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
echo 'HTTP code: ' . $httpcode . "</br>";

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}


// ****************************************************************************************************
// INSERT API CODE HERE - END
// ****************************************************************************************************

if ($max_loop_count > 0) {
  $counter++;
  if ($counter == $max_loop_count) {
    // loop count reached, exit
    echo " - Loop count (" . $max_loop_count . ") reached, exiting\n";
    exit();
  }
}

// sleep/wait for $loop_timer microseconds before continuing
echo " - Sleeping for " . $loop_timer . " microseconds\n";
usleep($loop_timer);

}

?>
