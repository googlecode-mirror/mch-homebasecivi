<?php

/*
 * Copyright 2008 by Oliver Radwan, Maxwell Palmer, Nolan McNair,
 * Taylor Talmage, and Allen Tucker.  This program is part of RMH Homebase.
 * RMH Homebase is free software.  It comes with absolutely no warranty.
 * You can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */

/*
 * class Shift characterizes a time interval in a day
 * for scheduling volunteers
 * @version May 1, 2008, modified 9/15/08, 2/14/10
 * @author Allen Tucker and Maxwell Palmer
 */

class Shift {

    private $mm_dd_yy;      // String: "mm-dd-yy".
    private $name;          // String: 'ss-ee' or 'overnight', where ss = start time and ee = end time e.g., '9-12'
    private $start_time;    // Integer: e.g. 10 (meaning 10:00am)
    private $end_time;      // Integer: e.g. 13 (meaning 1:00pm)
    private $venue;         //  "weekly" or "monthly"
    private $vacancies;     // number of vacancies in this shift
    private $persons;       // array of person ids filling slots, followed by their name, ie "malcom1234567890+Malcom+Jones"
    private $sub_call_list; // SCL if sub call list exists, otherwise null
    private $day;         // string name of day "Monday"...
    private $id;            // "mm-dd-yy-ss-ee" is a unique key for this shift
    private $notes;  // notes written by the manager

    /*
     * construct an empty shift with a certain number of vacancies
     */

    function __construct($id, $venue, $vacancies, $persons, $sub_call_list, $notes) {
    	$this->mm_dd_yy = substr($id, 0, 8);
        $this->name = substr($id, 9);
        $i = strpos($this->name, "-");
        if ($i>0) {
        	$this->start_time = substr($this->name, 0, $i);
        	$this->end_time = substr($this->name, $i + 1, 2);
        }
        else {  // assuming an overnight shift
        	$this->start_time = 0;
        	$this->end_time = 1;
        }
        $this->venue = $venue;
        $this->vacancies = $vacancies;
        $this->persons = $persons;
        $this->sub_call_list = $sub_call_list;
        $this->day = date("D", mktime(0, 0, 0, substr($this->mm_dd_yy, 0, 2), substr($this->mm_dd_yy, 3, 2), "20" . substr($this->mm_dd_yy, 6, 2)));
        $this->id = $id;
        $this->notes = $notes;	
    }

    /**
     * This function (re)sets the start and end times for a shift
     * and corrects its $id accordingly
     * Precondition:  0 <= $st && $st < $et && $et < 24
     *          && the shift is not "chef" or "night"
     * Postcondition: $this->start_time == $st && $this->end_time == $et
     *          && $this->id == $this->mm_dd_yy .  "-"
     *          . $this->start_time . "-" . $this->end_time . $this->venue
     *          && $this->name == substr($this->id, 9)
     */
    function set_start_end_time($st, $et) {
        if (0 <= $st && $st < $et && $et < 24 &&
                strpos(substr($this->id, 9), "-") !== false) {
            $this->start_time = $st;
            $this->end_time = $et;
            $this->id = $this->mm_dd_yy . "-" . $this->start_time
                    . "-" . $this->end_time;
            $this->name = substr($this->id, 9);
            return $this;
        }
        else
            return false;
    }

    /*
     * @return the number of vacancies in this shift.
     */

    function num_vacancies() {
        return $this->vacancies;
    }

    function ignore_vacancy() {
        if ($this->vacancies > 0)
            --$this->vacancies;
    }

    function add_vacancy() {
        ++$this->vacancies;
    }

    function num_slots() {
        if (!$this->persons[0])
            array_shift($this->persons);
        return $this->vacancies + count($this->persons);
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

    function get_name() {
        return $this->name;
    }

    function get_start_time() {
        return $this->start_time;
    }

    function get_end_time() {
        return $this->end_time;
    }
    function get_time_of_day() {
        if ($this->start_time == 0)
            return "overnight";
        else if ($this->start_time <= 10)
            return "morning";
        else if ($this->start_time <= 13)
            return "earlypm";
        else if ($this->start_time <= 16)
            return "latepm";
        else 
            return "evening";
    }
    function get_date() {
        return $this->mm_dd_yy;
    }

    function get_venue() {
        return $this->venue;
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

    function get_day() {
        return $this->day;
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
