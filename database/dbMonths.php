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
 * dbMonths module for MCH homebase.
 * @author Allen Tucker
 * @version October 20, 2013
 */
include_once(dirname(__FILE__) . '/dbinfo.php');
include_once(dirname(__FILE__) . '/../domain/Month.php');
include_once(dirname(__FILE__) . '/../domain/Crew.php');
include_once(dirname(__FILE__) . '/dbCrews.php');
include_once(dirname(__FILE__) . '/dbMasterSchedule.php');
include_once(dirname(__FILE__) . '/dbPersons.php');
include_once(dirname(__FILE__) . '/../domain/MasterScheduleEntry.php');

function create_dbMonths() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbMonths");
    $result = mysql_query("CREATE TABLE dbMonths (id TEXT NOT NULL, crews TEXT, `group` TEXT, status TEXT, end_of_month_timestamp INT)");

    if (!$result) {
        echo mysql_error() . "Error creating dbMonths table<br>";
        echo false;
    }
    mysql_close();
    return true;
}

/*
 * add a month to dbMonth: if already there, return false
 */

function insert_dbMonths($month) {
    if (!$month instanceof Month) {
        return false;
    }
    connect();

    $query = "SELECT * FROM dbMonths WHERE id = '" . $month->get_id() . "'";
    $result = mysql_query($query);


    //if there's no entry for this id, add it
    $query = "INSERT INTO dbMonths VALUES ('" .
            $month->get_id() . "','" .
            implode(',', $month->get_crews()) . "','" .
            $month->get_group() . "','" .
            $month->get_status() . "','" .
            $month->get_end_of_month_timestamp() .
            "');";


    $result = mysql_query($query);

    if (!$result) {
        echo (mysql_error() . " unable to insert into dbMonths: " . $month->get_id() . "\n");
        mysql_close();
        return false;
    }
    mysql_close();
    return true;
}

/*
 * @return a single row from dbMonths table matching a particular id.
 * if not in table, make a new month on the fly aand return it
 */

function retrieve_dbMonths($id) {
    connect();
    $query = 'SELECT * FROM dbMonths WHERE id = "' . $id . '"';
    $result = mysql_query($query);
    // can't find month with id
    if (mysql_num_rows($result) != 1) {
        mysql_close();
        return newMonth($id);
    }
    $result_row = mysql_fetch_assoc($result);
    $theMonth = new Month($result_row['id'], $result_row['status']);
    return $theMonth;
}

/*
 * @return all rows from dbMonths table for the given group, ordered by date
 * if none there, return false
 */

function getall_dbMonths($group) {
    connect();
    $query = "SELECT * FROM dbMonths WHERE `group` = '".$group."' ORDER BY end_of_month_timestamp";
    $result = mysql_query($query);
    $theMonths = array();
    for ($i=0; $i<sizeof($result); $i++) {
    	$result_row = mysql_fetch_assoc($result);
    	$theMonth = new Month($result_row['id'], $result_row['status']);
        $theMonths[] = $theMonth;
    }
    return $theMonths;
}

/*
 * update month with matching id with the values of this month's fields
 * if month with id is not in db, return false
 */

function update_dbMonths($month) {
    if (!$month instanceof Month) {
        echo ("Invalid argument for update_dbMonths function call");
        return false;
    }

    if (delete_dbMonths($month))
        return insert_dbMonths($month);
    else {
        echo (mysql_error() . "unable to update dbMonths table: " . $month->get_id());
        return false;
    }
}

/*
 * remove a month from dbMonths table and its crews from the dbCrews table
 */

function delete_dbMonths($month) {
    connect();
    $query = "DELETE FROM dbMonths WHERE id=\"" . $month->get_id() . "\"";
    $result = mysql_query($query);
    mysql_close();
    if (!$result) {
        echo (mysql_error() . " unable to delete from dbMonths: " . $month->get_id());
        return false;
    }
    foreach ($month->get_crews() as $crew_id) {
    	delete_dbCrews($crew_id);
    }
    return true;
}
// generate a new month for a group of crews from the master schedule
// $id = yy-mm-group
function newMonth ($id) {
	$days = array (1=>"Mon", 2=>"Tue", 3=>"Wed", 4=>"Thu", 5=>"Fri", 6=>"Sat", 7=>"Sun");

	// We switched new months to default to published, because otherwise they won't be available for viewing.
    // We're unsure if this was the right move to make.
    $new_month = new Month($id, "unpublished");
	$new_crews = $new_month->get_crews();

	$dom = 1;			// day of the month, 1, 2, ..., 31
	$week_no = 1;		// master schedule week number

	$firstdow = $dow = date("N", mktime(0,0,0,substr($id,3,2), "01", substr($id,0,2)));  // day of week, 1 = Monday
	$newbies = array();
	foreach ($new_crews as $new_crew) {
		$id1 = substr($id,6).$days[$dow].$week_no;
		$schedule_entry = retrieve_dbMasterSchedule($id1);
		if ($schedule_entry  && $schedule_entry->get_slots()>0) {	
			if ($dom<10) $dd = "-0".$dom; else $dd = "-".$dom;
			$person_ids = $schedule_entry->get_persons();
			$crew_names = array();
			foreach ($person_ids as $person_id) {
				if ($person_id=="") continue;
				$p = retrieve_person($person_id);
				if ($p)
					$crew_names[] = $person_id . "+" . $p->get_first_name() . "+" . 
									$p->get_last_name() . "+(" . implode(' ',$p->get_role()) . ")";
				else $crew_names[] = $person_id . "+++";			
			}
			$newbie = new Crew(substr($id,0,5).$dd, substr($id,6),
					$schedule_entry -> get_slots(),
					$crew_names,"","");
			$new_month->set_crew($dom, $newbie->get_id());
			$newbies[] = $newbie;;		
		}	
        if ($dow==$firstdow-1) 
			$week_no++;	
        if ($dow==7)
			$dow = 1;	
        else $dow++;
		$dom++;
	}
	update_dbMonths($new_month);
	foreach ($newbies as $newbie)
		update_dbCrews($newbie);
	return $new_month;
}

?>