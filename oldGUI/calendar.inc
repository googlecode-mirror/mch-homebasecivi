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
	function do_week_nav($week, $edit, $venue) {
		$cur_id=$week->get_id();
//		echo ("We are doing a nav for week ".$cur_id."<br>");
	    $prev_id=date("m-d-y",mktime(0,0,0,substr($cur_id,0,2),substr($cur_id,3,2)-7,substr($cur_id,6,2)));
		$next_id=date("m-d-y",mktime(0,0,0,substr($cur_id,0,2),substr($cur_id,3,2)+7,substr($cur_id,6,2)));
		$s="<p align=\"center\"><table id=\"weeknav\" align=\"center\"><tr><td align=\"left\">";
		// determines if there is a previous week
		$prev_week=get_dbWeeks($prev_id);
		if ($prev_week instanceof Week) {
			$s=$s.'<a href="calendar.php?id='.$prev_id.'&venue='.$venue;
			if($edit)
				$s=$s."&edit=true";
			$s=$s."\" id=\"weeknavlink\"><< Previous Week</a>";
		}
		$s=$s."</td>";

		$s = $s . "<td align=\"right\">";
		// determines if there is a next week
		$next_week=get_dbWeeks($next_id);
		if($next_week instanceof Week) {
			$s=$s.'<a href="calendar.php?id='.$next_id.'&venue='.$venue;
			if($edit)
				$s=$s."&edit=true";
			$s=$s."\" id=\"weeknavlink\">Next Week >></a>";
		}

		$s=$s."</td></tr><tr>" ;
		if($_SESSION['access_level']>=1 )
		  if ($edit != true)
		    $s = $s . '<td align="left"> <a href="calendar.php?id=' . $cur_id . '&venue=' . $venue . '&edit=true">(edit this week)</a></td>';
		  else
		    $s = $s . '<td align="left"> <a href="calendar.php?id=' . $cur_id . '&venue=' . $venue . '&edit=false">(view this week)</a></td>';
		if($_SESSION['access_level']>=2)
		    $s = $s . '<td align="right"> <a href="addWeek.php?archive=false">(manage weeks)</a></td>';
		$s = $s."</tr></table></p>";
		return $s;
	}

  /**
	* gets a week object, and displays it as a calendar
	*/
	function show_week($days,$week,$edit,$year,$doy,$venue) {
		// gets all of the dates for this week
		// sets up the table, with title, and then day of month
		$free_hour = array();
		for ($i=0; $i<84; $i++)
		    $free_hour[] = true;
//		echo ("Showing week ". $week->get_id(). " for venue " . $venue . " and first day " . $days[0]->get_id() . "<br>");
		$venuename = $venue;
		$daynames = array ("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		echo ('<table id="calendar" align="center" ><tr class="weekname"><td colspan="16" ' .
				'align="center" >'.$venuename.' Calendar: '.$week->get_name().'</td></tr>
				<tr><td bgcolor=tan></td>');
		for($i=0; $i<7; $i++)
			echo ('<td class="dom">'.get_dom($days[$i]).'</td><td class="dow">&nbsp; '. $daynames[$i] .'</td>');
		echo('<td bgcolor=tan></td></tr>');
                $limit = 21;
		for ($hour=9; $hour < $limit; $hour++) {
			echo ("<tr><td class=\"hour\">".show_hours($venue, $hour)."</td>");
			for ($i=0; $i<7; $i++){
				$shiftid = $days[$i]->get_shift_id($hour, $venue);
				if ($shiftid) {
					$shift_length = get_shift_end($shiftid)-get_shift_start($shiftid);
					echo do_shift($days[$i],substr($shiftid,9),
					    $shift_length,$edit,$venue);
					for ($j=$hour; $j<$hour+$shift_length; $j++)
					    $free_hour[7*($j-9)+$i] = false;
				}
				else if ($free_hour[7*($hour-9)+$i])
				    echo "<td colspan='2' bgcolor=\"lightgray\"></td>";

			}
			echo ("<td class=\"hour\">".show_hours($venue, $hour)."</td></tr>");
		}


// notes
		echo do_day_notes($days,$edit, $year, $doy);
		echo "<input type=\"hidden\" name=\"_submit_check_edit_notes\" value=\"1\">";
		echo "<input type=\"hidden\" name=\"weekid\" value=\"".$days[0]->get_id()."\">";
		echo "</table>";
	}
	
	function show_hours($venue, $hour) {
                $d = 3;
		$clock = $hour < 12 ? $hour . "am"  : $hour-12 . "pm";
		$clockend = $hour+$d<12?($hour+$d) . "am" : ($hour-12+$d) . "pm";
		if ($clock == "0pm") $clock = "12pm";
		if ($clockend == "0pm") $clockend = "12pm";
		if (($clock) % $d == 0 )
		   return $clock . " - " . $clockend;
		else return "";
	}
	
	// return a string of html that contains a table cell for a shift
	function do_shift($day,$shift,$length,$edit,$venue) {
		// chooses size annd style of cell based on length
		$s= "<td colspan=\"2\" ";
		$s = $s . "rowspan='".$length."' class = 'shift'";
		// checks that the shift is not in the past
		$year=date("Y",time());
		$doy=date("z",time())+1;
		if($edit && ($year < $day->get_year() || $year == $day->get_year() && $doy <= $day->get_day_of_year() ) )
			$s=$s."e";
		$s=$s."\"><table id=\"shiftbox\"><tr><td class=\"persons\">";
		if($edit && ($year < $day->get_year() || $year == $day->get_year() && $doy <= $day->get_day_of_year() ) )
			$s=$s."<a id=\"shiftlink\" href=\"editShift.php?shift=".$day->get_id()."-".$shift."&venue=".$venue."\">";
		$s=$s.get_people_for_shift($day,$shift);
		if($edit && ($year < $day->get_year() || $year == $day->get_year() && $doy <= $day->get_day_of_year() ) ) {
			$s=$s."</a>";
			// if manager, adds shift notes
			if($_SESSION['access_level']>=2) {
				$s=$s."</td></tr><tr><td class=\"notes\" align=\"center\">".
				"<textarea id=\"shift_notes\" rows=\"1\" name=\"shift_notes_".$day->get_shift($shift)->get_id()."\">"
					.$day->get_shift($shift)->get_notes()."</textarea>";
			}
			else {
				$shiftnote=$day->get_shift($shift)->get_notes();
			    $s=$s."</td></tr><tr><td class=\"notes\"><p id=\"shift\">".$shiftnote."</p>";
			}
		}
		else {
			$shiftnote=$day->get_shift($shift)->get_notes();
			$s=$s."</td></tr><tr><td class=\"notes\"><p id=\"shift\">".$shiftnote."</p>";
		}
		$s=$s."</td></tr></table></td>";
		return $s;
	}

	function get_dom($day) {
		$dom=$day->get_dom();
		if(substr($dom,0,1)=="0")
			return "&nbsp;&nbsp;".substr($dom,1);
		return $dom;
	}

	function get_people_for_shift($day,$key) {
		$shift=$day->get_shift($key);
		$people=$shift->get_persons();
		if(!$people[0])
			array_shift($people);
		$p="<br>";
		$vac=$shift->num_vacancies();
//		echo $shift->get_id()." slots = ".$shift->num_slots()."  vac = ".$shift->num_vacancies()."<br>";
		for($i=0;$i<sizeof($people);++$i) {
			if(!$people[$i]=="") {
				$name_components=explode("+",$people[$i]);
				$p=$p."&nbsp;".$name_components[1]." ".$name_components[2]."<br>";
			}
		}
		if($vac>0)
			$p=$p."&nbsp;<b>Vacancies (".$vac.")</b>";
		return substr($p,0,strlen($p)-4) ;
	}

	function do_day_notes($days, $edit, $year, $doy) {
		if($edit==false || $_SESSION['access_level']<2) {
			$s="<tr><td class=\"hour\">manager notes&nbsp;</td>";
			for($i=0;$i<7;++$i) {
				$s=$s."<td class=\"note\" colspan=\"2\">".$days[$i]->get_mgr_notes()."</td>";
			}
			return $s."<td class=\"hour\">manager notes&nbsp;</td></tr>";
		}
		else {
			$s="<tr><td class=\"hour\">manager notes&nbsp;</td>";
			for($i=0;$i<7;++$i) {
				if ($year < $days[$i]->get_year() || $year == $days[$i]->get_year() && $doy <= $days[$i]->get_day_of_year())
				{
					 $s=$s."<td class=\"note_e\" colspan=\"2\"><textarea id=\"mgr_notes\"
					 rows=\"1\" name=\"mgr_notes".$i."\">"
					 .$days[$i]->get_mgr_notes()."</textarea></td>";
				}
				else
				    $s=$s."<td class=\"note\" colspan=\"2\">".$days[$i]->get_mgr_notes()."</td>";
			}
			return $s."<td class=\"hour\">manager notes&nbsp;</td></tr>";
		}
	}

	function process_edit_notes($week,$venue,$post) {
		  $days=$week->get_dates();
		  for($i=0;$i<7;++$i) {
			$shifts=$days[$i]->get_shifts();
			foreach ($shifts as $key => $shift) {
				if ($shift->get_venue() == $venue) {
					$note=trim(str_replace('\"','\\\"',str_replace('\'','\\\'',htmlentities($post['shift_notes_'.$shift->get_id()]))));
					$shift->set_notes($note);
					update_dbShifts($shift);
				}
			}
			$mgr_note = trim(str_replace('\"','\\\"',str_replace('\'','\\\'',htmlentities($post['mgr_notes'.$i]))));
			$days[$i] = select_dbDates($days[$i]->get_id());
			$days[$i]->set_mgr_notes($mgr_note);
			update_dbDates($days[$i]);
		  }
	}
	
?>