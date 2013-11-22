<?php
/*
 * Copyright 2012 by Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */
session_start();
session_cache_expire(30);
?>
<html>
    <head>
        <title>
            Search for People
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            <div id="content">
                <?PHP
                // display the search form
                echo('<form method="post">');
                echo('<p><strong>Search for volunteers:</strong>');

                echo('<p>Type:<select name="s_type">' .
                '<option value="" SELECTED></option>' .
                '<option value="volunteer">Volunteer</option>' . '<option value="staff">Staff</option>' .
                '</select>');
                echo('&nbsp;&nbsp;Status:<select name="s_status">' .
                '<option value="" SELECTED></option>' . '<option value="applicant">Applicant</option>' . '<option value="active">Active</option>' .
                '<option value="LOA">On Leave</option>' . '<option value="former">Former</option>' .
                '</select>');
                echo '<p>Name (type a few letters): ';
                echo '<input type="text" name="s_name">';

                echo '<fieldset>
						<legend>Availability: </legend>
							<table><tr>
								<td>Day (of week)</td>
								<td>Week of Month</td>
								</tr>';
                echo "<tr>";
                echo "<td>";
                $days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                echo '<select name="s_day">' . '<option value=""></option>';
                foreach ($days as $day) {
                    echo '<option value="' . $day . '">' . $day . '</option>';
                }
                echo '</select>';
                echo "</td><td>";
                $weeks = array(1 => "First Week", 2 => "Second Week", 3 => "Third Week", 4 => "Fourth Week", 5 => "Fifth Week");
                echo '<select name="s_week">' . '<option value=""></option>';
                foreach ($weeks as $weekno => $weekname) {
                    echo '<option value="' . $weekno . '">' . $weekname . '</option>';
                }
                echo '</select>';
                echo "</td>";
                echo "</tr>";
                echo '</table></fieldset>';

                echo('<p><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search">');
                echo('</form></p>');

                // if user hit "Search"  button, query the database and display the results
                if (@$_POST['s_submitted']) {
                    $type = $_POST['s_type'];
                    $status = $_POST['s_status'];
                    $name = trim(str_replace('\'', '&#39;', htmlentities($_POST['s_name'])));
                    $day = $_POST['s_day'];
                    $week = $_POST['s_week'];

                    // now go after the volunteers that fit the search criteria
                    include_once('database/dbPersons.php');
                    include_once('domain/Person.php');

                    $result = getonlythose_dbPersons($type, $status, $name, $day, $week);
                    echo '<p><strong>Search Results:</strong> <p>Found ' . sizeof($result) . ' ' . $status . ' ';
                    if ($type != "")
                        echo $type . "s";
                    else
                        echo "persons";
                    if ($name != "")
                        echo ' with name like "' . $name . '"';
                    $availability = $_POST['s_day'] ." ". $_POST['s_week'];
                    if ($availability != " ") {
                        echo " with availability " . $availability;
                    }
                    if (sizeof($result) > 0) {
                        echo ' (select one for more info).';
                        echo '<p><table> <tr><td>Name</td><td>Phone</td><td>E-mail</td><td>Availability</td></tr>';
                        foreach ($result as $vol) {
                            echo "<tr><td><a href=personEdit.php?id=" . str_replace(" ","_",$vol->get_id()) . ">" .
                            $vol->get_first_name() . " " . $vol->get_last_name() . "</td><td>" .
                            phone_edit($vol->get_phone1()) . "</td><td>" .
                            $vol->get_email() . "</td><td>";
                            foreach ($vol->get_availability() as $availableon) {
                                echo ($availableon . ", ");
                            }
                            echo "</td></a></tr>";
                        }
                    }
                    echo '</table>';
                }
                ?>
                <!-- below is the footer that we're using currently-->
                </div>
        </div>
        <?PHP include('footer.inc'); ?>
    </body>
</html>

