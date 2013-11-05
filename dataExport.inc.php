<?php
/*
 * Copyright 2012 by Johnny Coster, Jackson Moniaga, Judy Yang, and
 * Allen Tucker.  This program is part of RMH Homebase. RMH Homebase
 * is free software.  It comes with absolutely no warranty. You can
 * redistribute it and/or modify it under the terms of the GNU General
 * Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */
/*
 * 	dataExport.inc.php
 *   asks which attributes to export to CSV
 * 	@author Johnny Coster
 * 	@version 4/2/2012
 */
?>

<head>
    <style type="text/css">
        td {padding-bottom: 8px; padding-left: 40px;}
    </style>
</head>
<?php
session_start();
session_cache_expire(30);
$checked_array = $_SESSION['checked'];
echo('<body onload="');
foreach ($checked_array as $check_num) {
    echo('document.getElementById(\'e_check' . $check_num . '\').checked=true;');
}
echo('">');
?>

<form name="export_data" method="post">
    <input type="hidden" name="_form_submit" value="3" />
    <p style="margin-left:20px"><b>Step 2. Select the attributes to be exported for these people, and then hit 'Export to CSV':</h4></b>
    <?php
    $select_list = "";
    foreach ($select_people_array as $dude) {
        $select_list .= $dude . ", ";
    }
    echo('<p style="font-size:15px;margin-left:20px">' . substr($select_list, 0, -2) . '</p>');
    ?>
    <table>
        <td valign="top"><table>
                <td><input type="checkbox" id="e_check1" name="e_check1" /> First Name </td>
                <tr><td><input type="checkbox" id="e_check2" name="e_check2" /> Last Name </td></tr>
                <tr><td><input type="checkbox" id="e_check3" name="e_check3"/> Gender </td></tr>
                <tr><td><input type="checkbox" id="e_check4" name="e_check4" /> Type </td></tr>
                <tr><td><input type="checkbox" id="e_check5" name="e_check5" /> Status </td></tr>
                <tr><td><input type="checkbox" id="e_check6" name="e_check6" /> Start Date </td></tr>
                <tr><td><input disabled type="checkbox" id="e_check7" name="e_check7" /> Hours worked </td></tr>
                <tr><td><input disabled type="checkbox" id="e_check8" name="e_check8" /> Day of the week </td></tr>
                <tr><td><input disabled type="checkbox" id="e_check9" name="e_check9" /> Month </td></tr>
                <tr><td><input disabled type="checkbox" id="e_check10" name="e_check10" /> Employer/School </td></tr>
            </table></td>
        <td valign="top"><table>
                <td><input type="checkbox" id="e_check11" name="e_check11" /> Street Address </td>
                <tr><td><input type="checkbox" id="e_check12" name="e_check12" /> City </td></tr>
                <tr><td><input type="checkbox" id="e_check13" name="e_check13" /> County </td></tr>
                <tr><td><input type="checkbox" id="e_check14" name="e_check14" /> State </td></tr>
                <tr><td><input type="checkbox" id="e_check15" name="e_check15" /> Zip </td></tr>
                <tr><td><input type="checkbox" id="e_check16" name="e_check16" /> Phone 1 </td></tr>
                <tr><td><input type="checkbox" id="e_check17" name="e_check17" /> Phone 2 </td></tr>
                <tr><td><input type="checkbox" id="e_check18" name="e_check18" /> Email </td></tr>
                <tr><td><input type="checkbox" id="e_check19" name="e_check19" /> Notes </td></tr>
            </table></td>
    </table>
    <table>
        <td><input style="font-size:10px;margin-left:120px" type="button" id="check_all" name="check_all" value="Check All" 
                   onclick="<?php for ($i = 1; $i < 20; $i++) {
        if ($i < 7 || $i > 10) { ?>document.getElementById('e_check<?php echo($i); ?>').checked=true;<?php }
    } ?>"/><br /></td>
        <td><input style="font-size:10px; margin-left:-20px" type="reset" id="uncheck_all" name="uncheck_all" 
                   value="Uncheck All" /><br /></td>
    </table>

    <input style="font-size:15px;margin-left:180px" type="submit" id="export_data" name="export_data" value="Export to CSV" />
</form>
</body>