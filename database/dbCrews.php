<?php

/*
 * Copyright 2008 by Oliver Radwan, Maxwell Palmer, Nolan McNair,
 * Taylor Talmage, and Allen Tucker.  This program is part of RMH Homebase.
 * RMH Homebase is free software.  It comes with absolutely no warranty.
 * You can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */

/**
 * Functions to create, update, and retrieve information from the
 * dbCrews table in the database.  This table is used with the Crew
 * class.  Crews are generated using the master schedule (through the
 * addWeek.php form), and retrieved by the calendar form and editCrew.
 * @version May 1, 2008
 * @author Maxwell Palmer
 */
include_once('domain/Crew.php');
include_once('dbPersons.php');
include_once('dbDates.php');
include_once('dbinfo.php');

/**
 * Drops the dbCrews table if it exists, and creates a new one
 * Table fields:
 * 0 id: "yy-mm-dd-group" is a unique key for this crew
 * 1 yy-mm-dd date of this crew
 * 2 group = foodbank, foodpantry, or soupkitchen
 * 3 slots: # of slots for this crew
 * 4 persons: list of ids, followed by their names, eg "max1234567890+Max+Palmer"
 * 5 sub_call_list: yes/no if crew has SCL
 * 6 notes: crew notes
 */
function create_dbCrews() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbCrews");
    $result = mysql_query("CREATE TABLE dbCrews (id CHAR(23) NOT NULL, " .
            "yy_mm_dd TEXT, group TEXT, slots INT, " .
            "persons TEXT, sub_call_list TEXT, notes TEXT, PRIMARY KEY (id))");
    if (!$result) {
        echo mysql_error();
        return false;
    }
    mysql_close();
    return true;
}

/**
 * Inserts a crew into the db
 * @param $s the crew to insert
 */
function insert_dbCrews($s) {
    if (!$s instanceof Crew) {
        die("Invalid argument for insert_dbCrews function call" . $s);
    }
    connect();
    $query = 'SELECT * FROM dbCrews WHERE id ="' . $s->get_id() . '"';
    $result = mysql_query($query);
    if (mysql_num_rows($result) != 0) {
        delete_dbCrews($s->get_id());
        connect();
    }
    $query = "INSERT INTO dbCrews VALUES (\"" . $s->get_id() . "\",\"" .
            $s->get_date() . "\",\"" . $s->get_group() . "\"," .
            $s->get_slots() . ",\"" . implode(",", $s->get_persons()) . "\",\"" . 
            $s->get_sub_call_list() . "\",\"" . $s->get_notes() . "\")";
    $result = mysql_query($query);
    if (!$result) {
        echo "unable to insert into dbCrews " . $s->get_id() . mysql_error();
        mysql_close();
        die();
    }
    mysql_close();
    return true;
}

/**
 * Deletes a crew from the db
 * @param $id is the id of the crew to delete
 */
function delete_dbCrews($id) {
    connect();
    $query = "DELETE FROM dbCrews WHERE id=\"" . $id . "\"";
    $result = mysql_query($query);
    if (!$result) {
        echo "unable to delete from dbCrews " . $id . mysql_error();
        mysql_close();
        return false;
    }
    mysql_close();
    return true;
}

/**
 * Updates a crew in the db by deleting it (if it exists) and then replacing it
 * @param $s the crew to update
 */
function update_dbCrews($s) {
    if (!$s instanceof Crew)
        die("Invalid argument for dbCrews->replace_crew function call");
    delete_dbCrews($s->get_id());
    insert_dbCrews($s);
    return true;
}

/**
 * Selects a crew from the database
 * @param $id a crew id
 * @return Crew or null
 */
function select_dbCrews($id) {
    connect();
    $s = null;
    $query = "SELECT * FROM dbCrews WHERE id =\"" . $id . "\"";
    $result = mysql_query($query);
    mysql_close();
    if (!$result) {
        echo 'Could not run query2: ' . mysql_error();
    } else {
        $result_row = mysql_fetch_row($result);
        if ($result_row != null) {
        	$persons = array();
        	if ($result_row[4]!="")
            	$persons = explode("*", $result_row[4]);
        	$s = new Crew($result_row[1], $result_row[2], $result_row[3], $persons, null, $result_row[6]);
        }
    }
    return $s;
}

/**
 * Selects all crews from the database for a given date and group
 * @return a result set or false (if there are no crews for that date and venue)
 */
function selectDateGroup_dbCrews($date, $group) {
    connect();
    $query = "SELECT * FROM dbCrews WHERE yy_mm_dd = " . $date . " AND group LIKE '%" . $group . "%'";
    $result = mysql_query($query);
    mysql_close();
    return $result;
}

/**
 * Returns an array of $ids for all crews scheduled for the person having $person_id
 */
function selectScheduled_dbCrews($person_id) {
    connect();
    $crew_ids = mysql_query("SELECT id FROM dbCrews WHERE persons LIKE '%" . $person_id . "%' ORDER BY id");
    $crews = array();
    if ($crew_ids) {
        while ($thisRow = mysql_fetch_array($crew_ids, MYSQL_ASSOC)) {
            $crews[] = $thisRow['id'];
        }
    }
    mysql_close();
    return $crews;
}

/**
 * Returns the month, day, year, or group of a crew from its id
 */
function get_crew_month($id) {
    return substr($id, 3, 2);
}

function get_crew_day($id) {
    return substr($id, 6, 2);
}

function get_crew_year($id) {
    return substr($id, 0, 2);
}

?>
