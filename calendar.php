<?php
 /*
 * Copyright 2013 by Brian Jacobel, Oliver Fisher, Simon Brooks and Allen Tucker.
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).

 * Based on previous work by Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 

  @author Judy Yang and Jackson Moniaga
 */
session_start();
session_cache_expire(30);
?>
<html>
<head>
    <title>Calendar viewing</title>
    <link rel="stylesheet" href="styles.css" type="text/css" />
    <link rel="stylesheet" href="calendar.css?v=3" type="text/css" />
    <!-- CSS for printing the calendar -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #calendar, #calendar * {
                visibility: visible;
            }
            #calendar {
                position: absolute;
                left: 0;
                top: 0;
            }
            @page { size: landscape; }
        }
    </style>
</head>
<body>
    <div id="container">
        <?PHP include('header.php'); ?>
        <div id="content">
            <?PHP
            		include_once('database/dbMonths.php');
                    include_once('database/dbCrews.php');
                    include_once('database/dbPersons.php');
                    include_once('database/dbLog.php');
                    
				// checks to see if in edit mode
                    $edit = $_GET['edit'];
                    if ($edit != "true")
                        $edit = false;
                    else
                        $edit = true;
                $this_group = $_SESSION['mygroup'];
                $groups = array('foodbank'=>'Food Bank', 'foodpantry'=>'Food Pantry', 'soupkitchen'=>'Soup Kitchen');
                echo("<p style='text-align:center;margin:0 auto;'>( Wrong calendar? Switch to: ");
                foreach ($groups as $group=>$group_name) {
                    echo("<a href='calendar.php?group=".$group."&month=".$_GET['month']."&edit=".$_GET['edit']."'>".$group_name."  </a>");
                }
                echo(")</p>");
                $_SESSION['mygroup'] = $_GET['group'];
                        
                    // gets the week to show, if no week then defaults to current week
                    $group = $_GET['group'];
                    $monthid = $_GET['month']."-".$group;
                    $explode_month = explode("-", $_GET['month']);
                    $year = "20" . $explode_month[0];
            		$month = retrieve_dbMonths($monthid); // get or create the month, as needed
            		include_once 'calendar.inc';
                    
                if ($_SESSION['type'] == "staff" || $_SESSION['type'] == "volunteer") {
                    if ($month->get_status() == "unpublished" && $_SESSION['access_level'] < 2) {
                        echo 'This month\'s calendar is not available for viewing. ';
                        die();
                    } 
                    $days = $month->get_dates();
                    // if notes were edited, processes notes
                    if (array_key_exists('_submit_check_edit_notes', $_POST) && $_SESSION['access_level'] >= 2) {
                            process_edit_notes($month, $group, $_POST, $year);
                            $month = get_dbMonths($monthid);
                    }

                    // prevents archived months from being edited by anyone
                    $today = mktime();
                    if ($month->get_status() == "archived")
                        $edit = false;

                    // shows the previous month / next month navigation
                    $month_nav = do_month_nav($month, $edit, $group);
                    echo $month_nav;

                    ?>

                    <button id="printcal" onclick="printpage()">Print Calendar</button><script>function printpage(){window.print();}</script>

                    <?PHP
                    echo '<form method="POST">';
                    show_month($days, $month, $edit, $year, $group);
               //     if ($edit == true && !($days[6]->get_year() < $year || ($days[6]->get_year() == $year) ) && $_SESSION['access_level'] >= 2)
               //         echo "<p align=\"center\"><input type=\"submit\" value=\"Save changes to all notes\" name=\"submit\">";
                    echo '</form>';
                
                }

                echo " </div>";
                include('footer.inc');
            ?>      
        </div>
    </div>
</body>
</html>