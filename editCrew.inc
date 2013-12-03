<?php
/*
 * Created on Oct 24, 2013
 * @author Allen Tucker
 */
	function do_slot_num($slots) {
		if($slots==1)
			return "1 slot for this crew:";
		return $slots." slots for this crew:";
	}

	function display_filled_slots($persons) {
		$s="";
		if(!$persons[0])
			array_shift($persons);
		for($i=0;$i<count($persons);++$i) {
			$p=explode("+",$persons[$i]);
			$s=$s."<tr><td width=\"150\" valign=\"top\"><br>&nbsp;".$p[1]." ".$p[2];
			$s = $s."</td><td><form method=\"POST\" style=\"margin-bottom:0;\">
				<input type=\"hidden\" name=\"_submit_filled_slot_".$i."\" value=\"1\"><br>
				<input type=\"submit\" value=\"Remove Person\" name=\"submit\">
			</form></td><td></td><td></td></tr>";
		}
		return $s."<br>";
	}

	function display_vacant_slots($vacancies) {
		$s="";
		for($i=0;$i<$vacancies;++$i) {
			$s=$s."<tr><td width=\"150\" valign=\"top\">&nbsp;<b>vacancy</b></td><td>
				<form method=\"POST\" style=\"margin-bottom:0;\">
				<input type=\"hidden\" name=\"_submit_fill_vacancy\" value=\"1\">
				<input type=\"submit\" value=\"Assign Person\" name=\"submit\"></form>";
			if($_SESSION['access_level']>=2) {
 				$s=$s."</td><td><form method=\"POST\" style=\"margin-bottom:0;\">
 				<input type=\"hidden\" name=\"_submit_ignore_vacancy\" value=\"1\">
				<input type=\"submit\" value=\"Remove Slot\" name=\"submit\"></form>";
			}
			$s=$s."</td><td></td></tr>";
		}
		return $s;
	}
	function process_fill_vacancy($post,$crew,$group) {
		if(!array_key_exists('_submit_fill_vacancy',$post))
			return false;
		$crewid=$crew->get_id();
		echo "<br><br><table align=\"center\" border=\"1px\"><tr><td align=\"center\" width=\"400\"><b>"
			.get_crew_name_from_id($crewid)."</b></td></tr>
		<tr><td align=\"center\"><form method=\"POST\" style=\"margin-bottom:0;\">
			<select name=\"scheduled_vol\">
			<option value=\"0\">Select a volunteer available for this day </option>"
			.get_available_volunteer_options($crew->get_date(),$crew->get_persons(),$group).
			"</select><br>
		</td></tr><tr><td align=\"center\">	<strong>or</strong><br>
		</td></tr><tr><td align=\"center\">	<select name=\"all_vol\">
			<option value=\"0\">Select from all volunteers for this group</option>"
			.get_all_volunteer_options($crew->get_persons(),$group).
			"</select><br><br>
			<input type=\"hidden\" name=\"_submit_add_volunteer\" value=\"1\">
			<input type=\"submit\" value=\"Add Volunteer\" name=\"submit\">
			</form></td></tr>";
		echo "</table>";
		echo "<table align=\"center\"><tr><td align=\"center\" width=\"400\">
		&nbsp;<a href=\"editCrew.php?id=".$crewid."\">Back to Crew</a><br></td></tr></table>";
		return true;
		// check that person is not already working that crew
		// check that person is available
	}

	function process_add_volunteer($post,$crew,$group) {
		if(!array_key_exists('_submit_add_volunteer',$post))
			return false;
		if($post['all_vol']=="0" && $post['scheduled_vol']=="0")
			$error="<table align=\"center\"><tr><td width=\"400\">
				You must select a volunteer from one of the menus.</td></tr></table><br>";
		else if($post['all_vol']!="0" && $post['scheduled_vol']!="0")
			$error="<table align=\"center\"><tr><td width=\"400\">
				You have selected a volunteer from both menus.  Please choose only one.</td></tr></table><br>";
		else {
			$v=$post['scheduled_vol'];
			if ($v=="0")
				$v=$post['all_vol'];  // one or the other must have been selected, so assign it to $p
			$p = retrieve_person($v);
			$persons=$crew->get_persons();
		}
		if($error) {
			echo $error;
			return true;
		}
		else {
			$persons[]=$p->get_id() . "+" . $p->get_first_name() . "+" . 
									$p->get_last_name() . "+(" . implode(' ',$p->get_role()) . ")";
			$crew->assign_persons($persons);
			update_dbCrews($crew);
			$name=explode("+",$vol);
			add_log_entry('<a href=\"personEdit.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.
			    $_SESSION['l_name'].'</a> assigned <a href=\"personEdit.php?id='.$name[0].'\">'.$name[1].' '.$name[2].'</a> to the crew: <a href=\"editCrew.php?crew='.
			    $crew->get_id().'\">'.get_crew_name_from_id($crew->get_id()).'</a>.');
			return false;
		}
	}

	function process_unfill_crew($post,$crew,$group) {
		$persons=$crew->get_persons();
		if(!$persons[0])
			array_shift($persons);
		for($i=0;$i<count($persons);++$i) {
			$p2 = array();
			if(array_key_exists('_submit_filled_slot_'.$i, $post)) {
				for($j=0;$j<count($persons);++$j) {
					if($i!=$j)
						$p2[]=$persons[$j];
					else
						$name=$persons[$j];
				}
				if(count($p2)==0)
					$p2=array();
				$crew->assign_persons($p2);
				if($name)
					$name=explode("+",$name);
				update_dbCrews($crew);
				add_log_entry('<a href=\"personEdit.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.
				    $_SESSION['l_name'].'</a> removed <a href=\"personEdit.php?id='.$name[0].'\">'.$name[1].' '.$name[2].'</a> from the crew: <a href=\"editCrew.php?crew='.
				    $crew->get_id().'\">'.get_crew_name_from_id($crew->get_id()).'</a>.');
				return false;
			}
		}
	}
	function process_add_slot($post,$crew,$group) {
		if(array_key_exists('_submit_add_slot',$post)) {
			$crew->add_slot();
			update_dbCrews($crew);
			add_log_entry('<a href=\"personEdit.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.
			    $_SESSION['l_name'].'</a> added a slot to the crew: <a href=\"editCrew.php?crew='.$crew->get_id().'\">'.
			    get_crew_name_from_id($crew->get_id()).'</a>.');
			return true;
		}
		return false;
	}
	function process_clear_crew($post,$crew,$group) {
		if(array_key_exists('_submit_clear_crew',$post)) {
			$crew = new Crew($crew->get_date(),$crew->get_group(),$crew->get_slots(), null, "", "");
			update_dbCrews($crew);
			add_log_entry('<a href=\"personEdit.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.
			    $_SESSION['l_name'].'</a> cleared the crew: <a href=\"editCrew.php?crew='.$crew->get_id().'\">'.
			    get_crew_name_from_id($crew->get_id()).'</a>.');
			return true;
		}
		return false;
	}
    function process_ignore_slot($post,$crew,$group) {
		if(array_key_exists('_submit_ignore_vacancy',$post)) {
			$crew->remove_slot();
			update_dbCrews($crew);
			add_log_entry('<a href=\"personEdit.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.
			    $_SESSION['l_name'].'</a> ignored a vacant slot for the crew: <a href=\"editCrew.php?crew='.$crew->get_id().'\">'.
			    get_crew_name_from_id($crew->get_id()).'</a>.');
			return true;
		}
		return false;
	}

	function get_available_volunteer_options($day,$persons,$group){
		if(!$persons[0])
			array_shift($persons);
		
		$personsfixed = array();
		foreach ($persons as $person) {
			$pex = explode("+", $person);
			$person = $pex[0];
			array_push($personsfixed, $person);
		}

		$result = getall_availablegroup("volunteer", $day, $group);
		$s="";
		for($i=0;$i<mysql_num_rows($result);++$i) {
			$row=mysql_fetch_row($result);
			$value=$row[0]; //."+".$row[1]."+".$row[2];
			$label=$row[2].", ".$row[1] ." ".$row[12];

			if(!in_array($value, $personsfixed)) {
				$s=$s."<option value=\"".$value."\">".$label."</option>";
				$match=false;
			}
		}
		return $s;
	}

	function get_all_volunteer_options($persons,$group) {
		if(!$persons[0])
			array_shift($persons);

		$personsfixed = array();
		foreach ($persons as $person) {
			$pex = explode("+", $person);
			$person = $pex[0];
			array_push($personsfixed, $person);
		}

		$result = getall_typegroup("volunteer",$group);
		$s="";
		for($i=0;$i<mysql_num_rows($result);$i++) {
		  	$row=mysql_fetch_row($result);
			$value=$row[0]; //."+".$row[1]."+".$row[2];
			$label=$row[2].", ".$row[1] ." ".$row[12];

			if(!in_array($value, $personsfixed)) {
				$s=$s."<option value=\"".$value."\">".$label."</option>";
				$match=false;
			}
		}
		return $s;
	}

	function get_crew_name_from_id2($crewid) {
		$yy_mm_dd = explode("-",$crewid);
		$groups = array("foodbank"=>"Food Bank", "foodpantry"=>"Food Pantry", "soupkitchen"=>"Soup Kitchen");
		return date("l M j, Y", mktime(0,0,0,$yy_mm_dd[1],$yy_mm_dd[2],$yy_mm_dd[0])) . " " . $groups[$yy_mm_dd[3]]. " Crew";
		
	}
?>
