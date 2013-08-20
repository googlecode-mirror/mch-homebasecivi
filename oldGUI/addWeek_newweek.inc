<p>
	<strong>Calendar Week Management</strong><br />
	Here you can add new weeks to the calendar and you can edit weeks that are already there. <br>Click the "Add new week" button for adding a new week, or select an option at the right of an existing week.</p>
	<span class="nextWeekTable">
		<form method="POST">
		<p><table>
		<tr><td>Weeks</td><td>Group</td><td colspan="2">Status (options)</td></tr>
			<?php
			$result=get_all_dbWeeks();
			for($i=0;$i<mysql_num_rows($result);++$i){
				$week=mysql_fetch_row($result);
                if ($i==mysql_num_rows($result)-1 || $i==0)
                   $remove = true;
                else $remove = false;
		//		if(($week[3]=="archived" && $_GET['archive']=="true") || $week[3]=="published" || 
		//		   ($week[3]=="unpublished" && $_SESSION['access_level']>=2))
				echo ("<tr><td>".$week[4]."</td><td>".$week[2]."</td>" .
					      "</td><td colspan=\"2\">".show_week_options($week, $remove) . "</td></tr>");
			}
			echo ('<tr> <td colspan="2">');
			// finds the parameters for "generate next week" button
			if($_SESSION['access_level']>=2) {
					// finds the last week, and calculates next week's groups
					$last=$week[0];
					if ($week[2]=="One")
					    $group="Two";
					else
						$group="One";
					$new_week_timestamp = mktime(0,0,0,substr($last,0,2),substr($last,3,2)+7,substr($last,6,2));
					echo "<input type=\"hidden\" name=\"_new_week_timestamp\" value=\"".$new_week_timestamp."\">
								<input type=\"hidden\" name=\"_submit_check_newweek\" value=\"1\">
								<input type=\"submit\" value=\"Add new week\" name=\"Add new week\">";
            if ($firstweek) {
					echo ('&nbsp;&nbsp;   Month <select name="month">');
			   $months = array('SELECT','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			   $today=date("M");
			   for($i=0;$i<=12;++$i) {
				echo ("<option value=\"".$i."\"");
				if($_POST['month']==$i || $today==$months[$i])
					echo (" SELECTED");
				echo (">".$months[$i]."</option>");
			   }
			   echo ('</select> Day <select name="day"> <option value="0">SELECT</option>');
			   $today=date("j");
			   for ($i=1;$i<=31;++$i) {
				  echo ("<option ");
				  if ($_POST['day']==$i || $today==$i)
					 echo (" SELECTED");
				echo (">".$i."</option>");
			   }
			   echo ('</select> Year <select name="year"> <option value="0">SELECT</option>');
			   $i=date("Y");
			   echo ("<option ");
			   if($_POST['year']==$i || $_POST['year']!=$i+1)
					echo (" SELECTED");
			   echo (">".$i."</option>");
			   echo ("<option ");
			   if($_POST['year']==$i+1)
					echo (" SELECTED");
			   echo (">".($i+1)."</option>");
			   echo ("</select>");
            }
			echo "<br> &nbsp; with Group <select name=\"group\">";
			// the group option menus, expected groups selected by default
			        $groups = array("One", "Two");
					foreach ($groups as $i) {
						echo "<option value=\"".$i."\"";
						if($i==$group)
							echo " SELECTED";
						echo ">".$i."</option>";
					}
					echo "</select>";
				}
				else
					echo "&nbsp;</td>";

            echo('<td align="center"><a href="addWeek.php?archive=');
			   if($_GET['archive']=="true")
					echo "false";
			   else
					echo "true";
			echo('">');
			   if($_GET['archive']=="true")
					echo "Hide";
			   else
					echo "View";
			echo(' Archive</a></td>');

            // determines what options apply to each week
			function show_week_options($week, $remove) {
				$id=$week[0];
				$status=$week[3];
				$end=$week[5];
				$options="";
				$rightnow = time(); $oneweek = 60*60*24*7;
				if ($status=="archived") {
					$options="archived (<a href=\"calendar.php?id=".$week[0]."&venue=Rec\">view</a>)";
				}
				// see if right now is part of a new week.  If so, archive the previous week.
				// here was a BIG BUG -- today might not have an immediate predecessor week in the database,
				// in which case "week2" below will be null!  So this now accommodates gaps (missing weeks) in the dbWeeks table.
				else if ($rightnow>$end) {
					$options="archived (<a href=\"calendar.php?id=".$week[0]."&venue=Rec\">view</a>)";
					$week2=get_dbWeeks($week[0]);
					if ($week2!=null) {
					    $week2->set_status("archived");
						update_dbWeeks($week2);
					}
				}
				else if ($status=="unpublished") {
					$options="unpublished 
							(<a href=\"calendar.php?id=".$week[0]."&venue=Rec&edit=true\">edit</a>)
							(<a href=\"addWeek.php?publish=".$week[0]."\">publish</a>)
							(<a href=\"calendar.php?id=".$week[0]."&venue=Rec\">view</a>)";
				}
				else { // status must be "published"
					$options="published (<a href=\"calendar.php?id=".$week[0]."&venue=Rec&edit=true\">edit</a>)
							(<a href=\"addWeek.php?publish=".$week[0]."\">unpublish</a>)
							(<a href=\"calendar.php?id=".$week[0]."&venue=Rec\">view</a>)";
				}
				if ($remove)
					$options = $options . " (<a href=\"addWeek.php?remove=".$week[0]."\">remove</a>)";
				return $options;
			}
			?>
			</tr>
		</table></p>
		</form></span>
