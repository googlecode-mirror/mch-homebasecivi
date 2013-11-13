<?php

/*
 * Copyright 2012 by Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */

/**
 * @version March 1, 2012
 * @author Oliver Radwan and Allen Tucker
 */
include_once('dbinfo.php');
include_once('domain/Person.php');

function create_dbPersons() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbPersons");
    $result = mysql_query("CREATE TABLE dbPersons(id TEXT NOT NULL, first_name TEXT NOT NULL, last_name TEXT, " .
            "    address TEXT, city TEXT, state VARCHAR(2), zip TEXT, phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), " .
            "    email TEXT, ".
            "    type TEXT, group TEXT, role TEXT, status TEXT, " .
            "    availability TEXT, schedule TEXT, " .
            "    birthday TEXT, start_date TEXT, notes TEXT, password TEXT)");
    if (!$result)
        echo mysql_error() . "Error creating dbPersons table<br>";
}

/*
 * add a person to dbPersons table: if already there, return false
 */

function add_person($person) {
    if (!$person instanceof Person)
        die("Error: add_person type mismatch");
    connect();
    $query = "SELECT * FROM dbPersons WHERE id = '" . $person->get_id() . "'";
    $result = mysql_query($query);
    //if there's no entry for this id, add it
    if ($result == null || mysql_num_rows($result) == 0) {
        $query_string = 'INSERT INTO dbPersons VALUES("' .
                $person->get_id() . '","' .
                $person->get_first_name() . '","' .
                $person->get_last_name() . '","' .
                $person->get_address() . '","' .
                $person->get_city() . '","' .
                $person->get_state() . '","' .
                $person->get_zip() . '","' .
                $person->get_phone1() . '","' .
                $person->get_phone2() . '","' .
                $person->get_email() . '","' .
                $person->get_type() . '","' .
                implode(',', $person->get_group()) . '","' .
                implode(',', $person->get_role()) . '","' .
                $person->get_status() . '","' .
                implode(',', $person->get_availability()) . '","' .
                implode(',', $person->get_schedule()) . '","' .
                $person->get_birthday() . '","' .
                $person->get_start_date() . '","' .
                $person->get_notes() . '","' .
                $person->get_password() .
                '");';
        $query = mysql_query($query_string);
        mysql_close();
        return true;
    }
    mysql_close();
    return false;
}

/*
 * remove a person from dbPersons table.  If already there, return false
 */

function remove_person($id) {
    connect();
    $query = 'SELECT * FROM dbPersons WHERE id = "' . $id . '"';
    $result = mysql_query($query);
    if ($result == null || mysql_num_rows($result) == 0) {
        mysql_close();
        return false;
    }
    $query = 'DELETE FROM dbPersons WHERE id = "' . $id . '"';
    $result = mysql_query($query);
    mysql_close();
    return true;
}

/*
 * @return a Person from dbPersons table matching a particular id.
 * if not in table, return false
 */

function retrieve_person($id) {
    connect();
    $query = "SELECT * FROM dbPersons WHERE id = '" . $id . "'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) !== 1) {
        mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    // var_dump($result_row);
    $thePerson = make_a_person($result_row);
//    mysql_close();
    return $thePerson;
}

function change_password($id, $newPass) {
    connect();
    $query = 'UPDATE dbPersons SET password = "' . $newPass . '" WHERE id = "' . $id . '"';
    $result = mysql_query($query);
    mysql_close();
    return $result;
}


/*
 * @return all rows from dbPersons table ordered by last name
 * if none there, return false
 */

function getall_dbPersons() {
    connect();
    $query = "SELECT * FROM dbPersons ORDER BY last_name,first_name";
    $result = mysql_query($query);
    if ($result == null || mysql_num_rows($result) == 0) {
        mysql_close();
        return false;
    }
    $result = mysql_query($query);
    $thePersons = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $thePerson = make_a_person($result_row);
        $thePersons[] = $thePerson;
    }
    mysql_close();
    return $thePersons;
}

function make_a_person($result_row) {
    $thePerson = new Person(
                    $result_row['first_name'],
                    $result_row['last_name'],
                    $result_row['address'],
                    $result_row['city'],
                    $result_row['state'],
                    $result_row['zip'],
                    $result_row['phone1'],
                    $result_row['phone2'],
                    $result_row['email'],
                    $result_row['type'],
                    $result_row['group'],
                    $result_row['role'],
                    $result_row['status'],
                    $result_row['availability'],
                    $result_row['schedule'],
                    $result_row['birthday'],
                    $result_row['start_date'],
                    $result_row['notes'],
                    $result_row['password']);
    return $thePerson;
}

