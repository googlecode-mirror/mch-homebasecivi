<?PHP
 /*
 * Copyright 2013 by Brian Jacobel, Oliver Fisher, Simon Brooks and Allen Tucker.
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).

 * Based on previous work by Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 */
/*
 * 	personForm.inc
 *  shows a form for a person to be added or edited in the database
 * 	@author Oliver Radwan and Allen Tucker
 * 	@version 9/1/2008, revised 4/1/2012
 */

if ($_SESSION['access_level'] == 0) {
    echo('<p><strong>New Volunteer Application Form</strong><br />');
    echo('Please provide us with some basic information about yourself and your interests.' .
    '<br>When you are finished, hit <b>Submit</b> at the bottom of this page.');
} else if ($_SESSION['access_level'] == 1)
    if ($_SESSION['_id'] != $person->get_id()) {
        echo("<p id=\"error\">You do not have sufficient permissions to edit this user.</p>");
        include('footer.inc');
        echo('</div></div></body></html>');
        die();
    } else {
        echo('<p><strong>Edit Your Own Information</strong><br />');
        echo('Here you can edit your contact information in the database.' .
        '<br>When you are finished, hit <b>Submit</b> at the bottom of this page.');
    } else if ($_SESSION['access_level'] == 2)
    if ($id == 'new') {
        echo('<p><strong>Add a New Volunteer</strong><br />');
        echo('Here you can add a new volunteer into the database. </p>');
    } else {
        echo('<p><strong>Edit a Volunteer\'s Contact Information</strong><br />');
        echo('Here you can edit, delete, or change the password for a volunteer in the database.</p><p>');
    } else {
    echo("<p id=\"error\">You do not have sufficient permissions to add a new person to the database.</p>");
    include('footer.inc');
    echo('</div></div></body></html>');
    die();
}
?>
<form method="POST">
    <input type="hidden" name="old_id" value=<?PHP echo("\"" . $id . "\""); ?>>
    <input type="hidden" name="old_pass" value=<?PHP echo("\"" . $person->get_password() . "\""); ?>>
    <input type="hidden" name="_form_submit" value="1">
    <p>(<span style="font-size:x-small;color:FF0000">*</span> denotes required fields)
    <p>First Name<span style="font-size:x-small;color:FF0000">*</span>: 
    <?php
    if ($id=="new")
        echo '<input type="text" name="first_name" tabindex="1" value="'.$person->get_first_name().'">';
    else echo $person->get_first_name();
        ?>
    
    <br>Last Name<span style="font-size:x-small;color:FF0000">*</span>: <input type="text" name="last_name" tabindex="2" value="<?PHP echo($person->get_last_name()) ?>">
    <fieldset>
        <legend>Contact information:</legend>
        <p>Address<span style="font-size:x-small;color:FF0000">*</span>: <input type="text" name="address" tabindex="3" size=40 value="<?PHP echo($person->get_address()) ?>">
        City<span style="font-size:x-small;color:FF0000">*</span>: <input type="text" name="city" tabindex="4" value="<?PHP echo($person->get_city()) ?>">
        <p>State, Zip<span style="font-size:x-small;color:FF0000">*</span>:
            <select name="state" tabindex="5">
<?PHP
$states = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA",
    "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM",
    "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA",
    "WV", "WI", "WY");
foreach ($states as $st) {
    echo "<option value='" . $st . "' ";
    if ($person->get_state() == $st)
        echo("SELECTED");
    else if ($id == "new" && $st == "ME")
        echo("SELECTED");
    echo ">" . $st . "</option>";
}
?>
            </select>,
            <input type="text" name="zip" size="5" tabindex="6" value="<?PHP echo($person->get_zip()) ?>">
        <p>Phone<span style="font-size:x-small;color:FF0000">*</span>: 
        <?php 
        if ($id=="new")
            echo '<input type="text" name="phone1" MAXLENGTH=12 tabindex="7" value="'.phone_edit($person->get_phone1()).'">';
        else echo phone_edit($person->get_phone1());
        ?>
        <br>Alternate Phone:<input type="text" name="phone2" MAXLENGTH=12 tabindex="8" value="<?PHP echo(phone_edit($person->get_phone2())) ?>">
        <p>Email:<input type="text" name="email" tabindex="9" value="<?PHP echo($person->get_email()) ?>">
