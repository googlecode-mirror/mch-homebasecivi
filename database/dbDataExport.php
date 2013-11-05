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
 * dbDataExport module for RMH Homebase
 * @author Johnny Coster
 * @version February 27, 2012
 */
include_once('../domain/DataExport.php');
include_once('dbinfo.php');

function create_dbDataExport() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbDataExport");
    $result = mysql_query("CREATE TABLE dbDataExport (export_date TEXT NOT NULL, first_name TEXT NOT NULL,
    					  last_name TEXT NOT NULL, gender TEXT,
						  type TEXT, notes TEXT, address TEXT, city TEXT, state TEXT, zip TEXT,
						  county TEXT, phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), email TEXT, employer TEXT,
						  status TEXT, hours_worked TEXT, day_of_week TEXT, month TEXT, start_date TEXT, id TEXT)");
    if (!$result) {
        echo mysql_error() . " - Error creating dbDataExport.\n";
        return false;
    }
    mysql_close();
    return true;
}

function insert_dbDataExport($data) {
    if (!$data instanceOf DataExport) {
        return false;
    }
    connect();
    $result = mysql_query("SELECT * FROM dbDataExport WHERE id = '" . $data->get_id() . "'");
    if (mysql_num_rows($result) != 0) {
        delete_dbDataExport($data->get_id());
        connect();
    }

    $query = "INSERT INTO dbDataExport VALUES ('" .
            $data->get_export_date() . "','" .
            $data->get_first_name() . "','" .
            $data->get_last_name() . "','" .
            $data->get_gender() . "','" .
            $data->get_type() . "','" .
            $data->get_notes() . "','" .
            $data->get_address() . "','" .
            $data->get_city() . "','" .
            $data->get_state() . "','" .
            $data->get_zip() . "','" .
            $data->get_county() . "','" .
            $data->get_phone1() . "','" .
            $data->get_phone2() . "','" .
            $data->get_email() . "','" .
            $data->get_employer() . "','" .
            $data->get_status() . "','" .
            $data->get_hours_worked() . "','" .
            $data->get_day_of_week() . "','" .
            $data->get_month() . "','" .
            $data->get_start_date() . "','" .
            $data->get_id() .
            "');";
    $result = mysql_query($query);
    if (!$result) {
        echo mysql_error() . " - Unable to insert in dbDataExport: " . $data->get_id() . "\n";
        mysql_close();
        return false;
    }
    mysql_close();
    return true;
}

function retrieve_dbDataExport($id) {
    connect();
    $result = mysql_query("SELECT * FROM dbDataExport WHERE id = '" . $id . "'");
    if (mysql_num_rows($result) != 1) {
        mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $theData = new DataExport($result_row['export_date'], $result_row['first_name'], $result_row['last_name'], $result_row['gender'],
                    $result_row['type'], $result_row['notes'], $result_row['address'],
                    $result_row['city'], $result_row['state'], $result_row['zip'],
                    $result_row['county'], $result_row['phone1'], $result_row['phone2'],
                    $result_row['email'], $result_row['employer'], $result_row['status'],
                    $result_row['hours_worked'], $result_row['day_of_week'],
                    $result_row['month'], $result_row['start_date']);
    mysql_close();
    return $theData;
}

function update_dbDataExport($data) {
    connect();
    if (!$data instanceOf DataExport) {
        echo "Invalid argument for update_dbDataExport function call";
        return false;
    }
    if (delete_dbDataExport($data->get_id())) {
        return insert_dbDataExport($data);
    } else {
        echo mysql_error() . " - Unable to update dbDataExport: " . $data->get_id() . "\n";
        return false;
    }
    mysql_close();
    return true;
}

function delete_dbDataExport($id) {
    connect();
    $result = mysql_query("DELETE FROM dbDataExport WHERE id = '" . $id . "'");
    if (!$result) {
        echo mysql_error() . " - Unable to delete from dbDataExport: " . $id . "\n";
        return false;
    }
    mysql_close();
    return true;
}

?>