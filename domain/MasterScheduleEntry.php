<?php
/*
* Copyright 2012 by Johnny Coster, Jackson Moniaga, Judy Yang, and
* Allen Tucker.  This program is part of RMH Homebase. RMH Homebase
* is free software.  It comes with absolutely no warranty. You can
* redistribute it and/or modify it under the terms of the GNU General
* Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/ for more information).
*/

/**
 * MasterScheduleEntry class for RMH homebase.
 * @author Johnny Coster
 * @version February 15, 2012, revised April 11, 2012
 */

class MasterScheduleEntry {
	private $group; // "foodbank", "foodpantry", or "soupkitchen"
	private $day;           // "Mon", "Tue", ... "Sun"
	private $week_no;       // week of month 1 - 5
	private $slots;         // the number of slots to be filled for this shift
	private $persons;       // array of ids, eg ["alex2071234567", "jane1112345567"]
	private $notes;         // notes to be displayed for this shift on the schedule
	private $id;	        // unique string for each entry = group.day.week_no   

	/**
	* constructor for all MasterScheduleEntries
	*/
	function __construct($group, $day, $week_no, $slots, $persons, $notes){
		$this->group = $group;
		$this->day = $day;
		$this->week_no = $week_no;
		$this->slots = $slots;
		if ($persons !== "")
			$this->persons = explode(',',$persons);
		else
			$this->persons = array();
		$this->notes = $notes;
		$this->id = $group.$day.$week_no;
	}
	
	/**
	*  getter functions
	*/
	
	function get_group(){
		return $this->group;
	}
	function get_day(){
		return $this->day;
	}
	function get_week_no(){
		return $this->week_no;
	}
	function get_slots(){
		return $this->slots;
	}
	function get_persons(){
		return $this->persons;
	}
	function get_notes(){
		return $this->notes; 
	}
	function get_id(){
		return $this->id;
	}
	
	function set_notes($notes){
		$this->notes = $notes; 
	}
	
	
}

?>