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
 * 	dataSearch.inc.php
 *   shows a form to search for a data object
 * 	@author Johnny Coster
 * 	@version 4/2/2012
 */
?>

<head>
    <style type="text/css">
        td {padding-bottom: 8px}
    </style>
</head>

<form name="search_data" method="post">
    <input type="hidden" name="_form_submit" value="1" />
    <b>These steps guide you through exporting volunteer data to a CSV file.</b><br> 
    <br>1.  You may limit the amount of data exported by selecting specific volunteer 
    attributes or calendar months.  If you choose nothing, all volunteer and past 
    calendar crew data will be exported.
    <br><br>2.  Hit <b>Search</b> to view the results.
    <br><br><table>
        <tr><td><table width="50%"> <b>Volunteer Selection:</b>
                <tr><td><br><input type="checkbox" id="check1" name="check1" /> First Name: <input type="text" name="first_name" 
                     onkeyup="if(this.value=='')document.getElementById('check1').checked=false;
                     else document.getElementById('check1').checked=true"/>
                </td></tr><tr>
                    <td><input type="checkbox" id="check2" name="check2" /> Last Name: <input type="text" name="last_name" 
                     onkeyup="if(this.value=='')document.getElementById('check2').checked=false;
                     else document.getElementById('check2').checked=true"/>
                    </td></tr>
                <tr><td><input type="checkbox" id="check3" name="check3" /> Type: 
                    <select name="type" onmouseup="if(this.value=='')document.getElementById('check3').checked=false;
                            else document.getElementById('check3').checked=true">
                            <option value="">--any--</option>
                            <option value="volunteer">Volunteer</option>
                            <option value="staff">Staff</option>
                    </select>
                    </td></tr>
                <tr><td><input type="checkbox" id="check4" name="check4" /> Status: 
                    <select name="status" onmouseup="if(this.value=='')document.getElementById('check4').checked=false;
                            else document.getElementById('check4').checked=true">
                            <option value="">--any--</option>
                            <option value="active">Active</option>
                            <option value="LOA">On Leave</option>
                            <option value="applicant">Applicant</option>
                            <option value="former">Former</option>
                    </select>
                    </td></tr>
                <tr><td><input type="checkbox" id="check5" name="check5" /> Group:
                    <select name="xgroup" onmouseup="if(this.value=='')document.getElementById('check5').checked=false;
                            else document.getElementById('check5').checked=true">
                               <option value="">--any--</option>
                               <option value="foodbank">Food Bank</option>
                               <option value="foodpantry">Food Pantry</option>
                               <option value="soupkitchen">Soup Kitchen</option>
                    </select>
                    </td></tr>
                <tr><td><input type="checkbox" id="check6" name="check6"/> Role:
                        <select name="xrole" onmouseup="if(this.value=='')document.getElementById('check6').checked=false;
                            else document.getElementById('check6').checked=true">
                            <option value="">--any--</option>
                            <option value="CC">Crew Chief/Chef</option>
                            <option value="B">Boxes</option>
                            <option value="DD">Driver</option>
                            <option value="PR">Prep</option>
                            <option value="I">Intake</option>
                            <option value="P">Produce</option>                        
                            <option value="M">Meat</option>
                            <option value="C">Carryout</option>
                            <option value="Pots">Pots</option>
                            <option value="Dishes">Dishes</option>
                        </select>
                    </td></tr>
                
            </table></td>
        <td valign="top">
        <table  width="50%"><b> Calendar Crews Selection:</b>
             <tr><td><br><input type="checkbox" id="check7" name="check7"/> Past Month:
                        <select name="month" onmouseup="if(this.value=='')document.getElementById('check7').checked=false;
                            else document.getElementById('check7').checked=true">
                            <option value="">--all--</option>
                            <?php 
                            $theMonths = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April",
                                "05"=>"May","06"=>"June","07"=>"July","08"=>"August",
                            	"09"=>"September","10"=>"October","11"=>"November","12"=>"December");
                            foreach ($unique_months as $unique_month)
                                echo "<option value='".$unique_month."'>".
                                    $theMonths[substr($unique_month,3,2)]." 20".substr($unique_month,0,2)."</option>";
                            ?>
                        </select>
                    </td></tr> 
                    
    	</table>
    	
    	</td></tr>
    	<tr>
            <td><input style="font-size:15px;float:right;margin-right:20px" type="reset" name="clear_data" value="Clear All Selections" /></td>
            <td><input style="font-size:20px;margin-left:20px" type="submit" name="data_search" value="Search"  /></td>
        </tr>
    </table>	
</form>