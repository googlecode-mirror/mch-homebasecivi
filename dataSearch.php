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
                include_once('domain/Crew.php');
                include_once('database/dbCrews.php');
                include_once('database/dbMonths.php');
                $returned_people = array();
                $search_attr = "Selection Criteria:  ";
                if ($_POST['_form_submit'] == 2) {
                        echo "Data have been exported. CTRL-click the following link and select 'Download' to download the CSV file.";
                        echo "<br/><br/><a href='dataexport.csv'>dataexport.csv</a>";
              //          header("File name: dataexport.csv");
                }
                else if ($_POST['_form_submit'] == 1) {
                    if ($_POST['check1'] == 'on')
                        $first_name = $_POST['first_name']; else
                        $first_name = '';
                    if ($_POST['check2'] == 'on')
                        $last_name = $_POST['last_name']; else
                        $last_name = '';
                    if ($_POST['check3'] == 'on')
                        $type = $_POST['type']; else
                        $type = '';
                    if ($_POST['check4'] == 'on')
                        $status = $_POST['status']; else
                        $status = '';
                    if ($_POST['check5'] == 'on')
                        $group = $_POST['xgroup']; else
                        $group = '';
                    if ($_POST['check6'] == 'on')
                        $role = $_POST['xrole']; else
                        $role = '';
                    if ($_POST['check7'] == 'on')
                        $month = $_POST['month']; else
                        $month = '';
                    
                    $attribute_array = array(
                        array(1 => $_POST['check1'], 'First Name', $first_name),
                        array(1 => $_POST['check2'], 'Last Name', $last_name),
                        array(1 => $_POST['check3'], 'Type', $type),
                        array(1 => $_POST['check4'], 'Status', $status),
                        array(1 => $_POST['check5'], 'Group', $group),
                        array(1 => $_POST['check6'], 'Role', $role),
                        array(1 => $_POST['check7'], 'Month', $month));
                        
                    
                            $returned_people = get_people_for_export($first_name, $last_name, $type, $status, $group, $role);
                            $returned_shifts = get_crews_for_export($returned_people,$month,$group);
                            $current_time = array("Export date: " . date("m/d/Y g:ia"));
                            for ($i = 0; $i <= count($attribute_array); $i++) {
                                if ($attribute_array[$i][1] == 'on')
                                    $search_attr .= $attribute_array[$i][2] . "=".$attribute_array[$i][3]. ", ";
                            }
                            $search_attr = substr($search_attr, 0, -2);
                            $data_to_export = array();
                            foreach ($returned_people as $p) {
                                $data_row = array($p->get_id(), $p->get_first_name(),$p->get_last_name(),$p->get_address(),
                                    $p->get_city(), $p->get_state(),$p->get_zip(),$p->get_phone1(),
                                    $p->get_phone2(), $p->get_email(),$p->get_type(),$p->get_address(),
                                    implode(',', $p->get_group()), implode(',', $p->get_role()),
                                    $p->get_status(), $p->get_birthday(), $p->get_start_date(), $p->get_notes()
                                );
                                $data_to_export[] = $data_row;
                            }
                            foreach ($returned_shifts as $returned_shift) {
                                $data_to_export[] = $returned_shift;
                            }
                            export_data($current_time, array($search_attr), $data_to_export);
                        include('dataResults.inc.php');
                } 
                else {
                    $archived_months = getall_archived_dbMonth_ids();
                    $unique_months = array();
                    foreach ($archived_months as $archived_month)
                        if (!in_array(substr($archived_month,0,5),$unique_months))
                            $unique_months[] = substr($archived_month,0,5);
                    include('dataSearch.inc.php'); // the form has not been submitted, so show it
                }
                               
                function export_data($ct, $sa, $ed) {
                    $filename = "dataexport.csv";
                    $handle = fopen($filename, "w");
                    fputcsv($handle, $ct);
                    fputcsv($handle, $sa);
                    foreach ($ed as $person_data) {
                        fputcsv($handle, $person_data, ',', '"');
                    }
                    fclose($handle);
                }
                
                
                ?>
            </div>
            <?php   include('footer.inc');  ?>
            
        </div>
    </body>
</html>