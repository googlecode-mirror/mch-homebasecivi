<p><strong>Edit Master Schedule</strong>
  <p>
        Select a Group:
		<select name="group">
<?PHP
           $groups = array('','One','Two','Wkd');
           for ($i = 0; $i <= count($groups); $i++) {
           	   echo " <option value='$groups[$i]' ";
	           if ($_POST['group'] == $groups[$i]) { echo("SELECTED"); $g = $groups[$i]; }
	           echo "> $groups[$i] </option>" ;
           }
?>
		</select>
		 a Day:
		<select name="day">
<?PHP
           $days = array('','Mon','Tue','Wed','Thu','Fri','Sat1','Sun1','Sat2','Sun2','Sat3','Sun3','Sat4','Sun4','Sat5','Sun5');
           for ($i = 0; $i <= count($days); $i++) {
           	    echo " <option value='$days[$i]' ";
	           if ($_POST['day'] == $days[$i]) { echo("SELECTED"); $d = $days[$i]; }
	           echo "> $days[$i] </option>" ;
           }
?>
		</select>
		and a Time:
		<select name="time">
<?PHP
           $times = array('','9-12','12-3','3-6','6-9','10-1','1-4','2-5','5-9');
           for ($i = 0; $i <= count($times); $i++) {
           	     echo " <option value='$times[$i]' ";
	           if ($_POST['time'] == $times[$i]) { echo("SELECTED"); $t = $times[$i]; }
	           echo "> $times[$i] </option>" ;
           }
?>
	    </select>

