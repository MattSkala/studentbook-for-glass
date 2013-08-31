<?php
require_once "StudentBookAPI.php";
require_once "util.php";

/**
 * Cron file that is launched every 5 minutes.
 */ 

// TODO: foreach logged users
$token = "2e057765633f536a7abb03560dc6a12ebc2bc096";
$api = new StudentBookAPI($token);
$lessons = $api->getTodayLessons();

//var_dump($lessons);

// Update timeline
update_lessons_card($lessons);

var_dump($lessons);

