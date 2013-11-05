<?PHP
session_start();
session_cache_expire(30);
?>
<!--
        calendar.inc
        @author Max Palmer and Allen Tucker
        @version 3/26/08, revised 9/15/2008
-->

<?php

/**
 * shows the "previous week"/"next week" menu, if the weeks exist
 */
function do_month_nav($week, $edit, $group) {
    // $cur_id = $week->get_id();
    // $prev_id = date("m-d-y", mktime(0, 0, 0, substr($cur_id, 0, 2), substr($cur_id, 3, 2) - 7, substr($cur_id, 6, 2)));
    // $next_id = date("m-d-y", mktime(0, 0, 0, substr($cur_id, 0, 2), substr($cur_id, 3, 2) + 7, substr($cur_id, 6, 2)));
    // $s = "<p><table id=\"weeknav\" align=\"left\"><tr><td align=\"left\" >";
    // // determines if there is a previous week
    // $prev_week = get_dbWeek($prev_id);
    // if ($prev_week instanceof Week) {
    //     $s = $s . '<a href="calendar.php?id=' . $prev_id . '&venue=' . $group;
    //     if ($edit)
    //         $s = $s . "&edit=true";
    //     $s = $s . "\" id=\"weeknavlink\"><< Previous Week</a>";
    // }
    // else $s = $s . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    // if ($_SESSION['access_level'] >= 1)
    //     if ($edit != true)
    //         $s = $s . '<td align="left"> <a href="calendar.php?id=' . $cur_id . '&venue=' . $group . '&edit=true">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(edit this week)</a></td>';
    //     else
    //         $s = $s . '<td align="left"> <a href="calendar.php?id=' . $cur_id . '&venue=' . $group . '&edit=false">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(view this week)</a></td>';
    // if ($_SESSION['access_level'] >= 2)
    //     $s = $s . '<td align="right"> <a href="addWeek.php?archive=false">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(manage weeks)</a></td>';
    // $s = $s . "</td><td align=\"right\">";
    // // determines if there is a next week
    // $next_week = get_dbWeeks($next_id);
    // if ($next_week instanceof Week) {
    //     $s = $s . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="calendar.php?id=' . $next_id . '&venue=' . $group;
    //     if ($edit)
    //         $s = $s . "&edit=true";
    //     $s = $s . "\" id=\"weeknavlink\">Next Week >></a>";
    // }
    // else $s = $s . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    // $s = $s . "</td></tr></table></p><br><br>";
    // return $s;
}

/**
 * gets a week object, and displays it as a calendar
 */
function show_month($days, $month, $edit, $year, $group) {
    // gets all of the dates for this week
    // sets up the table, with title, and then day of month
    // $free_hour = array();
    // for ($i = 0; $i < 91; $i++)
    //     $free_hour[] = true;

    $crew_ids = $month->get_crews();

    $crews = [];

    foreach($crew_ids as $crew){
        array_push($crews, select_dbCrews($crew));
    }

    $startday = date("w", mktime(0, 0, 0, $month->get_month_number(), 1, $year));

    echo ('<table id="calendar" align="center" ><tr class="weekname"><td colspan="16" ' .
    'align="center" >' . 'Calendar: ' . $month->get_name() . '</td></tr>');

    $daynames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    foreach($daynames as $name)
        echo ('<td class="dow">&nbsp; ' . $name . '</td>');

    // TABLE STARTS HERE
    
    // days of the "month" that actually belong to the last month
    echo("<tr>");
    for ($i = 0; $i < $startday; $i++) {
        echo("<td class='dow'></td>");
    }

    // actual days of the month
    for ($i = 0; $i <= count($days); $i++) {
        if(($i+$startday)%7==0){
            echo("<tr>");
        }
        echo("<td class='cell'><p class='date'>".$days[$i]."</p>".get_crewinfo($crews, $days[$i])."</td>");
        if(($i+$startday)%7==7){
            echo("</tr>");
        }
    }

    //$limit = 22;
    
    // for ($hour = 9; $hour < $limit; $hour++) {
    //     echo ("<tr><td class=\"hour\">" . show_hours($group, $hour) . "</td>");
    //     for ($i = 0; $i < 7; $i++) {
    //         $shiftid = $days[$i]->get_shift_id($hour, $group);
    //         if ($shiftid) {
    //         	$shift_length = get_shift_end($shiftid) - get_shift_start($shiftid);
    //         	echo do_shift($days[$i], substr($shiftid, 9), $shift_length, $edit, $group);
    //             for ($j = $hour; $j < $hour + $shift_length; $j++)
    //                 $free_hour[7 * ($j - 9) + $i] = false;
    //         } 
    //         else 
    //         if ($free_hour[7 * ($hour - 9) + $i])
    //             echo "<td colspan='2' bgcolor=\"lightgray\"></td>";
    //     }
    //     echo ("<td class=\"hour\">" . show_hours($group, $hour) . "</td></tr>");
    // }


    // notes
    //echo do_day_notes($days, $edit, $year);
    //echo "<input type=\"hidden\" name=\"_submit_check_edit_notes\" value=\"1\">";
    //echo "<input type=\"hidden\" name=\"weekid\" value=\"" . $days[0]->get_id() . "\">";
    echo "</table>";
}

