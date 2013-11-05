<?php
/*
 * Copyright 2012 by Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).

  @author Judy Yang and Jackson Moniaga
 */
session_start();
session_cache_expire(30);
?>
<html>
<head>
    <title>Calendar viewing</title>
    <link rel="stylesheet" href="styles.css" type="text/css" />
    <link rel="stylesheet" href="calendar.css?v=2" type="text/css" />
</head>
<body>
    <div id="container">
        <?PHP include('header.php'); ?>
        <div id="content">
            <?PHP

                if ($_SESSION['type'] == "staff" || $_SESSION['type'] == "volunteer") {
                    include_once('database/dbMonths.php');
                    include_once('database/dbCrews.php');
                    include_once('database/dbPersons.php');
                    include_once('database/dbLog.php');
                    include_once 'calendar.inc';

                    // checks to see if in edit mode
                    $edit = $_GET['edit'];
                    if ($edit != "true")
                        $edit = false;
                    else
                        $edit = true;

                    // gets the week to show, if no week then defaults to current week
                    $group = $_GET['group'];
                    $monthid = $_GET['month'];

                    if (!$monthid)
                        $monthid = date("y-m", time())."-".$group;
                    else
                        $monthid = $monthid."-".$group;

                    $month = retrieve_dbMonths($monthid); // get the month

                    // if invalid month or unpublished week and not a manager
                    if (!$month instanceof Month || $month->get_status() == "unpublished" && $_SESSION['access_level'] < 2) {
                        echo 'This month\'s calendar is not available for viewing. ';
                        if ($_SESSION['access_level'] >= 2)
                            echo ('<a href="addMonth.php?archive=false"> <br> Manage months</a>');
                    } else {
                        $days = $month->get_dates();
                        $year = date("Y", time());

                        // if notes were edited, processes notes
                        if (array_key_exists('_submit_check_edit_notes', $_POST) && $_SESSION['access_level'] >= 2) {
                            process_edit_notes($month, $group, $_POST, $year);
                            $month = get_dbMonths($monthid);
                        }

                        // shows the previous month / next month navigation
                        $month_nav = do_month_nav($month, $edit, $group);
                        echo $month_nav;

                        // prevents archived months from being edited by anyone
                        if ($month->get_status() == "archived")
                            $edit = false;

                        echo '<form method="POST">';
                        show_month($days, $month, $edit, $year, $group);
                        if ($edit == true && !($days[6]->get_year() < $year || ($days[6]->get_year() == $year) ) && $_SESSION['access_level'] >= 2)
                            echo "<p align=\"center\"><input type=\"submit\" value=\"Save changes to all notes\" name=\"submit\">";
                        echo '</form>';
                    }
                }

                echo " </div>";
                include('footer.inc');
            ?>      
        </div>
    </div>
</body>
</html>