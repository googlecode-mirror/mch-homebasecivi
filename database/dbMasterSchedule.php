<?php

 /*
 * Copyright 2013 by Brian Jacobel, Oliver Fisher, Simon Brooks and Allen Tucker.
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).

 * Based on previous work by Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 */
/*
 * dbMasterSchedule module for RMH Homebase
 * @author Allen Tucker
 * September, 2013
 */

include_once(dirname(__FILE__) . '/../domain/MasterScheduleEntry.php');
include_once('dbinfo.php');

function create_dbMasterSchedule() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbMasterSchedule");
    $result = mysql_query("CREATE TABLE dbMasterSchedule (group TEXT NOT NULL, day TEXT NOT NULL, week_no TEXT NOT NULL,
							slots TEXT, persons TEXT, notes TEXT, id TEXT)");
    // id is a unique string for each entry: id = schedule_type.day.week_no.start_time."-".end_time and week_no == odd, even, 1st, 2nd, ... 5th
    if (!$result) {
        echo mysql_error() . " - Error creating dbMasterSchedule table.\n";
        return false;
    }
    // documentation for this table:
    $groups = array("foodbank", "foodpantry","soupkitchen");
    $days = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
    $weeks = array(1,2,3,4,5);
    mysql_close();
    return true;
}

function insert_dbMasterSchedule($entry) {
    if (!$entry instanceof MasterScheduleEntry) {
        return false;
    }
    connect();
    $result = mysql_query("SELECT * FROM dbMasterSchedule WHERE id = '" . $entry->get_id() . "'");
    if (mysql_num_rows($result) != 0) {
        delete_dbMasterSchedule($entry->get_id());
        connect();
    }
    $query = "INSERT INTO dbMasterSchedule VALUES ('" .
		$entry->get_group() . "','" .
		$entry->get_day() . "','" .
		$entry->get_week_no() . "','" .
		$entry->get_slots() . "','" .
		implode(',', $entry->get_persons()) . "','" .
		$entry->get_notes() . "','" .
		$entry->get_id() .
		"');";
    $result = mysql_query($query);
    if (!$result) {
        echo mysql_error() . " - Unable to insert in dbMasterSchedule: " . $entry->get_id() . "\n";
        mysql_close();
        return false;
    }
    mysql_close();
    return true;
}

function retrieve_dbMasterSchedule($id) {
	connect();
    $query = "SELECT * FROM dbMasterSchedule WHERE id LIKE '%" . $id . "%'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) != 1) {
    	mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $theEntry = new MasterScheduleEntry($result_row['group'], $result_row['day'], $result_row['week_no'],
                    $result_row['slots'], $result_row['persons'],$result_row['notes']);
    mysql_close();
    return $theEntry;
}

function update_dbMasterSchedule($entry) {
    connect();
    if (!$entry instanceof MasterScheduleEntry) {
        echo("Invalid argument for update_dbMasterSchedule function call");
        return false;
    }
    if (delete_dbMasterSchedule($entry->get_id()))
        return insert_dbMasterSchedule($entry);
    else {
        echo (mysql_error() . " - Unable to update dbMasterSchedule: " . $entry->get_id() . "\n");
        return false;
    }
    mysql_close();
    return true;
}

function delete_dbMasterSchedule($id) {
    connect();
    $query = "DELETE FROM dbMasterSchedule WHERE id = '" . $id . "'";
    $result = mysql_query($query);
    if (!$result) {
        echo (mysql_error() . " - Unable to delete from dbMasterSchedule: " . $id . "\n");
        return false;
    }
    mysql_close();
    return true;
}

function insert_nonoverlapping($shift) {
    $other_shifts = get_master_shifts($shift->get_group(), $shift->get_day(), $shift->get_week_no());

    foreach ($other_shifts as $other_shift) {
        if (masterslots_overlap($shift, $oher_shift))
            return false;
    }
    insert_dbMasterSchedule($shift);
    return true;
}

function masterslots_overlap($s1, $s2) {
    if ($s1->get_group() == $s2->get_group())
        if ($s1->get_day() == $s2->get_day() && $s1->get_week_no() == $s2->get_week_no())
        	return true;
    return false;
}

/*
 * @return all master schedule entries for a particular group and day
 * Each row in the array is a MasterScheduleEntry
 * If there are no entries, return an empty array
 */

function get_master_shifts($group, $day) {
    connect();
    //$outcome = array();
    $query = "SELECT * FROM dbMasterSchedule WHERE day = '" . $day . "' AND group = '" . $group . "'";
    $result = mysql_query($query);
    mysql_close();
    $outcome = array();
    if (mysql_num_rows($result) == 0)
        return $outcome;
    for ($i = 0; $i < mysql_num_rows($result); $i++)
    {
    	$result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    	// problem - something about this call is faulty - it does not seem to be going through
    	// to the constructor. 
        $testVar = new MasterScheduleEntry($result_row['group'], $result_row['day'], 
            $result_row['week_no'], $result_row['slots'], $result_row['persons'], $result_row['notes']); 
        $outcome[] = $testVar;
    }
    return $outcome;
}