<?PHP
        echo '<p>Birthday:';
        $mm = substr($person->get_birthday(), 0, 2);
        $dd = substr($person->get_birthday(), 3, 2);
        $yy = substr($person->get_birthday(), 6, 2);
        select_date($mm, $dd, $yy, "DateOfBirth_Month", "DateOfBirth_Day", "DateOfBirth_Year");


    	echo "&nbsp;&nbsp;&nbsp;&nbsp;Start Date:";
    	$mm = substr($person->get_start_date(), 0, 2);
    	$dd = substr($person->get_start_date(), 3, 2);
    	$yy = substr($person->get_start_date(), 6, 2);
    	select_date($mm, $dd, $yy, "DateOfStart_Month", "DateOfStart_Day", "DateOfStart_Year");

        echo('<p>Notes:<br />');
        echo('<textarea name="notes" rows="2" cols="60">');
        echo($person->get_notes());
        echo('</textarea></fieldset>');


echo ('<p>Status:');
echo('<span style="font-size:x-small;color:FF0000">*</span>&nbsp;&nbsp;');
echo('<select name="status">');
if ($_SESSION['access_level'] == 0) {
	echo ('<option value="applicant"');
    echo (' SELECTED'); 
    echo('>applicant</option>');
}
else {
	
    echo ('<option value="active"');
	if ($person->get_status() == 'active')
	    echo (' SELECTED'); echo('>active</option>');
	echo ('<option value="LOA"');
	if ($person->get_status() == 'LOA')
	    echo (' SELECTED'); echo('>on leave</option>');
    echo ('<option value="applicant"');
    if ($person->get_status() == 'applicant')
        echo (' SELECTED'); 
    echo('>applicant</option>'); echo ('<option value="former"');
	if ($person->get_status() == 'former')
	    echo (' SELECTED'); echo('>former</option>');
}
echo('</select>');

$types = array('volunteer' => 'Volunteer', 'staff' => "Staff");
echo('<p>Position type:');
echo('<span style="font-size:x-small;color:FF0000">*</span>');
foreach ($types as $key => $value) {
    if($person->get_type() == $key)
        echo ('&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type[]" value="' . $key . '" checked>'. $value . '</input>');
    else
        echo ('&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type[]" value="' . $key . '">'. $value . '</input>');
}

$groups= array('foodbank' => "Food Bank", 'foodpantry' => "Food Pantry", 'soupkitchen' => 'Soup Kitchen', );
echo('<p>Group(s):');
//echo('<span style="font-size:x-small;color:FF0000">*</span>');
foreach ($groups as $key => $value) {
    if(in_array($key, $person->get_group()))
        echo ('&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="group[]" value="' . $key . '" checked>'. $value . '</input>');
    else {
        echo ('&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="group[]" value="' . $key . '">'. $value . '</input>');
    }
}

$roles = array('CC' => 'Crew Chief/Chef', 
				'B' => "Boxes", 'DD' => "Delivery Driver", 'P' => "Produce",             // Food Bank specific
				'PR' => "Prep", 'I' => 'Intake', 'M' => "Meat", 'CO' => "Carryout",      // Food Pantry specific
				'CH' => "Chef Helper", 'Pots' => "Pots", 'Dishes' => "Dishes");          // Soup Kitchen specific
