<?php

namespace View;

class DateTimeView {


	public function show() {
		date_default_timezone_set("Europe/Stockholm");
		$day = date("l");
		$clock = date("h:i:s");
		$theDayOfMonth = date("j");
		$month = date("F");
		$suffixForTheDay = date("S");
		$year = date("o");
		
		$timeString =  $day .", the " . $theDayOfMonth . $suffixForTheDay . " of " . $month . " " . $year . ", The time is " . $clock;

		return '<p class="dateTime">' . $timeString . '</p>';
	}
}