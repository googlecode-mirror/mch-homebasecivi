 <p>
<table>
		<tr>
	    <td>Available persons for <?PHP echo substr($d,0,3) . " " . $t; ?>:
	    <br />
	    <select name="sel_available" >
	    <?PHP
	       while ($person = mysql_fetch_array($a, MYSQL_ASSOC)) {
	           echo " <option value='$person[id]' ";
	           if ($_POST['sel_available']==$person[id]) echo("SELECTED");
	           echo "> $person[first_name]" . " $person[last_name] </option>\n";
           }
	    ?>
        </select>

		</td>
		<td>
        Scheduled persons for Group <?PHP echo $g . " " . $d . " " . $t; ?>:
	    <br />
	    <select name="sel_scheduled" >
	    <?PHP
	       while ($person = mysql_fetch_array($s, MYSQL_ASSOC)) {
	           echo " <option value='$person[id]' ";
	           if ($_POST['sel_scheduled']==$person[id]) echo("SELECTED");
	           echo "> $person[first_name]" . " $person[last_name] </option>\n";
           }
	    ?>
        </select>
		</td>
		</tr>
</table>

