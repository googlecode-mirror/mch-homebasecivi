<?PHP
	echo('<form method="post"><p><strong>Search for People</strong>');
	echo('<table><tr><td>First Name:</td><td><input type="text" name="s_first_name"></td></tr>');
	echo('<tr><td>Last Name:</td><td><input type="text" name="s_last_name"></td></tr>');
	echo('<tr><td>E-mail:</td><td><input type="text" name="s_email"></td></tr>');
	echo('<tr><td>Status:</td><td><select name="s_status">' .
			'<option value=""></option>' .
			'<option value="active">active</option>' .
			'<option value="LOA">LOA</option>' .
			'<option value="former">former</option>' .
			'</select></td></tr>');
    echo('<tr><td>Type:</td><td><select name="s_type">' .
			'<option value=""></option>' .
			'<option value="Rec">Reception</option>' .
			'<option value="Fam">Family room</option>' .
			'<option value="Van">Van driver</option>' .
			'<option value="Kit">Kitchen</option>' .
			'<option value="Off">Office</option>' .
			'<option value="Lin">Linens</option>' .
			'<option value="manager">manager</option>' .
			'<option value="App">applicant</option>' .
			'<option value="teen">teen</option>' .
			'<option value="committee">committee</option>' .
			'<option value="sub">sub</option>' .
			'<option value="trainee">trainee</option>' .
			'</select></td></tr>');
	echo('<tr><td colspan="2" align="left"><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search"></td></tr>');
	echo('</table></form></p>');
?>