/* schedule a person for a given group, day, and week_no
 * update that persons schedule in the dbPersons table
 *
 */

function schedule_person($group, $day, $week_no, $person_id) {
    connect();
    $query1 = "SELECT * FROM dbMasterSchedule WHERE id = '" .
            $group . $day . $week_no . "'";
    $query2 = "SELECT * FROM dbPersons WHERE id = '" . $person_id . "'";
    $result = mysql_query($query1);
    $resultp = mysql_query($query2);
    if (!$result || !$resultp)
        die("schedule_person could not query the database");
    // be sure the master shift and person both exist
    if (mysql_num_rows($result) !== 1 || mysql_num_rows($resultp) !== 1) {
        mysql_close();
        die("schedule_person couldnt retrieve 1 person and 1 dbScheduleEntry");
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    $resultp_row = mysql_fetch_array($resultp, MYSQL_ASSOC);
    $persons = explode(',', $result_row['persons']);    // get an array of scheduled person id's
    $schedule = explode(',', $resultp_row['schedule']); // get an array of person's scheduled times
    $availability = explode(',', $resultp_row['availability']);     // and their availabiltiy
    if (!in_array($person_id, $persons) && !in_array($group . $day . $time, $schedule)) {
        $persons[] = $person_id;             // add the person to the array, and
        $schedule[] = $group . $day . $week_no ; // add the time to the person's schedule
        $result_row['persons'] = implode(',', $persons);     // and update one row in each table
        $resultp_row['schedule'] = implode(',', $schedule);  // in the database
        mysql_query("UPDATE dbMasterSchedule SET persons = '" . $result_row['persons'] .
                "' WHERE id = '" . $group . $day . $week_no . "'");
        mysql_query("UPDATE dbPersons SET schedule = '" . $resultp_row['schedule'] .
                "' WHERE id = '" . $person_id . "'");
        mysql_close();
        return "";
    }
    mysql_close();
    return "Error: can't schedule a person not available or already scheduled";
}

/* unschedule a volunteer from a group, day, and week_no
 * update that person's schedule in the dbPersons table
 *
 */

function unschedule_person($group, $day, $week_no, $person_id) {
    connect();
    $query = "SELECT * FROM dbMasterSchedule WHERE id = '" .
            $group . $day . $week_no . "'";
    $queryp = "SELECT * FROM dbPersons WHERE id = '" . $person_id . "'";
    $result = mysql_query($query);
    $resultp = mysql_query($queryp);
    // be sure the person exists and is scheduled
    if (!$result || mysql_num_rows($result) !== 1) {
        mysql_close();
        die("Error: group-day-week_no not valid");
    } else if (!$resultp || mysql_num_rows($resultp) !== 1) {
        $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
        $persons = explode(',', $result_row['persons']);    // get an array of scheduled person id's
        if (in_array($person_id, $persons)) {
            $index = array_search($person_id, $persons);
            array_splice($persons, $index, 1);               // remove the person from the array, and
            $result_row['persons'] = implode(',', $persons); // and update one row in the schedule
            mysql_query("UPDATE dbMasterSchedule SET persons = '" . $result_row['persons'] .
                    "' WHERE id = '" . $group . $day . $week_no . "'");
        }
        mysql_close();
        die("Error: person not in database" . $person_id);
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    $resultp_row = mysql_fetch_array($resultp, MYSQL_ASSOC);
    $persons = explode(',', $result_row['persons']);    // get an array of scheduled person id's
    $schedule = explode(',', $resultp_row['schedule']); // get an array of person's scheduled times
    if (in_array($person_id, $persons)) {
        $index = array_search($person_id, $persons);
        $indexp = array_search($group . $day . $week_no, $schedule);
        array_splice($persons, $index, 1);   // remove the person from the array, and
        if (in_array($group . $day . $week_no, $schedule))       	
        	array_splice($schedule, $indexp, 1); // remove the time from the person's schedule
        $result_row['persons'] = implode(',', $persons);     // and update one row in each table
        $resultp_row['schedule'] = implode(',', $schedule);  // in the database
        mysql_query("UPDATE dbMasterSchedule SET persons = '" . $result_row['persons'] .
                "' WHERE id = '" . $group . $day . $week_no . "'");
        mysql_query("UPDATE dbPersons SET schedule = '" . $resultp_row['schedule'] .
                "' WHERE id = '" . $person_id . "'");
        mysql_close();
        return "";
    }
    mysql_close();
    die("Error: can't unschedule a person not scheduled");
}

/* insert a note in the schedule for a given group, day, and week_no.
 *
 */

function make_notes($group, $day, $week_no, $notes) {
    connect();
    $query = "SELECT * FROM dbMasterSchedule WHERE group = '" .
            $group . "' AND week_no = '" .
            $week_no . "' AND day = '" .
            $day . "'";
    $result = mysql_query($query);
    if (!$result)
        die("make_notes could not query the database");
    // be sure the person exists and is scheduled
    if (mysql_num_rows($result) !== 1) {
        mysql_close();
        return "Error: group-day-week_no not valid";
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    $result_row['notes'] = $notes;
    mysql_query("UPDATE dbMasterSchedule SET notes = '" . $result_row['notes'] . "' WHERE group = '" .
            $group . "' AND day = '" .
            $day . "' AND week_no = '" . $week_no . "'");
    mysql_close();
    return "";
}

/*
 * @return whether or not a person is scheduled in a given group, day, and week_no
 *
 */

function is_scheduled($group, $day, $week_no, $person_id) {
    connect();
    $query = "SELECT * FROM dbMasterSchedule WHERE group = '" .
            $group . "' AND day = '" .
            $day . "' AND week_no = '" . $week_no . "'";
    $result = mysql_query($query);
    if (!$result)
        die("is_scheduled could not query the database");
    if (mysql_num_rows($result) !== 1) {
        mysql_close();
        return "Error: group-day-week_no not valid";
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    $persons = explode(',', $result_row['persons']);    // get array of scheduled person id's
    mysql_close();
    if (in_array($person_id, $persons))
        return true;
    else
        return false;
}

/*
 * @return all persons scheduled for a particular group, day, and week_no
 * as an array of associative arrays.  Each associative array has
 * entries indexed by the field names of a person in dbPersons.
 */

function get_persons($group, $day, $week_no) {
    connect();
    $query1 = "SELECT * FROM dbMasterSchedule WHERE id = '" .
            $group . $day . $week_no . "'";
    $result = mysql_query($query1);
    if (!$result)
        die("get_persons could not query the database");
    $out = array();
    if (mysql_num_rows($result) !== 1) {
        mysql_close();
        $out[] = "Error: group-day-week_no not valid";
        return $out;
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    $person_ids = explode(',', $result_row['persons']);    // get an array of scheduled person id's
    foreach ($person_ids as $person_id)
        if ($person_id != "") {
            $query2 = "SELECT * FROM dbPersons WHERE id = '" . $person_id . "'";
            $resultp = mysql_query($query2);
            if (!$resultp)
                die("get_persons could not query the database");
            if (mysql_num_rows($resultp) !== 1) {
                mysql_close();
                $out[] = $person_id;
                return $out;
            }
            $out[] = mysql_fetch_array($resultp, MYSQL_ASSOC);
        }
    mysql_close();
    return $out;
}

/*
 * @return ids of all persons scheduled for a particular group, day, and week_no
 */

function get_person_ids($group, $day, $week_no) {
    connect();
    $query1 = "SELECT * FROM dbMasterSchedule WHERE id = '" .
            $group . $day . $week_no . "'";
    $result = mysql_query($query1);
    if (!$result)
        die("get_person_ids could not query the database");
    if (mysql_num_rows($result) !== 1) {
        mysql_close();
        return array("Error: group-day-week_no not valid");
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    $person_ids = explode(',', $result_row['persons']);
    mysql_close();
    return $person_ids;
}

/*
 * @return number of slots for a particular group, day, and week_no
 * this is fixed with a kluge.
 */

function get_total_slots($group, $day, $week_no) {
    connect();
    $query1 = "SELECT * FROM dbMasterSchedule WHERE id = '" .
            $group . $day . $week_no . "'";
    $result = mysql_query($query1);
    if (!$result)
        die("get_total_slots could not query the database");
    if (mysql_num_rows($result) !== 1) {
        mysql_close();
        return "Error: group-day-time not valid";
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    return $result_row['slots'];
}

/*
 * @return number of vacancies for a particular group, day, and week_no
 */

function get_total_vacancies($group, $day, $week_no) {
    $slots = get_total_slots($group, $day, $week_no);
    $persons = count(get_persons($group, $day, $week_no));
    return $slots - $persons;
}

/*
 * @return number of vacancies for a particular group, day, and week_no
 */

function check_valid_schedule($group, $day, $week_no) {
    connect();
    $query1 = "SELECT * FROM dbMasterSchedule WHERE id = '" .
            $schedule_type . $day . $week_no . "-" . $time . "'";
    $result = mysql_query($query1);
    mysql_close();
    if (!$result)
        die("check_valid_schedule could not query the database");
    if (mysql_num_rows($result) !== 1) {
        return false;
    }
    return true;
}

/*
 * @return number of vacancies for a particular group, day, and week_no
 */

function edit_schedule_vacancy($group, $day, $week_no, $change) {
    connect();
    $query1 = "SELECT * FROM dbMasterSchedule WHERE id = '" .
            $group . $day . $week_no . "'";
    $result = mysql_query($query1);
    if (!$result)
        die("edit_schedule_vacancy could not query the database");
    if (mysql_num_rows($result) !== 1) {
        mysql_close();
        return false;
    }
    $result_row = mysql_fetch_array($result, MYSQL_ASSOC);
    $result_row['slots'] = $result_row['slots'] + $change;
    // id = schedule_type.day.week_no.start_time."-".end_time
    mysql_query("UPDATE dbMasterSchedule SET slots = '" . $result_row['slots'] .
            "' WHERE id = '" . $group . $day . $week_no . "'");
    mysql_close();
    return true;
}

?>