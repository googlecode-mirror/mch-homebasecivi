<?PHP
session_start();
session_cache_expire(30);
?>
<!-- page generated by the BowdoinRMH software package -->
<html>
    <head>
        <title>
            Edit Master Schedule Crew
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            <div id="content">
                <?php
                if ($_SESSION['access_level'] < 2) {
                    die("<p>Only staff can edit the master schedule.</p>");
                }
                if(isset($_SESSION['mygroup'])){
                    $group = $_SESSION['mygroup'];
                } else {
                    $group = "foodbank";
                }
                $group = $_GET['group'];
                $day = $_GET['day'];
                $week_no = $_GET['week_no'];
                
                include_once('database/dbMasterSchedule.php');
                include_once('domain/MasterScheduleEntry.php');
                include_once('database/dbLog.php');
                if ($group == "" || $day == "" || $week_no == "") {
                    echo "<p>Invalid schedule parameters.  Please click on the \"Master Schedule\" link above to edit a master schedule crew.</p>";
                } // see if there is no master crew for this time slot and try to set times starting there
                else if (retrieve_dbMasterSchedule($group . $day . $week_no) == false) {
                    $result = process_set_times($_POST, $group, $day, $week_no);
                    if ($result) {
                        $returnpoint = "viewSchedule.php?group=" . $group;
                        echo "<table align=\"center\"><tr><td align=\"center\" width=\"442\">
								<br><a href=\"" . $returnpoint . "\">
								Back to Master Schedule</a></td></tr></table>";
                    }
                    // if not, there's an opportunity to add a crew 
                    else {
                        echo ("<table align=\"center\" width=\"450\"><tr><td align=\"center\" colspan=\"2\"><b>
								Adding a New Master Schedule crew for " . $do_group_name($group) . ", " .
                        do_week_name($week_no) . " " . do_day_name($day) . " " . "</b></td></tr>"
                        . "<tr><td>
									<form method=\"POST\" style=\"margin-bottom:0;\">
									<select name=\"new_slots\">
									<option value=\"0\">Please select the number of slots for this crew</option>"
                        . slots_select() .
                        "</select><br>
									<br><br>
									<input type=\"hidden\" name=\"_submit_change_times\" value=\"1\">
									<input type=\"submit\" value=\"Add New Crew\" name=\"submit\">
									</form><br></td></tr></table>");
                    }
                } else { // if one is there, see what we can do to update it
                	if (!process_fill_vacancy($_POST, $group, $day, $week_no) && // try to fill a vacancy
                            !process_add_volunteer($_POST, $group, $day, $week_no) &&
                            !process_remove_crew($_POST, $group, $day, $week_no)) { // try to remove the crew
                        if (process_unfill_crew($_POST, $group, $day, $week_no)) {  // try to remove a person
                        } else if (process_add_slot($_POST, $group, $day, $week_no)) { // try to add a new slot
                        } else if (process_ignore_slot($_POST, $group, $day, $week_no)) {  //try to remove a slot
                        }
                        // we've tried to clear the crew, add a slot, or remove a slot;
                        // so now display the crew again.
                        $persons = get_persons($group, $day, $week_no);
                        echo ("<br><table align=\"center\" width=\"450\" border=\"1px\"><tr><td align=\"center\" colspan=\"2\"><b>
								Editing master schedule crew for " . 
                        do_group_name($group) . "<br>" . do_week_name($week_no) . " " . do_day_name($day) . "
								</b>
								<form method=\"POST\" style=\"margin-bottom:0;\">
									<input type=\"hidden\" name=\"_submit_remove_crew\" value=\"1\"><br>
									<input type=\"submit\" value=\"Remove Entire Crew\"
									name=\"submit\">
									</form>
								</td></tr>"
                        . "<tr><td valign=\"top\"><br>&nbsp;" . do_slot_num($group, $day, $week_no) . "</td><td>
									<form method=\"POST\" style=\"margin-bottom:0;\">
									<input type=\"hidden\" name=\"_submit_add_slot\" value=\"1\"><br>
									<input type=\"submit\" value=\"Add Slot\"
									name=\"submit\" style=\"width: 250px\">
									</form></td></tr>");
                        echo (display_filled_slots($persons)
                        . display_vacant_slots(get_total_vacancies($group, $day, $week_no))
                        . "</table>");
                        $returnpoint = "viewSchedule.php?group=" . $group;
                        echo "<table align=\"center\"><tr><td align=\"center\" width=\"442\">
									       <br><a href=\"" . $returnpoint . "\">
										   Back to Master Schedule</a></td></tr></table>";
                    }
                }
                ?>
                <br>
             </div>
          <?PHP include('footer.inc'); ?>
        </div>
    </body>
</html>

                <?php

                function slots_select() {
                    $s = "";
                    for ($slots = 0; $slots < 15; $slots++) {
                        $s = $s . "<option value=\"" . $slots . "\">" . $slots . "</option>";
                    }
                    return $s;
                }

                function process_set_times($post, $group, $day, $week_no) {
                    if (!array_key_exists('_submit_change_times', $post))
                        return false;
                    if ($post['new_start'] == "0")
                        $error = "Can't add new crew: you must select a start time.<br><br>";
                    else if ($post['new_start'] != "overnight" && $post['new_end'] == "0")
                        $error = "Can't add new crew: you must select an end time.<br><br>";
                    else {
                        $entry = new MasterScheduleEntry($group, $day, $week_no);
                        if (!insert_nonoverlapping($entry))
                            $error = "Can't insert a new crew into an overlapping group, day, week.<br><br>";
                    }
                    if ($error) {
                        echo $error;
                        return true;
                    } else {
                        echo "Added a new crew for " . do_group_name($group) . " " . do_day_name($day) . " " . do_week_name($week_no) . "<br><br>";
                        return true;
                    }
                }

                function process_remove_crew($post, $group, $day, $week_no) {
                    if (!array_key_exists('_submit_remove_crew', $post))
                        return false;
                    $id = $group . $day . $week_no ;
                    if (delete_dbMasterSchedule($id)) {
                    	echo "<br>Removed entire crew for " . do_group_name($group) .", ". do_week_name($week_no) ." ". do_day_name($day) . "<br><br>";
                        $returnpoint = "viewSchedule.php?group=" . $group;
                        echo "<table align=\"center\"><tr><td align=\"center\" width=\"442\">
				<br><a href=\"" . $returnpoint . "\">
				Back to Master Schedule</a></td></tr></table>";
                        return true;
                    }
                    return false;
                }

                function do_group_name($id) {
                    $group_names = array ("foodbank"=>"Food Bank", "foodpantry"=>"Food Pantry", "soupkitchen"=>"Soup Kitchen");
                    return $group_names[$id];
                }
				function do_day_name($id) {
                    $day_names = array ("Mon"=>"Monday", "Tue"=>"Tuesday", "Wed"=>"Wednesday", "Wed930"=>"Wednesday 9:30", "Wed1100"=>"Wednesday 11:45", "Thu"=>"Thursday", "Fri"=>"Friday", "Sat"=>"Saturday", "Sun"=>"Sunday");
                    return $day_names[$id];
                }
                function do_week_name($id) {
                    $week_names = array (1=>"1st", 2=>"2nd", 3=>"3rd", 4=>"4th", 5=>"5th");
                    return $week_names[$id];
                }
                function do_slot_num($group, $day, $week_no) {
                    $slots = get_total_slots($group, $day, $week_no);
                    if ($slots == 1)
                        return "1 slot for this crew:";
                    return $slots . " slots for this crew:";
                }

                function display_filled_slots($persons) {
                    $s = "";
                    if (!$persons[0])
                        array_shift($persons);
                    for ($i = 0; $i < count($persons); ++$i) {
                        $p = $persons[$i];
                        if (is_array($persons[$i])) {
                            $p = $persons[$i]['first_name'] . " " . $persons[$i]['last_name'];
                            if ($persons[$i]['role']!="")
                            	$p = "(".$persons[$i]['role'].") ".$p;
                        }
                        $s = $s . "<tr><td width=\"150\" valign=\"top\"><br>&nbsp;" . $p . "</td><td>
				<form method=\"POST\" style=\"margin-bottom:0;\">
				<input type=\"hidden\" name=\"_submit_filled_slot_" . $i . "\" value=\"1\"><br>
				<input type=\"submit\" value=\"Remove Person / Create Vacancy\" name=\"submit\" style=\"width: 250px\">
			</form></td></tr>";
                    }
                    return $s;
                }

                function display_vacant_slots($vacancies) {
                    $s = "";
                    for ($i = 0; $i < $vacancies; ++$i) {
                        $s = $s . "<tr><td width=\"150\" valign=\"top\"><br>&nbsp;<b>vacant</b></td><td>
				<form method=\"POST\" style=\"margin-bottom:0;\">
				<input type=\"hidden\" name=\"_submit_fill_vacancy\" value=\"1\"><br>
				<input type=\"submit\" value=\"Assign Volunteer\" name=\"submit\" style=\"width: 250px\"></form>
				<form method=\"POST\" style=\"margin-bottom:0;\">
				<input type=\"hidden\" name=\"_submit_ignore_vacancy\" value=\"1\">
				<input type=\"submit\" value=\"Remove Vacant Slot\" name=\"submit\" style=\"width: 250px\"></form>
				</td></tr>";
                    }
                    return $s;
                }

                function process_fill_vacancy($post, $group, $day, $week_no) {
                    if (!array_key_exists('_submit_fill_vacancy', $post))
                        return false;
                    echo "<table align=\"center\"><tr><td align=\"center\" border=\"1px\"><br><b>
		Filling a vacancy for " . do_group_name($group) . ", " . do_week_name($week_no) . " " . do_day_name($day) . "
		</b></td></tr>
		<tr><td align=\"center\"><form method=\"POST\" style=\"margin-bottom:0;\">
			<select name=\"scheduled_vol\">
			<option value=\"0\" style=\"width: 371px;\">Select a volunteer with " . do_week_name($week_no) . " " . do_day_name($day) . " availability</option>"
                    . get_available_volunteer_options($group, $day, $week_no, get_persons($group, $day, $week_no)) .
                    "</select><br><br>
			<select name=\"all_vol\">
			<option value=\"0\" style=\"width: 371px;\">Select from all volunteers in the " . do_group_name($group) . " group</option>"
                    . get_all_volunteer_options($group, get_persons($group, $day, $week_no)) .
                    "</select><br><br>
			<input type=\"hidden\" name=\"_submit_add_volunteer\" value=\"1\">
			<input type=\"submit\" value=\"Add Volunteer\" name=\"submit\" style=\"width: 200px\">
			</form></td></tr>";
                    echo "</table>";
                    echo "<br><table align=\"center\"><tr><td align=\"center\" width=\"450\">
		<a href=\"editMasterSchedule.php?group=" . $group . "&day=" . $day . "&week_no=" . $week_no . "\">Back to Crew</a><br></td></tr></table>";
                    return true;

                    // check that person is not already working that crew
                    // check that person is available
                }

                function process_unfill_crew($post, $group, $day, $week_no) {
                    $persons = get_persons($group, $day, $week_no);
                    if (!$persons[0])
                        array_shift($persons);
                    for ($i = 0; $i < count($persons); ++$i) {
                        if (array_key_exists('_submit_filled_slot_' . $i, $post)) {
                            if (is_array($persons[$i]))
                                unschedule_person($group, $day, $week_no, $persons[$i]['id']);
                            else
                                unschedule_person($group, $day, $week_no, $persons[$i]);
                            return true;
                        }
                    }
                    return false;
                }

                function process_add_slot($post, $group, $day, $week_no) {
                    if (array_key_exists('_submit_add_slot', $post)) {
                        edit_schedule_vacancy($group, $day, $week_no, 1);
                        return true;
                    }
                    return false;
                }

                function process_ignore_slot($post, $group, $day, $week_no) {
                    if (array_key_exists('_submit_ignore_vacancy', $post)) {
                        edit_schedule_vacancy($group, $day, $week_no, -1);
                        return true;
                    }
                    return false;
                }

                function get_available_volunteer_options($group, $day, $week_no, $persons) {
                    if (!$persons[0])
                        array_shift($persons);
                    connect();

                    $query = "SELECT * FROM dbPersons WHERE status = 'active' " .
                    		"AND `group` LIKE '%" . $group . "%' " .
                            "AND availability LIKE '%" . substr($day,0,3) . ":" . $week_no . "%' ORDER BY last_name,first_name";
                    $result = mysql_query($query);
                    mysql_close();
                    $s = "";
                    for ($i = 0; $i < mysql_num_rows($result); ++$i) {
                        $row = mysql_fetch_row($result);
                        $value = $row[0];
                        $label = $row[2] . ", " . $row[1];
                        $match = false;
                        for ($j = 0; $j < count($persons); ++$j) {
                            if ($value == $persons[$j]['id']) {
                                $match = true;
                            }
                        }
                        if (!$match) {
                            $s = $s . "<option value=\"" . $value . "\">" . $label . "</option>";
                            $match = false;
                        }
                    }
                    return $s;
                }
				// list everyone in this group except persons already scheduled in this crew
                function get_all_volunteer_options($group, $persons) {
                    if (!$persons[0])
                        array_shift($persons);
                    connect();
                    $query = "SELECT * FROM dbPersons WHERE status = 'active' " .
                    		"AND `group` LIKE '%" . $group . "%' " .
                            " ORDER BY last_name,first_name";
                    $result = mysql_query($query);
                    mysql_close();
                    $s = "";
                    for ($i = 0; $i < mysql_num_rows($result); ++$i) {
                        $row = mysql_fetch_row($result);
                        $value = $row[0];
                        $label = $row[2] . ", " . $row[1];
                        $match = false;
                        for ($j = 0; $j < count($persons); ++$j) {
                            if ($value == $persons[$j]['id']) {
                                $match = true;
                            }
                        }
                        if (!$match) {
                            $s = $s . "<option value=\"" . $value . "\">" . $label . "</option>";
                            $match = false;
                        }
                    }
                    return $s;
                }

                function process_add_volunteer($post, $group, $day, $week_no) {
                    if (!array_key_exists('_submit_add_volunteer', $post))
                        return false;
                    if ($post['all_vol'] == "0" && $post['scheduled_vol'] == "0")
                        $error = "<table align=\"center\"><tr><td width=\"400\">
				You must select a volunteer.</td></tr></table><br>";
                    else if ($post['all_vol'] == "0")
                        $vol = $post['scheduled_vol'];
                    else
                        $vol = $post['all_vol'];
                    schedule_person($group, $day, $week_no, $vol);
                    return false;
                }
                
?>
