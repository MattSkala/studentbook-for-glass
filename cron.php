<?php
require_once "StudentBookAPI.php";

/**
 * Cron file that is launched every 5 minutes.
 */ 

// TODO: get token for current user
$token = "2e057765633f536a7abb03560dc6a12ebc2bc096";
$api = new StudentBookAPI($token);
$lessons = $api->getTodayLessons();

var_dump($lessons);

// Update timeline
// TODO