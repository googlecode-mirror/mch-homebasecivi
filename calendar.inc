<?PHP
session_start();
session_cache_expire(30);
?>
<!--
        calendar.inc
        @author Max Palmer Allen Tucker, Brian Jacobel
        @version 3/26/08, revised 9/15/2008, 12/17/2013
-->

<?php

/**
 * shows the "previous month"/"next month" menu, if the weeks exist
 */
function do_month_nav($month, $edit, $group) {
    $cur_id = $month->get_id();
    $prev_id = date("y-m", mktime(0, 0, 0, substr($cur_id, 3, 2)-1, 1, substr($cur_id, 0, 2)))."-".$group;
    $next_id = date("y-m", mktime(0, 0, 0, substr($cur_id, 3, 2)+1, 1, substr($cur_id, 0, 2)))."-".$group;
    $s = "<p><table id=\"weeknav\" align=\"center\"><tr><td align=\"left\" >";

    $s = $s . '<a href="calendar.php?month=' . substr($prev_id, 0, 5) . '&group=' . $group ;
    if ($edit)
        $s = $s . "&edit=true";
    else $s = $s . "&edit=false";
    $s = $s . "\" id=\"weeknavlink\"><< Previous Month</a>";
    
    if ($_SESSION['access_level'] >= 2){
        if ($edit != true)
            $s = $s . '<td align="left"> <a href="calendar.php?month=' . substr($cur_id, 0, 5) . '&group=' . $group . '&edit=true">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(edit this month)</a></td>';
        else
            $s = $s . '<td align="left"> <a href="calendar.php?month=' . substr($cur_id, 0, 5) . '&group=' . $group . '&edit=false">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(view this month)</a></td>';
        $s = $s . '<td align="right"> <a href="addMonth.php?archive=false&group='.$group.'&monthid='.$cur_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(manage months)</a></td>';
        $s = $s . "</td><td align=\"right\">";
    }
    $s = $s . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="calendar.php?month=' . substr($next_id, 0, 5) . '&group=' . $group;
    if ($edit)
        $s = $s . "&edit=true";
    else $s = $s . "&edit=false";
    $s = $s . "\" id=\"weeknavlink\">Next Month>></a>";

    $s = $s . "</td></tr></table></p>";
    
    return $s;
}

/**
 * gets a week object, and displays it as a calendar
 */