echo('<p>Role(s):&nbsp;');
//echo('<span style="font-size:x-small;color:FF0000">*</span>');
$i=1;
foreach ($roles as $key => $value) {
    if ($i==2) echo "<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Food Bank: &nbsp;&nbsp;";
    else if ($i==5) echo "<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Food Pantry: &nbsp;&nbsp;";
    else if ($i==9) echo "<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Soup Kitchen: &nbsp;&nbsp;";
 //   else echo "&nbsp;&nbsp;&nbsp;&nbsp";
    if(in_array($key, $person->get_role()))
        echo ('<input type="checkbox" name="role[]" value="' . $key . '" checked>'. $value . ' ('.$key.') </input>&nbsp;&nbsp;&nbsp;&nbsp;');
    else
        echo ('<input type="checkbox" name="role[]" value="' . $key . '">'. $value . ' ('.$key.') </input>&nbsp;&nbsp;&nbsp;&nbsp;');
    $i++;
}
?>
    <fieldset id='availability'>
        <legend>Availability:<span style="font-size:x-small;color:FF0000">*</span> </legend>
        <span class="bigTable">
            <table>
                <?PHP
                $shifts = array(1 => "First Week", 2 => "Second Week", 3 => "Third Week", 4 => "Fourth Week", 5 => "Fifth Week");
                $days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                $person_availability = implode(',', $person->get_availability());
    			echo "<table>".
					"<tr><td> &nbsp;&nbsp;</td><td>Mon&nbsp;&nbsp;</td><td>Tue&nbsp;&nbsp;</td><td>Wed&nbsp;&nbsp;</td>".
					"<td>Thu&nbsp;&nbsp;</td><td>Fri&nbsp;&nbsp;</td><td>Sat&nbsp;&nbsp;</td><td>Sun</td></tr>";
    			foreach ($shifts as $weekno => $weekvalue) {
       				echo ('<tr><td>'. $weekvalue . '</td>');
       				foreach ($days as $day) {
       	  				$realkey = $day . ":". $weekno;
       	  				echo ('<td><input type="checkbox" name="availability[]" value=' . $realkey);
    	  				if (in_array($day.":".$weekno, $person->get_availability())) echo(' CHECKED');
    	  				echo ('></td>');
       				}
       				echo ('</tr>');
    			}
   		 		echo "</table><p>";
    			?>
            </table>
        </span></p>
    </fieldset>
    <p>
        <?PHP

        echo('<input type="hidden" name="schedule" value="' . implode(',', $person->get_schedule()) . '">');
        echo('<input type="hidden" name="password" value="' . $person->get_password() . '">');
        echo('<input type="hidden" name="_submit_check" value="1"><p>');
        if ($_SESSION['access_level'] == 0)
            echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to complete this application.<br /><br />');
        else
            echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to submit these edits.<br /><br />');
        if ($id != 'new' && $_SESSION['access_level'] >= 2) {
            echo ('<input type="checkbox" name="deleteMe" value="DELETE"> Check this box and then hit ' .
            '<input type="submit" value="Delete" name="Delete Entry"> to delete this entry. <br />' .
            '<input type="checkbox" name="reset_pass" value="RESET"> Check this box and then hit ' .
            '<input type="submit" value="Reset Password" name="Reset Password"> to reset this person\'s password.</p>');
        }

        function select_date($month, $day, $year, $month_name, $day_name, $year_name) {
            $months = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
            echo('<select name=' . $month_name . '><option value=""></option>');
            echo('');
            for ($i = 1; $i <= 12; $i++) {
                echo '<option value=' . (($i < 10) ? "0" . $i : $i);
                if ($month == $i)
                    echo(' SELECTED');
                echo '>' . $months[$i] . ' </option>';
            }
            echo "</select>";

            echo '<select name=' . $day_name . '><option value=""></option>';
            for ($i = 1; $i <= 31; $i++) {
                echo '<option value=' . (($i < 10) ? "0" . $i : $i);
                if ($day == $i)
                    echo(' SELECTED');
                echo '>' . $i . ' </option>';
            }
            echo "</select>";
            // handles a year range of 81 years, from today+1 to today-80
            echo '<select name=' . $year_name . '><option value=""></option>';
            $start_year = date("Y")+1;
            for ($i = $start_year; $i >= ($start_year - 90); $i--) {
                echo '<option value=' . substr($i, 2, 2);
                if ($year == substr($i, 2, 2))
                    echo(' SELECTED');
                echo '>' . $i . ' </option>';
            }
            echo "</select>";
        }
        ?>
</form>

