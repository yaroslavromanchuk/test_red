<?php

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

//$analytics = initializeAnalytics();
//$profile = getFirstProfileId($analytics);
//$results = getResults($analytics, $profile);
//printResults($results);

function initializeAnalytics()
{
  // Creates and returns the Analytics Reporting service object.

  // Use the developers console and download your service account
  // credentials in JSON format. Place them in this directory or
  // change the key file location if necessary.
  $KEY_FILE_LOCATION = __DIR__ . '/My Project-6d145705ebc1.json';

  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("Hello Analytics Reporting");
  $client->setAuthConfig($KEY_FILE_LOCATION);
  $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
  $analytics = new Google_Service_Analytics($client);

  return $analytics;
}



