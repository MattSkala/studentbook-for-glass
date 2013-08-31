<?php
require_once "StudentBookAPI.php";
require_once "util.php";
require_once "msb-mirror-client.php";

@session_start();

/**
 * Cron file that is launched every 5 minutes.
 */ 


// TODO: foreach logged users
$token = "2e057765633f536a7abb03560dc6a12ebc2bc096";
$api = new StudentBookAPI($token);
$lessons = $api->getTodayLessons();

//var_dump($lessons);

// Update timeline
//update_lessons_card($lessons);

$client = new MirrorClient(get_credentials($_SESSION["userid"]));
$today = Date("N", time());
$items = $client->getBundle($today)->getItems();
if(count($items)==0){
	$client->pushSchedule($today, $lessons);
}else{
	$currentLesson = $api->getCurrentLesson($lessons);
	if($currentLesson[0]!==false){
		$current = $api->getCurrentLesson($lessons);
		if(isset($items[$current[0]])){
			print_r($current);
			print_r($items);
			$items[$current[0]]->isBundleCover = true;
			$client->updateItem($items[$current[0]]->id, $items[$current[0]]);

		}
	}
}

