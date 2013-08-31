<?php

class MirrorClient{
	protected $service;

	public function __construct($credentials){
		
		$client = get_google_api_client();
  		
  		if($credentials){
  			$client->setAccessToken($credentials["credentials"]);
  			$this->service = new Google_MirrorService($client);
  		}else{
  			return false;
  		}
	}

	public function insertCard(TimeLineItem $item){
		  try {
		    $opt_params = array();
		    if ($content_type != null && $attachment != null) {
		      $opt_params['data'] = $attachment;
		      $opt_params['mimeType'] = $content_type;
		    }
		    return $service->timeline->insert($timeline_item, $opt_params);
		  } catch (Exception $e) {
		    print 'An error ocurred: ' . $e->getMessage();
		    return null;
		  }
	}

	public function getBundle($bundleId){
		return $this->service->timeline->listTimeline(Array("bundleId"=>$bundleId));
	}

	public function pushSchedule($today, $lessons){
		$mirror_service = &$this->service;

  foreach ($lessons as $lesson) {
    $timeline_item = new Google_TimelineItem();
    $html = file_get_contents('docs/template_timetable_day.html');
    $html = str_replace('{$subject}', $lesson->subject->name, $html);

    // Fix color to be visible on Glass
    $color = $lesson->subject->color;
    if ($color == '#000000') {
      $color = '#FFFFFF';
    }

    $html = str_replace('{$color}', $lesson->subject->color, $html);
    $html = str_replace('{$teacher}', $lesson->subject->teacher->name, $html);
    $html = str_replace('{$classroom}', $lesson->classroom, $html);
    $html = str_replace('{$duration}', $lesson->duration_from . " – " . $lesson->duration_to, $html);
    $timeline_item->setHtml($html);

    // Bundle ID
    $timeline_item->setBundleId($today);

    // Menu
    $menu = array();
    $pinItem = new Google_MenuItem();
    $pinItem->setAction('TOGGLE_PINNED');
    array_push($menu, $pinItem);
    $timeline_item->setMenuItems($menu);

    insert_timeline_item($mirror_service, $timeline_item, null, null);
  }
	}


  public function updateItem($id, TimeLineItem $new){
    $this->service->timeline->update($id, $new);
  }
}

?>