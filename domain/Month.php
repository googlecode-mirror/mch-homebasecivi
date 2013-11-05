<?php

/**
 * This class will be most of what is called for by the design document's Month class.
 * It already contains an ID, and information about the month. It will need to contain a variable
 * to represent the crews that are working that month, and at what times.
 * It will not need the $group variable, as MCHPP does not use alternating weeks.
 * @author Brian, Simon
 * @version Sept. 20, 2013
**/


/*
 * Copyright 2012 by Johnny Coster, Jackson Moniaga, Judy Yang, and
 * Allen Tucker.  This program is part of RMH Homebase. RMH Homebase
 * is free software.  It comes with absolutely no warranty. You can
 * redistribute it and/or modify it under the terms of the GNU General
 * Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */

/**
 * Month class for MCH Homebase.
 * @author Allen Tucker
 * @version October 20, 2013
 */
class Month {

    private $id; // yy-mm-group
    private $crews; // array of 31 (or less) crew ids, with ids mm-dd-yy-group 
    private $group; // foodbank, foodpantry, or soupkitchen
    private $status; // "unpublished", "published" or "archived"
    private $end_of_month_timestamp; // the mktime timestamp of the last day

    /**
     * constructor for all months
     */
    function __construct($id, $s) {
        $this->id = $id;
        $month = substr($this->id, 3, 2); // get the month
        $year = substr($this->id, 0, 2); // get the year
        $num_days = date("t", mktime(0, 0, 0, $month, 1, "20".$year)); // get number days in $month

        $this->crews = array();
        for ($i = 1; $i <= 9; $i++) {
            $this->crews[] = $year . "-" . $month . "-0" . $i . "-" . substr($id, 6);
        }
        for ($i = 10; $i <= $num_days; $i++) {
            $this->crews[] = $year . "-" . $month . "-" . $i  . "-" . substr($id, 6);
        }
        $this->group = substr($id, 6);
        $this->status = $s;
        $this->end_of_month_timestamp = mktime(0, 0, 0, $month, $num_days, $year); // get last day in $month
    }

    function get_id() {
        return $this->id;
    }

    function get_month_number(){
    	$eti = explode("-", $this->id);
        return intval($eti[1]);

    }

    function get_crews() {
        return $this->crews;
    }

    function get_group() {
        return $this->group;
    }

    function get_status() {
        return $this->status;
    }

    function get_end_of_month_timestamp() {
        return $this->end_of_month_timestamp;
    }

    function set_end_of_month_timestamp($ts) {
        $this->end_of_month_timestamp = $ts;
    }

    function set_group($g) {
        $this->group = $g;
    }

    function set_status($s) {
        $this->status = $s;
    }
    function set_crew($i, $crew) {
    	$this->crews[$i] = $crew;
    }

    function get_name(){
    	$eti = explode("-", $this->id);
        $year = 2000 + intval($eti[0]);
        $month = intval($eti[1]);
        return date("F", mktime(0, 0, 0, $month, 1, "20".$year));
    }

    function get_dates() {
    	$eti = explode("-", $this->id);
        $year = 2000 + intval($eti[0]);
        $month = intval($eti[1]);

        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = array();
        for ($i = 1; $i <= $num; $i++){
            array_push($dates, $i);
        }
        return $dates;
    }
    

}

?>