function show_month($days, $month, $edit, $year, $group) {
    // gets all of the dates for this week
    // sets up the table, with title, and then day of month
	$groups = array('foodbank'=>'Food Bank', 'foodpantry'=>'Food Pantry', 'soupkitchen'=>'Soup Kitchen');
    $crew_ids = $month->get_crews();
    $crews = array();
    foreach($crew_ids as $crew){
    	if (select_dbCrews($crew)!=null)
        	array_push($crews, select_dbCrews($crew));
        else array_push($crews, new Crew(substr($crew,0,6),substr($crew,9),0,array(),"",""));
    }

    $startday = $startindex = date("w", mktime(0, 0, 0, $month->get_month_number(), 1, $year));
    if ($group=="foodpantry" && $startday==3) 
			$startindex=2;
// echo 'startday='.$startday.' startindex='.$startindex;
    echo ('<table id="calendar" align="center" ><tr class="weekname"><td colspan="16" ' .
    'align="center" >' . $groups[$group] . ' Calendar ' . $month->get_name() . '</td></tr>');

    $daynames = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
	$fpdaynames = array("Monday", "Tuesday", "Wednesday 9:30", "Wednesday 11:45", "Thursday", "Friday", "Saturday");
    if ($group=="foodpantry")
    	$thisdaynames = $fpdaynames;
    else $thisdaynames = $daynames;
    foreach($thisdaynames as $name)
        echo ('<td class="dow">&nbsp; ' . $name . '</td>');
    echo("<tr>");
    // days of the "month" that actually belong to the last month
    if ($startday>=2) {
     	for ($i = 1; $i < $startday; $i++) 
        	echo("<td class='dow'></td>");
    }
    if ($group=="foodpantry" && $startday>3) 
    	echo("<td class='dow'></td>");
    for ($i = 0; $i < count($days); $i++) {
    	if(($i+$startday)%7==0)  // skip sundays
    		continue;
        if(($i+$startday)>6 && ($i+$startday)%7==1){ // new row on successive mondays
            echo("<tr>");
        }
        if ($group=="foodpantry") {
          if (($i+$startday)%7==2) { // skip Tuesdays
          	  echo("<td class='cell'><p class='date'>".($days[$i])."</p><br/>"."</td>");
          }
          else if (($i+$startday)%7==3) { // handle Wednesdays
            if ($startday==$startindex) { // real start day is Tuesday
          	  echo("<td class='cell'><p class='date'>".($days[$i])."</p><br/>".get_crewinfo($edit, $crews, $days[$i-1])."</td>");
        	  echo("<td class='cell'><p class='date'>"."</p><br/>".get_crewinfo($edit, $crews, $days[$i])."</td>");
            }
            else {  // real start day is Wednesday
              echo("<td class='cell'><p class='date'>".($days[$i])."</p><br/>".get_crewinfo($edit, $crews, $days[$i])."</td>");
        	  echo("<td class='cell'><p class='date'>"."</p><br/>".get_crewinfo($edit, $crews, $days[$i+1])."</td>");
            }
          }
          else if (($i+$startday)%7==4)
          	  echo("<td class='cell'><p class='date'>".($days[$i])."</p><br/>"."</td>");
          else if ((($i+$startday)%7==5 || ($i+$startday)%7==6) && $startday!=$startindex)  // handle fridays and saturdays when real start day is Wednesday
              echo("<td class='cell'><p class='date'>".($days[$i])."</p><br/>".get_crewinfo($edit, $crews, $days[$i+1])."</td>");
          else if (($i+$startday)%7==1 && $startday!=$startindex) // handle mondays when real start day is Wednesday      	  
              echo("<td class='cell'><p class='date'>".$days[$i]."</p><br/>".get_crewinfo($edit, $crews, $days[$i+1])."</td>");
          else 
          	  echo("<td class='cell'><p class='date'>".$days[$i]."</p><br/>".get_crewinfo($edit, $crews, $days[$i])."</td>");
        }
        else 
          echo("<td class='cell'><p class='date'>".$days[$i]."</p><br/>".get_crewinfo($edit, $crews, $days[$i])."</td>");
        if(($i+$startday)%7==6){
          echo("</tr>");     // end row on saturdays
        }
    }
    echo "</table>";
}

function get_crewinfo($edit, $crews, $day){
    foreach($crews as $crew){
    	$cgde = explode("-", $crew->get_date());
        if($crew && $cgde[2] == $day){
        	$cgp = $crew->get_persons();
        	 //$person_ids = explode(",", $cgp);
        	if ($edit || $_SESSION['access_level']<2) $prefix = "<a class='celllink' href='editCrew.php?id=".$crew->get_id()."'><br>&nbsp;&nbsp;";
            else $prefix = "";
            $info = $prefix."&nbsp;&nbsp;";
            foreach($cgp as $person_id){
                $id_exploded = explode("+", $person_id);
                $filtered_roles = filter_roles($crew->get_group(), $id_exploded[3]);
                if ($filtered_roles=="()")
                	$display_name = $id_exploded[1]. " " . $id_exploded[2];
                else 
                	$display_name = $id_exploded[1]. " " . $id_exploded[2]. " " .$filtered_roles ;
				$info = $info . $display_name . "<br>&nbsp;&nbsp;";
            }
			if ($crew->num_vacancies()>0)
            	$info = $info . "<strong>Vacancies (" . $crew->num_vacancies() . ")</strong><br>";
            if ($prefix == "")
                return $info;    
            else return $info."</a>";
        }
    }
}

// show only those roles for the given group
function filter_roles($group, $roles){
    $group_roles = array("foodbank"=>array("CC","B","DD","P"),
                        "foodpantry"=>array("CC","PR","I","M","CO"),
                        "soupkitchen"=>array("CC","CH","Pots","Dishes"));
    $candidates = $group_roles[$group];
    $filtered_roles = "";
    $trimmed_roles = explode(" ",substr($roles,1,strlen($roles)-2));
    foreach ($trimmed_roles as $role) {
        if (in_array($role, $candidates))
            if ($filtered_roles=="")
                $filtered_roles = $role; 
            else 
                $filtered_roles .= " ".$role;
    } 
    return "(".$filtered_roles.")";
}

function get_dom($day) {
    $dom = $day->get_dom();
    if (substr($dom, 0, 1) == "0")
        return "&nbsp;&nbsp;" . substr($dom, 1);
    return $dom;
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