<?php

class checkDay {

	public function lastf_month($year, $month) {
		$day = 0;
		while(true) {
			$last_day = mktime(0, 0, 0, $month+1, $day, $year); 
			if (date("w", $last_day) == 5) {
				return date("Y-m-d", $last_day);
			}
			$day -= 1;
		}
	}

}