// function show_hours($group, $hour) {
//     $d = 3;
//     if ($hour==21) {
//     	return "overnight";
//     }
//     else {
//     $clock = $hour < 12 ? $hour . "am" : $hour - 12 . "pm";
//     $clockend = $hour + $d < 12 ? ($hour + $d) . "am" : ($hour - 12 + $d) . "pm";
//     if ($clock == "0pm")
//         $clock = "12pm";
//     if ($clockend == "0pm")
//         $clockend = "12pm";
//     if (($clock) % $d == 0)
//         return $clock . " - " . $clockend;
//     else
//         return "";
//     }
// }


function get_crewinfo($crews, $day){
    foreach($crews as $crew){
        if($crew && explode("-", $crew->get_date())[2] == $day){
            $person_ids = explode(",", $crew->get_persons()[0]);

            var_dump($person_ids);

            $info = "";

            foreach($person_ids as $person_id){
                $id_exploded = explode("+", $person_id);
                $base_url = strrev(substr(strrev($_SERVER['SCRIPT_NAME']), strpos(strrev($_SERVER['SCRIPT_NAME']), '/')));
                $url = $base_url."personEdit.php?id=".$id_exploded[0];

                $display_name = $id_exploded[1]. " " . $id_exploded[2];

                $info = $info . "<a href=".$url.">";

                $info = $info . $display_name;

                $info = $info . "</a><br/>";
            }

            return $info;
        }
    }
}

// return a string of html that contains a table cell for a shift
function do_shift($day, $shift, $length, $edit, $group) {



    // // chooses size annd style of cell based on length
    // $s = "<td colspan=\"2\" ";
    // $s = $s . "rowspan='" . $length . "' class = 'shift'";
    // // checks that the shift is not in the past
    // $year = date("Y", time());
    // $doy = date("z", time()) + 1;
    // if ($edit && ($year < $day->get_year() || $year == $day->get_year()))
    //     $s = $s . "e";
    // $s = $s . "\"><table id=\"shiftbox\"><tr><td class=\"persons\">";
    // if ($edit && ($year < $day->get_year() || $year == $day->get_year()))
    //     $s = $s . "<a id=\"shiftlink\" href=\"editShift.php?shift=" . $day->get_id() . "-" . $shift . "&venue=" . $group . "\">";
    // $s = $s . get_people_for_shift($day, $shift);
    // if ($edit && ($year < $day->get_year() || $year == $day->get_year())) {
    //     $s = $s . "</a>";
    //     // if manager, adds shift notes
    //     if ($_SESSION['access_level'] >= 2) {
    //         $s = $s . "</td></tr><tr><td class=\"notes\" align=\"center\">" .
    //                 "<textarea id=\"shift_notes\" rows=\"1\" name=\"shift_notes_" . $day->get_shift($shift)->get_id() . "\">"
    //                 . $day->get_shift($shift)->get_notes() . "</textarea>";
    //     } else {
    //         $shiftnote = $day->get_shift($shift)->get_notes();
    //         $s = $s . "</td></tr><tr><td class=\"notes\"><p id=\"shift\">" . $shiftnote . "</p>";
    //     }
    // } else {
    //     $shiftnote = $day->get_shift($shift)->get_notes();
    //     $s = $s . "</td></tr><tr><td class=\"notes\"><p id=\"shift\">" . $shiftnote . "</p>";
    // }
    // $s = $s . "</td></tr></table></td>";
    // return $s;
}

