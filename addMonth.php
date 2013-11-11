<?PHP
session_start();
session_cache_expire(30);
?>
<!--
        addMonth.php
        @author Max Palmer and Allen Tucker
        @version 3/25/08, revised 10/19/13
-->
<html>
    <head>
        <title>
            Add a Month to a group's calendar
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            <div id="content">
                <?PHP
                include_once('database/dbMonths.php');
                include_once('database/dbMasterSchedule.php');
                include_once('database/dbPersons.php');
                include_once('database/dbLog.php');
                include_once('domain/Shift.php');
                include_once('domain/Month.php');
                include_once('domain/Person.php');
                
                // Check to see if there are already months in the db
                // connects to the database to see if there are any months in the dbMonths table
                $group = $_GET['group'];
                $groups = array("foodbank"=>"Food Bank", "foodpantry"=>"Food Pantry","soupkitchen"=>"Soup Kitchen");
                $result = getall_dbMonths($group);
            // If no months for either the house or the family room, show first month form
                if (sizeof($result) == 0)
                    $firstmonth = true;
                else
                    $firstmonth = false;
                
                // publishes a month if the user is a manager
                if ($_GET['publish'] && $_SESSION['access_level'] >= 2) {
                    $id = $_GET['publish'];
                    $month = get_dbMonths($id);
                    if ($month->get_status() == "unpublished")
                        $month->set_status("published");
                    else if ($month->get_status() == "published")
                        $month->set_status("unpublished");
                    update_dbMonths($month);
                    add_log_entry('<a href=\"personEdit.php?id=' . $_SESSION['_id'] . '\">' . $_SESSION['f_name'] . ' ' . $_SESSION['l_name'] . '</a> ' .
                            $month->get_status() . ' the month of <a href=\"calendar.php?id=' . $month->get_id() . '&edit=true\">' . $month->get_name() . '</a>.');
                    echo "<p>Month \"" . $month->get_name() . "\" " .
                    $month->get_status() . ".<br>";
					include('addMonth.inc');
                }
                // removes a month if user is a manager
                else if ($_GET['remove'] && $_SESSION['access_level'] >= 2) {
                    $id = $_GET['remove'];
                    $month = get_dbMonths($id);
                    if ($month) {
                      if ($month->get_status() == "unpublished" || $month->get_status() == "archived") {
                        delete_dbMonths($month);
                        add_log_entry('<a href=\"personEdit.php?id=' . $_SESSION['_id'] . '\">' . $_SESSION['f_name'] . ' ' . $_SESSION['l_name'] . '</a> removed the month of <a href=\"calendar.php?id=' . $month->get_id() . '&edit=true\">' . $month->get_name() . '</a>.');
                        echo "<p>Month \"" . $month->get_name() . "\" removed.<br>";
                      }
                      else
                        echo "<p>Month \"" . $month->get_name() . "\" is published, so it cannot be removed.<br>";
					  include('addMonth.inc');
                    }
                }
                else if (!array_key_exists('_submit_check_newmonth', $_POST)) {
                    include('addMonth.inc');
                } else {
                    process_form($firstmonth);
                    include('addMonth.inc');
                }
                
                // must be a manager
                function process_form($firstmonth) {
                	
                	if ($_SESSION['access_level'] < 2)
                        return null;
                    if ($firstmonth == true) {
                        //find the beginning of the month
                        $timestamp = mktime(0, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']);
                        $dow = date("N", $timestamp);
                        $m = date("m", mktime(0, 0, 0, $_POST['month'], $_POST['day'] - $dow + 1, $_POST['year']));
                        $d = date("d", mktime(0, 0, 0, $_POST['month'], $_POST['day'] - $dow + 1, $_POST['year']));
                        $y = date("y", mktime(0, 0, 0, $_POST['month'], $_POST['day'] - $dow + 1, $_POST['year']));
                        generate_populate_and_save_new_month($m, $d, $y, $_POST['monthday_group'],$_POST['monthend_group']);
                    } else {
                        $timestamp = $_POST['_new_month_timestamp'];
                        $m = date("m", $timestamp);
                        $d = date("d", $timestamp);  
                        $y = date("y", $timestamp);
                        // finds the last month, and calculates next month's groups
                        //$month = get_dbMonths($m.'-'.$d.'-'.$y);
                        $monthday_group = $_POST['monthday_group'];
                		$monthend_group = $_POST['monthend_group'];
                        generate_populate_and_save_new_month($m, $d, $y, $monthday_group, $monthend_group);
                    }
                }

                // uses the master schedule to create a new month in dbMonths and 
                // 7 new dates in dbDates and new shifts in dbShifts
                // 
                function generate_populate_and_save_new_month($m, $d, $y, $monthdaygroup, $monthendgroup) {
                    // set the group names the format used by master schedule
                    $monthdays = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
                    $day_id = $m . "-" . $d . "-" . $y;
                    $dates = array();
                    foreach ($monthdays as $day) {
                        if ($day == "Sat" || $day == "Sun")
                            $my_group = $monthendgroup;
                        else
                            $my_group = $monthdaygroup;
                        
                        $venue_shifts = get_master_shifts("monthly", $my_group, $day);
                            /* Each row in the array is an associative array
                             *  of (venue, my_group, day, time, start, end, slots, persons, notes)
                             *  and persons is a comma-separated string of ids, like "alex2077291234"
                             */
                        $shifts = array();
                        if (sizeof($venue_shifts)>0) {
                        	foreach ($venue_shifts as $venue_shift) 
                                $shifts[] = generate_and_populate_shift($day_id, "monthly", $my_group, $day, $venue_shift->get_time(), "");
                        }
                    
                        // makes a new date with these shifts
                        $new_date = new RMHdate($day_id, $shifts, "");
                        $dates[] = $new_date;
                        $d++;
                        $day_id = date("m-d-y", mktime(0, 0, 0, $m, $d, $y));
                    }
                     // creates a new month from the dates
                    $newmonth = new Month($dates, "monthly", $monthdaygroup, $monthendgroup, "unpublished");
                    insert_dbMonths($newmonth);
                    add_log_entry('<a href=\"personEdit.php?id=' . $_SESSION['_id'] . '\">' . $_SESSION['f_name'] . ' ' . $_SESSION['l_name'] . '</a> generated a new month: <a href=\"calendar.php?id=' . $newmonth->get_id() . '&edit=true\">' . $newmonth->get_name() . '</a>.');        
                }

                // makes new shifts, fills from master schedule
                //!
                function generate_and_populate_shift($day_id, $venue, $group, $day, $time, $note) {
                    // gets the people from the master schedule
                    $people = get_person_ids($venue, $group, $day, $time);
                    if (!$people[0])
                        array_shift($people);
                    // changes the people array to the format used by Shift (id, fname lname)
                    for ($i = 0; $i < count($people); ++$i) {
                        $person = retrieve_person($people[$i]);
                        if ($person) {
                        	$people[$i] = $person->get_id() . "+" . $person->get_first_name() . "+" . $person->get_last_name();
                        }
                    }
                    // calculates vacancies
                    $vacancies = get_total_slots($venue, $group, $day, $time) - count($people);
                    // makes a new shift filled with people found above
                    $newShift = new Shift($day_id . "-" . $time, $venue, $vacancies, $people, "", $note);
                    return $newShift;
                    
                }

                // displays form errors (only for first month)
                function show_errors($e) {
                    //this function should display all of our errors.
                    echo("<p><ul>");
                    foreach ($e as $error) {
                        echo("<li><strong><font color=\"red\">" . $error . "</font></strong></li>\n");
                    }
                    echo("</ul></p>");
                }
                ?>
                <?PHP include('footer.inc'); ?>
            </div>
        </div>
    </body>
</html>