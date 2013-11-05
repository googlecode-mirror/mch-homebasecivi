<?php

/**
 * This class will be most of what we need for the Crew class from the design document.
 * It already has an ID and an array of persons, and generally represents a group of people working
 * together so it should be a good fir for conversion into the required Crew class. 
 * It will probably no longer need variables $venue, $sub_call_list, and possibly others.
 * @author Brian, Simon
 * @version Sept. 20, 2013
**/


/*
 * Copyright 2008 by Oliver Radwan, Maxwell Palmer, Nolan McNair,
 * Taylor Talmage, and Allen Tucker.  This program is part of RMH Homebase.
 * RMH Homebase is free software.  It comes with absolutely no warranty.
 * You can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */

/*
 * class Crew characterizes a group of volunteers working on a particular day
 * @version October 20, 2013
 * @author Allen Tucker 
 */

class Crew {

    private $id;            // "yy-mm-dd-group" is a unique key for this crew
    private $yy_mm_dd;      // "yy-mm-dd" date of this crew
    private $group;         // foodbank, foodpantry, or soupkitchen
    private $slots;     	// number of slots
    private $persons;       // array of ids followed by their names, eg "malcom1234567890+Malcom+Jones"
    private $sub_call_list; // SCL if sub call list exists, otherwise null
    private $notes;         // crew notes

    /*
     * construct a new crew with given persons and vacancies
     */
    function __construct($yy_mm_dd, $group, $slots, $persons, $sub_call_list, $notes) {
    	$this->yy_mm_dd = $yy_mm_dd;
        $this->group = $group;
        $this->id = $yy_mm_dd."-".$group;
        $this->slots = $slots;
        if ($persons=="")
        	$this->persons = array();
        else $this->persons = $persons;
        $this->sub_call_list = $sub_call_list;
        $this->notes = $notes;	
    }

    /*
     * @return the number of vacancies in this crew.
     */

    function remove_slot() {
        if ($this->slots > 0)
            --$this->slots;
    }

    function add_slot() {
        ++$this->slots;
    }

    function num_vacancies() {
        if (sizeof($this->persons)>0 && !$this->persons[0])
            array_shift($this->persons);
        return $this->slots - count($this->persons);
    }

    function has_sub_call_list() {
        if ($this->sub_call_list == "yes")
            return true;
        return false;
    }

    function open_sub_call_list() {
        $this->sub_call_list = "yes";
    }

    function close_sub_call_list() {
        $this->sub_call_list = "no";
    }

    /*
     * getters and setters
     */

    function get_group() {
        return $this->group;
    }
    function get_slots() {
        return $this->slots;
    }
    function get_date() {
        return $this->yy_mm_dd;
    }
	function get_persons() {
        return $this->persons;
    }
    function get_sub_call_list() {
        return $this->sub_call_list;
    }
    function get_id() {
        return $this->id;
    }
    function get_notes() {
        return $this->notes;
    }
    function set_notes($notes) {
        $this->notes = $notes;
    }
    function assign_persons($p) {
        $this->persons = $p;
    }
}

?>
