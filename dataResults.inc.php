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
 * 	dataResults.inc
 *   shows results of a search for a data object
 * 	@author Johnny Coster
 * 	@version 4/2/2012
 */
?>
<form name="data_results" method="post">
    <input type="hidden" name="_form_submit" value="2" />
    <p style="text-align:center"><b>Here are the results.</b>  (Select one or more names and then hit <br> 
                            'Export Data' to choose attributes to export.)</p>
    <table align="center">
    	
        <td valign="top"><table>
                <td><h4>Selection Criteria:</h4>
                    <?php
                    session_start();
                    session_cache_expire(30);
                    $checked = array();
                    for ($i = 0; $i <= count($attribute_array); $i++) {
                        if ($attribute_array[$i][1] == 'on') {
                            $checked[] = $i;
                            echo('<tr><td>' .
                            $attribute_array[$i][2] . ': <b>' . $attribute_array[$i][3] . '</b></td></tr>');
                        }
                    }
                    $_SESSION['checked'] = $checked;
                    ?>
                </td>
            </table></td>
           <td> </td>
        <td valign="top"><table>
                <td><h4>Search Results:</h4>
                <tr><td>
                        <select multiple name="results_list[]" id="tempid" style="width:250px;height:150px;font-size:15px"
                                onmouseup="if(this.value!=''){document.getElementById('b_details').disabled=false;
                                    document.getElementById('b_export').disabled=false}
                                else{document.getElementById('b_details').disabled=true;
                                    document.getElementById('b_export').disabled=true};var count=0;
                                for(var i=0; i < document.getElementById('tempid').options.length; i++){
                                    if(document.getElementById('tempid').options[i].selected)count++};
                                if(count > 1)document.getElementById('b_details').disabled=true">
                                <?php foreach ($returned_people as $per) { ?>
                                <option value="<?php echo($per->get_id()); ?>"><?php echo($per->get_first_name() . " " . $per->get_last_name()); ?></option>
                            <?php } ?>
                        </select></td></tr>
                <tr><td></td></tr></td>
        <tr><td><br />
                <input type="submit" style="margin-left: 35px" id="b_export" name="b_export" value="Export Data" />

            </td></tr>
    </table></td>
</table>
</form>