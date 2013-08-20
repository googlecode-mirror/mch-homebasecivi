<?PHP
	session_start();
	session_cache_expire(30);
?>
<!--
	viewWeek.php
	@author Max Palmer
	@version 3/26/08
-->
<html>
	<head>
		<title>
			Master Schedule
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" /> 
		<link rel="stylesheet" href="calendar.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
				$venue = $_GET['venue'];
                $venuegroups = array ("Rec" => 2, "Fam" => 2, "Van" => 2, "Kit" => 2, 
		               "Off" => 2, "Lin" => 2, "Chr" => 2, "Dup" => 2);
				if($_SESSION['access_level']<2) {
					die("<p>Only managers can view the master schedule.</p>");
				}
				echo ('<span class="viewweek">');
				include_once('database/dbSchedules.php');
					echo "<br><br>";
				show_master_week($venue, "One");
					echo "<br><br>";
				if ($venuegroups[$venue] == 2)
				    show_master_week($venue, "Two");
			    echo "<br><br>";
				?>
				<?PHP include('footer.inc');?>
			</div>
		</div>
	</body>
</html>

<?php
	/**
	* displays the master schedule for a given venue and group
	*/
	function show_master_week($venue, $group) {
		// gets all of the dates for this week
		// sets up the table, with title, and then day of month
		$venuename = name_venue($venue);
		$daynames = array ("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		$days = array ("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
		echo ('<table id="calendar" align="center" ><tr class="weekname"><td colspan="9" ' .
				'bgcolor="#99B1D1" align="center" >'.$venuename.' Master Schedule');
		echo (': Group '.$group);		
		echo ('</td></tr><td bgcolor="#99B1D1">  </td>');
		for($i=0; $i<7; $i++)
			echo ('<td class="dow" align="center"> '. $daynames[$i] .' </td>');
		echo('<td bgcolor="#99B1D1"></td></tr>');
		$free_hour = array();
		for ($i=0; $i<84; $i++)
		    $free_hour[] = true;
		if ($venue == "Chr") $stop = 20; else $stop = 21;
		for ($hour=9; $hour < $stop; $hour++) {
			echo ("<tr><td class=\"masterhour\">".show_hours($venue,$hour)."</td>");
			for ($i=0; $i<7; $i++){
				$master_shift = get_master_shift($venue, $group, $days[$i], $hour);
				/* retrieves an associative array of (venue, my_group, day, time, 
				 * start, end, slots, persons, notes) */ 
				if ($master_shift) {
					$shift_length = $master_shift['end'] - $master_shift['start'];
					echo do_shift($master_shift, $shift_length);
					for ($j=$hour; $j<$hour+$shift_length; $j++)
					    $free_hour[7*($j-9)+$i] = false;
				}
				else if ($free_hour[7*($hour-9)+$i]) {
					$t = $hour . "-" . ($hour+1);
					$master_shift = array("venue"=>$venue,"my_group"=>$group,"day"=>$days[$i], 
						"time"=>$t, "slots"=>0,
						"persons"=>null, "notes"=>null, "start"=>$hour, "end"=>$hour+1);
				    echo do_shift($master_shift, 0);
				}
			}
			echo ("<td class=\"masterhour\">".show_hours($venue,$hour)."</td></tr>");
		}

        
		echo "</table>";
	}
	
	function show_hours($venue, $hour) {
		if ($venue == "Chr") $d = 2; else $d = 3;
		$clock = $hour < 12 ? $hour . "am"  : $hour-12 . "pm";
		$clockend = $hour+$d<12?($hour+$d) . "am" : ($hour-12+$d) . "pm";
		if ($clock == "0pm") $clock = "12pm";
		if ($clockend == "0pm") $clockend = "12pm";
		if (($clock) % $d == 0 )
		   return $clock . " - " . $clockend;
		else return "";
	}
	
	function name_venue ($venue) {
		$venuenames = array ("Rec" => "Reception", "Fam" => "Family Room", "Van" => "Van", "Kit" => "Kitchen", 
		      "Off" => "Office", "Lin" => "Linens", "Chr" => "Christiana Room", "Dup" => "AI duPont Room");
		return $venuenames[$venue];
	}
	
	function do_shift($master_shift, $master_shift_length) {
		/* $master_shift is an associative array of (venue, my_group, day, time, 
				 * start, end, slots, persons, notes) */
		if ($master_shift_length==0){
			$s = "<td bgcolor=\"darkgray\" rowspan='".$master_shift_length."'>" .
				"<a id=\"shiftlink\" href=\"editMasterSchedule.php?group=".
				$master_shift['my_group']."&day=".$master_shift['day']."&shift=".
				$master_shift['time']."&venue=".$master_shift['venue']."\">".
				"<br>". 
			"</td>";
		}
		else if ($master_shift['slots']==0) {
			$s = "<td rowspan='".$master_shift_length."'>" .
				"<a id=\"shiftlink\" href=\"editMasterSchedule.php?group=".
				$master_shift['my_group']."&day=".$master_shift['day']."&shift=".
				$master_shift['time']."&venue=".$master_shift['venue']."\">".
				"<br>". 
			"</td>";
		}
		else {
			$s= "<td rowspan='".$master_shift_length."'>".
				"<a id=\"shiftlink\" href=\"editMasterSchedule.php?group=".
				$master_shift['my_group']."&day=".$master_shift['day']."&shift=".
				$master_shift['time']."&venue=".$master_shift['venue']."\">".
				get_people_for_shift($master_shift, $master_shift_length).
			"</td>";
		}
		return $s;
	}
	
	function get_people_for_shift($master_shift, $master_shift_length) {
		/* $master_shift is an associative array of (venue, my_group, day, time, 
				 * start, end, slots, persons, notes) */
		$people=get_persons($master_shift['venue'], $master_shift['my_group'],
							$master_shift['day'],$master_shift['time']);
		$slots=get_total_slots($master_shift['venue'], $master_shift['my_group'],
							$master_shift['day'],$master_shift['time']);
		if(!$people[0])
			array_shift($people);
		$p="<br>";
		for($i=0;$i<count($people);++$i) {
			if (is_array($people[$i]))
			   $p = $p."&nbsp;".$people[$i]['first_name']." ".$people[$i]['last_name']."<br>";
			else
			   $p = $p."&nbsp;".$people[$i]."<br>";
		}
		if($slots-count($people)>0 )
			$p=$p."&nbsp;<b>Vacancies (".($slots-count($people)).")</b><br>";
		else if (count($people) == 0)
		    $p=$p."&nbsp;<br>";
		return substr($p,0,strlen($p)-4) ;
	}
	

	?>