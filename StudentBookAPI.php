<?php 

/**
 * StudentBook API for PHP wrapper
 */
class StudentBookAPI {
	/**
	 * What time before lesson start the card appears as active (minutes)
	 */
	public static $LESSON_OFFSET = 10;
	public static $API_URL = "http://dev.mystudentbook.com/api2";
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
	 * Finds current lesson from today lessons array.
	 * @return Current lesson position or false if not found
	 */
	public function getCurrentLesson($lessons) {
		$currentPosition = false;
		$currentLesson = false;
		$now = time();

		foreach ($lessons as $k => $lesson) {
			$from = strtotime($lesson->duration_from) - self::$LESSON_OFFSET * 60;
			$to = strtotime($lesson->duration_to) - self::$LESSON_OFFSET * 60;

			if ($now >= $from && $now <= $to) {
				$currentPosition = $k;
				$currentLesson = $lesson;
				break;
			}
		}

		return Array($currentPosition, $currentLesson);
	}
	
	/**
	 * Fetches JSON from URL
	 */
	private function fetchJson($path) {
		$url = self::$API_URL . $path;		
		$json = file_get_contents($url);
		$obj = json_decode($json);
		return $obj;
	}
}