function get_dom($day) {
    $dom = $day->get_dom();
    if (substr($dom, 0, 1) == "0")
        return "&nbsp;&nbsp;" . substr($dom, 1);
    return $dom;
}

function get_people_for_shift($day, $key) {
	//echo "we are getting people for shift ".$day->get_shift($key);
    $shift = $day->get_shift($key);
    $people = $shift->get_persons();
    if (!$people[0])
        array_shift($people);
    $p = "<br>";
    $vac = $shift->num_vacancies();
//		echo $shift->get_id()." slots = ".$shift->num_slots()."  vac = ".$shift->num_vacancies()."<br>";
    for ($i = 0; $i < sizeof($people); ++$i) {
        if (!$people[$i] == "") {
            $name_components = explode("+", $people[$i]);
            $p = $p . "&nbsp;" . $name_components[1] . " " . $name_components[2] . "<br>";
        }
    }
    if ($vac > 0)
        $p = $p . "&nbsp;<b>Vacancies (" . $vac . ")</b>";
    return substr($p, 0, strlen($p) - 4);
}

function do_day_notes($days, $edit, $year, $doy) {
    if ($edit == false || $_SESSION['access_level'] < 2) {
        $s = "<tr><td class=\"hour\">manager notes&nbsp;</td>";
        for ($i = 0; $i < 7; ++$i) {
            $s = $s . "<td class=\"note\" colspan=\"2\">" . $days[$i]->get_mgr_notes() . "</td>";
        }
        return $s . "<td class=\"hour\">manager notes&nbsp;</td></tr>";
    } else {
        $s = "<tr><td class=\"hour\">manager notes&nbsp;</td>";
        for ($i = 0; $i < 7; ++$i) {
            if ($year < $days[$i]->get_year() || $year == $days[$i]->get_year() && $doy <= $days[$i]->get_day_of_year()) {
                $s = $s . "<td class=\"note_e\" colspan=\"2\"><textarea id=\"mgr_notes\"
					 rows=\"1\" name=\"mgr_notes" . $i . "\">"
                        . $days[$i]->get_mgr_notes() . "</textarea></td>";
            }
            else 
                $s = $s . "<td class=\"note\" colspan=\"2\">" . $days[$i]->get_mgr_notes() . "</td>";
        }
        return $s . "<td class=\"hour\">manager notes&nbsp;</td></tr>";
    }
}

function process_edit_notes($week, $group, $post) {
    $days = $week->get_dates();
    for ($i = 0; $i < 7; ++$i) {
        $shifts = $days[$i]->get_shifts();
        foreach ($shifts as $key => $shift) {
        	$note = trim(str_replace('\"', '\\\"', str_replace('\'', '\\\'', htmlentities($post['shift_notes_' . $shift->get_id()]))));
            $shift->set_notes($note);
            update_dbShifts($shift);
      //    }
        }
    //    if ($year < $days[$i]->get_year() || $year == $days[$i]->get_year() && $doy <= $days[$i]->get_day_of_year()) {
            $mgr_note = trim(str_replace('\"', '\\\"', str_replace('\'', '\\\'', htmlentities($post['mgr_notes' . $i]))));
            $days[$i] = select_dbDates($days[$i]->get_id());
            $days[$i]->set_mgr_notes($mgr_note);
            update_dbDates($days[$i]);
    //    }
        
    }
}
?>