<?php

/**
 * This class will be most of what we need for the Person class spec'ed out in the design document.
 * Most of the variables are the same. Only small tweaks to this file will be needed.
 * @author Brian, Simon
 * @version Sept. 20, 2013
**/


/**
 * This class relies on some functionality provided by the DBZipCode class/table that we removed. 
 * We plan on querying an API for this information - it's much simpler and more reliable. 
 * We will write this functionality into this class soon.
 * (Good idea -- meanwhile, I removed the reliance on dbZipCodes so that AllTests and the app itself will run.  Allen)
 * @author Brian, Oliver
 * @version Sept. 27, 2013
**/

/*
 * Copyright 2008 by Oliver Radwan, Maxwell Palmer, Nolan McNair,
 * Taylor Talmage, and Allen Tucker.  This program is part of RMH Homebase.
 * RMH Homebase is free software.  It comes with absolutely no warranty.
 * You can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */

/**
 * Person class for RMH homebase.
 * @author Oliver Radwan, Judy Yang and Allen Tucker
 * @version May 1, 2008, modified 2/15/2012
 */

class Person {
    private $person_id; // id (unique key) = first_name . phone1
    private $first_name; // first name as a string
    private $last_name; // last name as a string
    private $address; // local address - string
    private $city; // city - string
    private $state; // state - string
    private $zip; // zip code - integer
    private $phone1; // primary phone (may be a cell)
    private $phone2; // alternate phone (may be a cell)
    private $email; // email address as a string
    private $type; // "volunteer" or "staff"
    private $group; // array of "soupkitchen", "foodpantry", "foodbank", "other"
    private $role; // array of roles e.g., [c;d;p]
    private $status; // "active", "on-leave", or "former"
    private $availability; // array of day:week-of-month pairs; e.g. [“Mon:1”, “Thu:4”]
    private $schedule; // array of dates actually worked e.g., [“09-19-13”,”09-16-13”]
    private $birthday; // format: yy-mm-dd
    private $start_date; // format: yy-mm-dd
    private $notes; // notes about this person
    private $password; // password for system access

    /**
     * constructor for all persons
     */

    // constructior takes firstname, lastname, address, city, state, phone1, phone2, email, type, group, role, status, availability, schedule, birthday, start_date, notes, password
    function __construct($f, $l, $a, $c, $s, $z, $p1, $p2, $e, $t, $g, $r, $st, $av, $sch, $bd, $sd, $notes, $pass) {
        $this->id = $f . $p1;
        $this->first_name = $f;
        $this->last_name = $l;
        $this->address = $a;
        $this->city = $c;
        $this->state = $s;
        $this->zip = $z;
        
        $this->phone1 = $p1;
        $this->phone2 = $p2;
        $this->email = $e;
        $this->type = $t;
        
        // turn "availability", "schedule", group, and role from a comma-separated string into an array (or empty array)
        if ($av == "")
            $this->availability = array();
        else
            $this->availability = explode(',', $av);
        if ($sch !== "")
            $this->schedule = explode(',', $sch);
        else
            $this->schedule = array();
        if ($g == "")
            $this->group = array();
        else
            $this->group = explode(',', $g);
        if ($r !== "")
            $this->role = explode(';', $r);
        else
            $this->role = array();
        $this->status = $st;
        $this->birthday = $bd;
        $this->start_date = $sd;
        $this->notes = $notes;

        // MD5 hash the password (oooh, secure)
        if ($pass == "")
            $this->password = md5($this->id);
        else
            $this->password = $pass;  // default password == md5($id)

    }

    function get_id() {
        return $this->id;
    }

    function get_first_name() {
        return $this->first_name;
    }

    function get_last_name() {
        return $this->last_name;
    }

    function get_address() {
        return $this->address;
    }

    function get_city() {
        return $this->city;
    }

    function get_state() {
        return $this->state;
    }

    function get_zip() {
        return $this->zip;
    }

    function get_phone1() {
        return $this->phone1;
    }

    function get_phone2() {
        return $this->phone2;
    }

    function get_email() {
        return $this->email;
    }

    /**
     * @return type of person, either "volunteer" or "staff"
     */
    function get_type() {
        return $this->type;
    }

    function get_group() {
        return $this->group;
    }

    function get_role() {
        return $this->role;
    }

    function get_status() {
        return $this->status;
    }

    function get_availability() {
        return $this->availability;
    }

    function get_schedule() {
        return $this->schedule;
    }

    function get_birthday() {
        return $this->birthday;
    }

    function get_start_date() {
        return $this->start_date;
    }

    function get_notes() {
        return $this->notes;
    }

    function get_password() {
        return $this->password;
    }
}

?>