function getall_names($status, $type) {
    connect();
    $result = mysql_query("SELECT id,first_name,last_name,type FROM dbPersons " .
            "WHERE status = '" . $status . "' AND TYPE LIKE '%" . $type . "%' ORDER BY last_name,first_name");
    mysql_close();
    return $result;
}

/*
 * @return all active people of type $t or subs from dbPersons table ordered by last name
 */

function getall_type($t) {
    connect();
    $query = "SELECT * FROM dbPersons WHERE (type LIKE '%" . $t . "%' OR type LIKE '%sub%') AND status = 'active'  ORDER BY last_name,first_name";
    $result = mysql_query($query);
    if ($result == null || mysql_num_rows($result) == 0) {
        mysql_close();
        return false;
    }
    mysql_close;
    return $result;
}

/*
 * @return all active people in group $group of type $t or subs from dbPersons table ordered by last name
 */

function getall_typegroup($t, $group) {
    connect();
    $query = "SELECT * FROM dbPersons WHERE (type LIKE '%" . $t . "%' OR type LIKE '%sub%') AND group LIKE '%" . $group . "%' AND status = 'active'  ORDER BY last_name,first_name";
    $result = mysql_query($query);
    if ($result == null || mysql_num_rows($result) == 0) {
        mysql_close();
        return false;
    }
    mysql_close;
    return $result;
}

/*
 *   get all active volunteers and subs of $type who are available for the given $day and $week
 */

function getall_available($type, $day, $week) {
    connect();
    $query = "SELECT * FROM dbPersons WHERE (type LIKE '%" . $type . "%' OR type LIKE '%sub%')" .
            " AND availability LIKE '%" . $day .":". $week .
            "%' AND status = 'active' ORDER BY last_name,first_name";
    $result = mysql_query($query);
    mysql_close();
    return $result;
}
/*
 *   get all active volunteers and subs of $type who are available for the given $day and $group
 */

function getall_availablegroup($type, $day, $group) {
    connect();
    $query = "SELECT * FROM dbPersons WHERE (type LIKE '%" . $type . "%' OR type LIKE '%sub%')" .
            " AND availability LIKE '%" . $day ."%'  AND group LIKE '%" . $group . 
            "%'AND status = 'active' ORDER BY last_name,first_name";
    $result = mysql_query($query);
    mysql_close();
    return $result;
}

// retrieve only those persons that match the criteria given in the arguments
function getonlythose_dbPersons($type, $status, $name, $day, $week) {
    connect();
    if ($type=="manager")
        {$string1 = " = '"; $string2 = "'";}
    else {$string1 = " LIKE '%"; $string2 = "%'";}
    if ($day=="" || $week == "") $avail = "";
    	else $avail = $day . ":" . $week;
    $query = "SELECT * FROM dbPersons WHERE type ".$string1. $type . $string2 .
            " AND status LIKE '%" . $status . "%'" .
            " AND (first_name LIKE '%" . $name . "%' OR last_name LIKE'%" . $name . "%')" .
            " AND availability LIKE '%" . $avail . "%'" .
            " ORDER BY last_name,first_name";
    $result = mysql_query($query);
    $thePersons = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $thePerson = make_a_person($result_row);
        $thePersons[] = $thePerson;
    }
//    mysql_close();
    return $thePersons;
}

function phone_edit($phone) {
    if ($phone!="")
		return substr($phone, 0, 3) . "-" . substr($phone, 3, 3) . "-" . substr($phone, 6);
	else return "";
}

function get_people_for_export($first_name, $last_name, $gender, $type, $status, $start_date, $street, $city, $county, $state, $zip, $phone1, $phone2, $email, $notes) {
    connect();
    //hours_worked, day_of_the_week, month, employer_school...
    $query = "SELECT * FROM dbPersons WHERE first_name LIKE '%" . $first_name . "%' AND last_name LIKE '%" .
            $last_name . "%' AND type LIKE '%" . $type . "%' AND status LIKE '%" .
            $status . "%' AND start_date LIKE '%" . $start_date . "%' AND address LIKE '%" . $street . "%' AND city LIKE '%" .
            $city . "%' AND state LIKE '%" . $state . "%' AND zip LIKE '%" . $zip .
            "%' AND phone1 LIKE '%" . $phone1 . "%' AND phone2 LIKE '%" . $phone2 . "%' AND email LIKE '%" . $email .
            "%' AND notes LIKE '%" . $notes . "%' ORDER BY last_name,first_name";
    $result = mysql_query($query);
    $thePersons = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $thePerson = make_a_person($result_row);
        $thePersons[] = $thePerson;
    }
//    mysql_close();
    return $thePersons;
}

?>
