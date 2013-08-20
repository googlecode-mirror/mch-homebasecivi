<?PHP
	session_start();
	session_cache_expire(30);
?>
<!--
	addWeek.php
	@author Max Palmer and Allen Tucker
	@version 3/25/08, revised 9/10/08
-->
<html>
	<head>
		<title>
			Add Weeks to Calendar
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
					include_once('database/dbWeeks.php');
					//rename dbMasterSchedule
					include_once('database/dbSchedules.php');
					include_once('database/dbPersons.php');
					include_once('database/dbLog.php');
					// Check to see if there are already weeks in the db
					// connects to the database to see if there are any weeks in the dbWeeks table
				    $result=get_all_dbWeeks();
					// If no weeks for either the house or the family room, show first week form
					if(mysql_num_rows($result)==0)
						$firstweek = true;
					else
						$firstweek = false;
							
						// publishes a week if the user is a manager
					if($_GET['publish'] && $_SESSION['access_level']>=2) {
							$id=$_GET['publish'];
							$week=get_dbWeeks($id);
							if ($week->get_status() == "unpublished")
							   $week->set_status("published");
							else if ($week->get_status() == "published")
							   $week->set_status("unpublished");
							update_dbWeeks($week);
							add_log_entry('<a href=\"viewPerson.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.$_SESSION['l_name'].'</a> ' .
									$week->get_status().' the week of <a href=\"calendar.php?id='.$week->get_id().'&edit=true\">'.$week->get_name().'</a>.');
							echo "<p>Week \"".$week->get_name()."\" " .
									$week->get_status() . ".<br><a href=\"addWeek.php\">Back</a>";
						}
					
					else if ($_GET['remove'] && $_SESSION['access_level']>=2) {
							$id=$_GET['remove'];
							$week=get_dbWeeks($id);
							if ($week->get_status()=="unpublished" || $week->get_status()=="archived") {
							   delete_dbWeeks($week);
							   add_log_entry('<a href=\"viewPerson.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.$_SESSION['l_name'].'</a> removed the week of <a href=\"calendar.php?id='.$week->get_id().'&edit=true\">'.$week->get_name().'</a>.');
							   echo "<p>Week \"".$week->get_name()."\" removed.<br><a href=\"addWeek.php\">Back</a>";
							}
							else echo "<p>Week \"".$week->get_name()."\" is published, so it cannot be removed.<br><a href=\"addWeek.php\">Back</a>";
						}
					else if(!array_key_exists('_submit_check_newweek', $_POST)) {
							include('addWeek_newweek.inc');
						}
						else {
							$errors = validate_form($firstweek);
							if($errors)
								show_errors($errors);
							else
								process_form ($firstweek);
						 	include('addWeek_newweek.inc');
						}

					// checks that all of the entries are valid to create new week
					// returns errors
					function validate_form ($firstweek) {
						if (!$firstweek)
							return;

						if($_POST['month']==0)
							$error[]="Please select a month";
						if($_POST['day']==0)
							$error[]="Please select a day";
						if($_POST['year']==0)
							$error[]="Please select a year";
						if($_POST['month']!=0 && $_POST['day']!=0 && $_POST['year']!=0) {
							if(! checkdate($_POST['month'], $_POST['day'], $_POST['year']))
								$error[]="Invalid date";
						}
						return $error;
					}

					// must be a manager
					function process_form ($firstweek) {
						if($_SESSION['access_level']<2)
							return null;
						if ($firstweek==true) {
							//find the beginning of the week
							$timestamp = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);
							$dow = date("N",$timestamp);
							$m=date("m",mktime(0,0,0,$_POST['month'],$_POST['day']-$dow+1,$_POST['year']));
							$d=date("d",mktime(0,0,0,$_POST['month'],$_POST['day']-$dow+1,$_POST['year']));
							$y=date("y",mktime(0,0,0,$_POST['month'],$_POST['day']-$dow+1,$_POST['year']));
						}
						else {
							$timestamp = $_POST['_new_week_timestamp'];
							$m=date("m",$timestamp);
							$d=date("d",$timestamp);
							$y=date("y",$timestamp);
						}
						// create the week
						generate_populate_and_save_new_week($m,$d,$y,$_POST['group']);
					}
					// uses the master schedule to create weeks
					function generate_populate_and_save_new_week($m,$d,$y,$group) {
						// set the group names the format used by master schedule
						$venues = array ("Rec", "Fam", "Van", "Kit", "Off", "Lin", "Chr", "Dup");
						$days = array ("Mon","Tue","Wed","Thu","Fri", "Sat", "Sun");
					    $day_id=$m."-".$d."-".$y;
						foreach ($days as $day) {
					     // makes a new date with these shifts
						 $dates[]=new RMHdate($day_id,$shifts,"");
						 $d++;
						 $day_id=date("m-d-y",mktime(0,0,0,$m,$d,$y));
						 $shifts = null;  
						 foreach ($venues as $venue) {
						 	// creates a new week from the dates
							$newweek=new Week($dates,$venue, $group,"unpublished");
							insert_dbWeeks($newweek);
							add_log_entry('<a href=\"viewPerson.php?id='.$_SESSION['_id'].'\">'.$_SESSION['f_name'].' '.$_SESSION['l_name'].'</a> generated a new week: <a href=\"calendar.php?id='.$newweek->get_id().'&edit=true\">'.$newweek->get_name().'</a>.');
						 	$my_group = $group;
						 	$venue_shifts = get_master_shifts($venue,$my_group,$day);
						   /* Each row in the array is an associative array
     					     *    of (venue, my_group, day, time, start, end, slots, persons, notes)
     					     *    and persons is a comma-separated string of ids, like "alex2077291234"
						     */
						 	if ($venue_shifts)
						 	  foreach ($venue_shifts as $venue_shift){
						        $shifts[]=generate_and_populate_shift($day_id,$venue,$my_group,$day,$venue_shift['time'],"");
						 	  }
						 }
						 
						}
						
					}

					// makes new shifts, fills from master schedule
					function generate_and_populate_shift($day_id,$venue,$group,$day,$time,$note) {
						// gets the people from the master schedule
						$people=get_person_ids($venue,$group,$day,$time);
						if(!$people[0])
							array_shift($people);
						// changes the people array to the format used by Shift (id, fname lname)
						for($i=0;$i<count($people);++$i) {
							$person=retrieve_person($people[$i]);
							if($person) 
								$people[$i]=$person->get_id()."+".$person->get_first_name()."+".$person->get_last_name();
						}
						// calculates vacancies
						$vacancies=get_total_slots($venue,$group,$day,$time)-count($people);
						// makes a new shift filled with people found above
						return new Shift($day_id."-".$time.$venue,$venue,$vacancies,$people,"",$note);
					}

					// displays form errors (only for first week)
					function show_errors($e) {
						//this function should display all of our errors.
						echo("<p><ul>");
						foreach($e as $error){
							echo("<li><strong><font color=\"red\">".$error."</font></strong></li>\n");
						}
						echo("</ul></p>");
					}

				?>
				<?PHP include('footer.inc');?>
			</div>
		</div>
	</body>
</html>