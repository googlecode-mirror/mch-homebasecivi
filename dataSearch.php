<?php
/*
 * Copyright 2012 by Johnny Coster, Jackson Moniaga, Judy Yang, and
 * Allen Tucker.  This program is part of RMH Homebase. RMH Homebase
 * is free software.  It comes with absolutely no warranty. You can
 * redistribute it and/or modify it under the terms of the GNU General
 * Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */
/*
 * dataSearch page for RMH homebase.
 * @author Johnny Coster
 * @version April 2, 2012
 */
session_start();
session_cache_expire(30);
?>
<html>
    <head>
        <title>
            Search for data objects
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
    </head>
    <body>
        <div id="container">
            <?php include_once('header.php'); ?>
            <div id="content">
                <?php
                include_once('domain/Person.php');
                include_once('database/dbPersons.php');

                if ($_POST['_form_submit'] != 1 && $_POST['_form_submit'] != 2 && $_POST['_form_submit'] != 3)
                    include('dataSearch.inc.php'); // the form has not been submitted, so show it

                process_form();
                include('footer.inc');

                function process_form() {
                    if ($_POST['check1'] == 'on')
                        $first_name = $_POST['first_name']; else
                        $first_name = '';
                    if ($_POST['check2'] == 'on')
                        $last_name = $_POST['last_name']; else
                        $last_name = '';
                    if ($_POST['check3'] == 'on')
                        $gender = $_POST['gender']; else
                        $gender = '';
                    if ($_POST['check4'] == 'on')
                        $type = $_POST['type'][0]; else
                        $type = '';
                    if ($_POST['check5'] == 'on')
                        $status = $_POST['status']; else
                        $status = '';
                    if ($_POST['check6'] == 'on')
                        $start_date = $_POST['start_date']; else
                        $start_date = '';
                    if ($_POST['check7'] == 'on')
                        $hours_worked = $_POST['hours_worked']; else
                        $hours_worked = '';
                    if ($_POST['check8'] == 'on')
                        $day_of_the_week = $_POST['day_of_the_week']; else
                        $day_of_the_week = '';
                    if ($_POST['check9'] == 'on')
                        $month = $_POST['month']; else
                        $month = '';
                    if ($_POST['check10'] == 'on')
                        $employer_school = $_POST['employer_school']; else
                        $employer_school = '';
                    if ($_POST['check11'] == 'on')
                        $street = $_POST['street']; else
                        $street = '';
                    if ($_POST['check12'] == 'on')
                        $city = $_POST['city']; else
                        $city = '';
                    if ($_POST['check13'] == 'on')
                        $county = $_POST['county']; else
                        $county = '';
                    if ($_POST['check14'] == 'on')
                        $state = $_POST['state']; else
                        $state = '';
                    if ($_POST['check15'] == 'on')
                        $zip = $_POST['zip']; else
                        $zip = '';
                    if ($_POST['check16'] == 'on')
                        $phone1 = $_POST['phone1']; else
                        $phone1 = '';
                    if ($_POST['check17'] == 'on')
                        $phone2 = $_POST['phone2']; else
                        $phone2 = '';
                    if ($_POST['check18'] == 'on')
                        $email = $_POST['email']; else
                        $email = '';
                    if ($_POST['check19'] == 'on')
                        $notes = $_POST['notes']; else
                        $notes = '';

                    $attribute_array = array(1 => array(1 => $_POST['check1'], 'First Name', $first_name), array(1 => $_POST['check2'], 'Last Name', $last_name),
                        array(1 => $_POST['check3'], 'Gender', $gender), array(1 => $_POST['check4'], 'Type', $type), array(1 => $_POST['check5'], 'Status', $status),
                        array(1 => $_POST['check6'], 'Start Date', $start_date), array(1 => $_POST['check7'], 'Hours Worked', $hours_worked),
                        array(1 => $_POST['check8'], 'Day of the Week', $day_of_the_week), array(1 => $_POST['check9'], 'Month', $month),
                        array(1 => $_POST['check10'], 'Employer/School', $employer_school), array(1 => $_POST['check11'], 'Street Address', $street),
                        array(1 => $_POST['check12'], 'City', $city), array(1 => $_POST['check13'], 'County', $county),
                        array(1 => $_POST['check14'], 'State', $state), array(1 => $_POST['check15'], 'Zip', $zip),
                        array(1 => $_POST['check16'], 'Phone 1', $phone1), array(1 => $_POST['check17'], 'Phone 2', $phone2),
                        array(1 => $_POST['check18'], 'Email', $email), array(1 => $_POST['check19'], 'Notes', $notes));

                    $export_attribute_array = array(1 => array(1 => $_POST['e_check1'], 'First Name', 'get_first_name'), array(1 => $_POST['e_check2'], 'Last Name', 'get_last_name'),
                        array(1 => $_POST['e_check3'], 'Gender', 'get_gender'), array(1 => $_POST['e_check4'], 'Type', 'get_type'), array(1 => $_POST['e_check5'], 'Status', 'get_status'),
                        array(1 => $_POST['e_check6'], 'Start Date', 'get_start_date'), array(1 => $_POST['e_check7'], 'Hours Worked', 'get_hours_worked'),
                        array(1 => $_POST['e_check8'], 'Day of the Week', 'get_day_of_the_week'), array(1 => $_POST['e_check9'], 'Month', 'get_month'),
                        array(1 => $_POST['e_check10'], 'Employer/School', 'get_employer_school'), array(1 => $_POST['e_check11'], 'Address', 'get_address'),
                        array(1 => $_POST['e_check12'], 'City', 'get_city'), array(1 => $_POST['e_check13'], 'County', 'get_county'),
                        array(1 => $_POST['e_check14'], 'State', 'get_state'), array(1 => $_POST['e_check15'], 'Zip', 'get_zip'),
                        array(1 => $_POST['e_check16'], 'Phone 1', 'get_phone1'), array(1 => $_POST['e_check17'], 'Phone 2', 'get_phone2'),
                        array(1 => $_POST['e_check18'], 'Email', 'get_email'), array(1 => $_POST['e_check19'], 'Notes', 'get_notes'));
                    //$value_array = array(1 => $first_name, $last_name, $gender, $type, $status, $start_date, $hours_worked,
                    //							  $day_of_the_week, $month, $employer_school, $street, $city,
                    //							  $county, $state, $zip, $phone1, $phone2, $email, $notes);


                    if ($_POST['_form_submit'] == 1) {
                        $returned_people = get_people_for_export($first_name, $last_name, $gender, $type, $status, $start_date, $street, $city, $county, $state, $zip, $phone1, $phon2, $email, $notes);
                        include('dataResults.inc.php');
                    } else if ($_POST['_form_submit'] == 2) {
                        $_SESSION['results'] = $_POST['results_list'];
                        if ($_POST['b_export']) {
                            $select_people_array = array();
                            if ($_POST['results_list']) 
                              foreach ($_POST['results_list'] as $export_person) {
                                $temp_dude = retrieve_person($export_person);
                                $select_people_array[] = $temp_dude->get_first_name() . " " . $temp_dude->get_last_name();
                              }
                            include('dataExport.inc.php');
                        }
                    } else if ($_POST['_form_submit'] == 3) {
                        $search_attr = "Based on: ";
                        foreach ($_SESSION['checked'] as $check_num)
                            $search_attr .= $attribute_array[$check_num][2] . ", ";
                        $search_attr = substr($search_attr, 0, -2);
                        $attr_to_export = array();
                        foreach ($export_attribute_array as $exp_att) {
                            if ($exp_att[1] == 'on')
                                $attr_to_export[] = array($exp_att[2], $exp_att[3]);
                        }
                        $export_data = array();
                        for ($i = 0; $i < count($_SESSION['results']); $i++) {
                            $data_row = array($i + 1);
                            for ($j = 0; $j < count($attr_to_export); $j++) {
                                $data_row[] = retrieve_person($_SESSION['results'][$i])->$attr_to_export[$j][1]();
                                if ($attr_to_export[$j][0] == 'Type')
                                    $data_row[$j + 1] = $data_row[$j + 1][0];
                            }
                            $export_data[] = $data_row;
                        }
                        $attribute_row = array("");
                        foreach ($attr_to_export as $attr)
                            $attribute_row[] = $attr[0];
                        date_default_timezone_set('America/New_York');
                        $current_time = array("Export date: " . date("F j, Y, g:i a"));
                        export_data($current_time, array($search_attr), $attribute_row, $export_data);
                        echo "Data have been exported: browse to dataexport.csv to retrieve the Excel file.";
              //          header("File name: dataexport.csv");
                    }
                }

                function export_data($ct, $sa, $ar, $ed) {
                    $filename = "dataexport.csv";
                    $handle = fopen($filename, "w");
                    fputcsv($handle, $ct);
                    fputcsv($handle, $sa);
                    fputcsv($handle, str_replace(' ', '_', $ar), ' ', ' ');
                    foreach ($ed as $person_data) {
                        fputcsv($handle, str_replace(' ', '_', $person_data), ' ', ' ');
                    }
                    fclose($handle);
                }
                ?>
            </div>
        </div>
    </body>
</html>