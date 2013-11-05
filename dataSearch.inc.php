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
    <h4>This is a 2-step process. <br>Step 1: Check the attributes you want to limit your search, and then hit "Search".</h4>
    <table>
        <td valign="top"><table>
                <td><input type="checkbox" id="check1" name="check1" /> First Name: <input type="text" name="first_name" 
                                                                                           onkeyup="if(this.value=='')document.getElementById('check1').checked=false;
                                                                                               else document.getElementById('check1').checked=true"/>
                </td><tr>
                    <td><input type="checkbox" id="check2" name="check2" /> Last Name: <input type="text" name="last_name" 
                                                                                              onkeyup="if(this.value=='')document.getElementById('check2').checked=false;
                                                                                                  else document.getElementById('check2').checked=true"/>
                    </td></tr>
                <tr><td><input type="checkbox" id="check3" name="check3"/> 
                        Gender: <input type="radio" id="gender1" name="gender" value="male" onclick="document.getElementById('check3').checked=true" /> Male 
                        <input type="radio" id="gender2" name="gender" value="female" onclick="document.getElementById('check3').checked=true "/> Female
                    </td></tr>
                <tr><td valign="top"><input style="position:relative;float:left" type="checkbox" id="check4" name="check4" />
                        &nbsp;Type: <select multiple name="type[]" onmouseup="if(this.value=='')document.getElementById('check4').checked=false;
                            else document.getElementById('check4').checked=true">
                            <option value="volunteer">Volunteer</option>
                            <option value="manager">Manager</option>
                            <option value="guestchef">Guest Chef</option>
                            <option value="parking">Parking</option>
                            <option value="cleaning">Cleaning</option>
                            <option value="other">Other...</option>
                        </select>
                    </td></tr>
                <tr><td><input type="checkbox" id="check5" name="check5" /> Status: 
                        <select name="status" onmouseup="if(this.value=='')document.getElementById('check5').checked=false;
                            else document.getElementById('check5').checked=true">
                            <option value="">--Select One--</option>
                            <option value="active">Active</option>
                            <option value="LOA">LOA</option>
                            <option value="former">Former</option>
                            <option value="other">Other...</option>
                        </select>
                    </td></tr>
                <tr><td><input type="checkbox" id="check6" name="check6" /> Start Date: 
                        <input type="text" name="start_date" value="(e.g. 02/03/12)" 
                               onfocus="if(this.value==this.defaultValue) value=''" onblur="if(this.value=='')value=this.defaultValue" 
                               onkeyup="if(this.value=='')document.getElementById('check6').checked=false; else document.getElementById('check6').checked=true" />
                    </td></tr>
                <tr><td><input disabled type="checkbox" id="check7" name="check7" /> Hours worked: 
                        <input disabled type="text" name="hours_worked" value="(e.g. 1-5pm)" 
                               onfocus="if(this.value==this.defaultValue) value=''" onblur="if(this.value=='')value=this.defaultValue"
                               onkeyup="if(this.value=='')document.getElementById('check7').checked=false; else document.getElementById('check7').checked=true" />
                    </td></tr>
                <tr><td><input disabled type="checkbox" id="check8" name="check8" /> Day of the week:
                        <select disabled name="day_of_the_week" onmouseup="if(this.value=='--Select One--')document.getElementById('check8').checked=false;
                            else document.getElementById('check8').checked=true">
                                    <?php
                                    $week_array = array("--Select One--", "Monday", "Tuesday", "Wednesday",
                                        "Thursday", "Friday", "Saturday", "Sunday");
                                    foreach ($week_array as $w) {
                                        ?>
                                <option value="<?php echo($w) ?>"><?php echo($w) ?></option>
<?php } ?>
                        </select>
                    </td></tr>
                <tr><td><input disabled type="checkbox" id="check9" name="check9" /> Month:
                        <select disabled name="month" onmouseup="if(this.value=='--Select One--')document.getElementById('check9').checked=false;
                            else document.getElementById('check9').checked=true">
                                    <?php
                                    $month_array = array("--Select One--", "January", "February", "March",
                                        "April", "May", "June", "July", "August", "September",
                                        "October", "November", "December");
                                    foreach ($month_array as $m) {
                                        ?> 
                                <option value="<?php echo($m) ?>"><?php echo($m) ?></option>	
<?php } ?>
                        </select>
                    </td></tr>
                <tr><td><input disabled type="checkbox" id="check10" name="check10" /> Employer/School: 
                        <input disabled type="text" name="employer_school" style="width:135px" onkeyup="if(this.value=='')document.getElementById('check10').checked=false; 
                            else document.getElementById('check10').checked=true"/>
                    </td></tr>
            </table></td>
        <td valign="top"><table>
                <td><input type="checkbox" id="check11" name="check11" /> Street Address: <input type="text" name="street" 
                                                                                                 onkeyup="if(this.value=='')document.getElementById('check11').checked=false; else document.getElementById('check11').checked=true" />
                </td>
                <tr><td><input type="checkbox" id="check12" name="check12" /> City: <input type="text" name="city" onkeyup="if(this.value=='')document.getElementById('check12').checked=false; 
                    else document.getElementById('check12').checked=true"/>
                    </td></tr>
                <tr><td><input type="checkbox" id="check13" name="check13" /> County: <input type="text" name="county" onkeyup="if(this.value=='')document.getElementById('check13').checked=false; 
                    else document.getElementById('check13').checked=true"/>
                    </td></tr>
                <tr><td><input type="checkbox" id="check14" name="check14" /> State:
                        <select name="state" onmouseup="if(this.value=='')document.getElementById('check14').checked=false;
                            else document.getElementById('check14').checked=true">
                                    <?php
                                    $state_array = array("", "AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC",
                                        "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA",
                                        "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE",
                                        "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "RI", "SC",
                                        "SD", "TN", "TX", "UT", "VA", "VT", "WA", "WI", "WV", "WY");
                                    foreach ($state_array as $s) {
                                        ?>
                                <option value="<?php echo($s) ?>"><?php echo($s) ?></option>
                                                                                          <?php } ?>
                        </select>
                    </td></tr>
                <tr><td><input type="checkbox" id="check15" name="check15" /> Zip: <input type="text" name="zip" onkeyup="if(this.value=='')document.getElementById('check15').checked=false; 
                    else document.getElementById('check15').checked=true"/>
                    </td></tr>
                <tr><td><input type="checkbox" id="check16" name="check16" /> Phone 1: 
                        <input type="text" name="phone1" value="(e.g. 2071234567)" 
                               onfocus="if(this.value==this.defaultValue) value=''" onblur="if(this.value=='')value=this.defaultValue" 
                               onkeyup="if(this.value=='')document.getElementById('check16').checked=false; else document.getElementById('check16').checked=true" />
                    </td></tr>
                <tr><td><input type="checkbox" id="check17" name="check17" /> Phone 2: 
                        <input type="text" name="phone2" value="(e.g. 2079876543)" 
                               onfocus="if(this.value==this.defaultValue) value=''" onblur="if(this.value=='')value=this.defaultValue" 
                               onkeyup="if(this.value=='')document.getElementById('check17').checked=false; else document.getElementById('check17').checked=true" />
                    </td></tr>
                <tr><td><input type="checkbox" id="check18" name="check18" /> Email: <input type="text" name="email" 
                                                                                            onkeyup="if(this.value=='')document.getElementById('check18').checked=false; 
                                                                                                else document.getElementById('check18').checked=true"/>
                    </td></tr>
                <tr><td valign=top><input style="position:relative;float:left" type="checkbox" id="check19" name="check19" />
                        &nbsp;Notes: <textarea rows="5" cols="25" name="notes" onkeyup="if(this.value=='')document.getElementById('check19').checked=false; 
                            else document.getElementById('check19').checked=true"></textarea>
                    </td></tr>
            </table></td>
        <tr>
            <td><input style="font-size:15px;float:right;margin-right:20px" type="reset" name="clear_data" value="Clear Fields" /></td>
            <td><input style="font-size:20px;margin-left:20px" type="submit" name="data_search" value="Search" 
                       onclick="if(document.getElementById('check6').checked==false)document.search_data.start_date.value='';
                           if(document.getElementById('check16').checked==false)document.search_data.phone1.value='';
                           if(document.getElementById('check17').checked==false)document.search_data.phone2.value='';" /></td>
        </tr>
    </table>	
</form>