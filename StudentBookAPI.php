<?php 

/**
 * StudentBook API for PHP wrapper
 */
class StudentBookAPI {
	private $API_URL = "http://dev.mystudentbook.com/api2";
	private $token;

	public function __construct($token) {
		$this->token = $token;
	}

	/**
	 * Gets today lessons
	 */
	public function getTodayLessons() {
		$json = $this->fetchJson("/subjects?token=" . $this->token);
		$subjects = $json->subjects;

		// Find subjects for today
		$today = date("N", time());
		$lessons = array();
		foreach ($subjects as $subject) {
			foreach ($subject->lessons as $lesson) {
				if ($lesson->day == $today) {			
					$lesson->subject = $subject;			
					unset($lesson->subject->lessons);
					$lessons[] = $lesson;		
				}
			}
		}

		return $lessons;
	}

	/**
	 * Fetches JSON from URL
	 */
	private function fetchJson($path) {
		$url = $this->API_URL . $path;		
		$json = file_get_contents($url);
		$obj = json_decode($json);
		return $obj;
	}
}