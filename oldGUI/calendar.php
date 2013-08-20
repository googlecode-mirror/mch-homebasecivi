<?PHP
	session_start();
	session_cache_expire(30);
?>
<!--
	calendar.php
	@author Max Palmer and Allen Tucker
	@version 3/26/08, revised 9/15/2008
-->
<html>
	<head>
		<title>
			Calendar display and edit form
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
		<link rel="stylesheet" href="calendar_1.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<span class="viewweek">
				<?php
                                        echo "we are here";
					include_once('database/dbWeeks.php');
					include_once('database/dbPersons.php');
					include_once('database/dbLog.php');
					
                                        include_once('calendar.inc');
                                        
					// checks to see if in edit mode
					$edit=$_GET['edit'];
					if($edit!="true")
						$edit=false;
					else
						$edit=true;
					// gets the week to show, if no week then defaults to current week
					$venue = $_GET['venue'];
					$weekid = $_GET['id'];
					if(!$weekid)
						$weekid=date("m-d-y",time());
					$week=get_dbWeeks($weekid); // get the week for this venue only
					echo ("We are back here at calendar.php after getting a week.<br>");
					// if invalid week or unpublished week and not a manager
					if (! $week instanceof Week || $week->get_status()=="unpublished" && $_SESSION['access_level']<2) {
						echo 'This week\'s calendar is not available for viewing. ';
						if ($_SESSION['access_level']>=2)
						   echo ('<a href="addWeek.php?archive=false"> <br> Manage weeks</a>');
					}
					else {
						// if notes were edited, processes notes
						if(array_key_exists('_submit_check_edit_notes', $_POST) && $_SESSION['access_level']>=2) {
							process_edit_notes($week,$venue,$_POST);
							$week=get_dbWeeks($weekid);
						}
						// shows the previous week / next week navigation
						$week_nav=do_week_nav($week,$edit,$venue);
						echo $week_nav;
						// prevents archived weeks from being edited by anyone
						if($week->get_status()=="archived")
							$edit=false;
						echo '<form method="POST">';
						    $days=$week->get_dates();
						    $year=date("Y",time());
							$doy=date("z",time())+1;
							show_week($days,$week,$edit,$year,$doy,$venue);
						    if ($edit==true && !($days[6]->get_year()<$year || ($days[6]->get_year()==$year && $days[6]->get_day_of_year()<$doy) ) && $_SESSION['access_level']>=2)
								echo "<p align=\"center\"><input type=\"submit\" value=\"Save changes to all notes\" name=\"submit\">";
						echo '</form>';
					}
				?>
				<?PHP include('footer.inc');?>
			</div>
		</div>
	</body>
</html>
<?PHP 
?>