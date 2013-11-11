<p>
    <strong><?php echo $groups[$group]?> Calendar Month Management</strong><br />
    Here you can add new months to the calendar, publish or unpublish future months, 
    or remove and re-add a month that is already there. This last optiion is handy 
    when you want to repopulate a future month after having added or removed people 
    from the master schedule.</p>
<span class="nextMonthTable">
    <form method="POST">
        <p><table border="1">
            <tr><td>Month</td><td>Status (options)</td></tr>
            <?php
            for ($i = 0; $i < sizeof($result); ++$i) {
                echo "we are here";
                $month = $result[$i];
                if ($i == sizeof($result)-1 || $i == 0)
                    $remove = true;
                else
                    $remove = false;
                    echo $month->get_name() . " ". $month->get_id(); 
                if(($month->get_status()=="archived" && $_GET['archive']=="true") || $month->get_status()=="published" || 
                    ($month->get_status()=="unpublished" && $_SESSION['access_level']>=2))     
                    echo ("<tr><td>" . $month->get_name() . " ". $month->get_id()."</td>" .
                "<td>" . show_month_options($month, $remove) . "</td></tr>");
            }
            // finds the parameters for "generate next month" button
            if ($_SESSION['access_level'] >= 2) {
                if ($month){
                    $last = $month -> get_id();
                    if ($month->get_monthday_group() == "odd")
                        $next_monthday_group = "even";
                    else
                        $next_monthday_group = "odd";
                    
                    $monthend_group = $month -> get_monthend_group(); 
                    if ($month->get_monthend_group() == "1st")
                   		$monthend_group = "2nd";
                    else if ($month->get_monthend_group() == "2nd")
                    	$monthend_group = "3rd";
                    else if ($month->get_monthend_group() == "3rd")
                    	$monthend_group = "4th";
                    else if ($month->get_monthend_group() == "4th" && substr($last, -5, 2) < 22)
                    	$monthend_group = "5th";
                    else $monthend_group = "1st"; 
                }
                $new_month_timestamp = mktime(0, 0, 0, substr($last, 0, 2), substr($last, 3, 2) + 7, substr($last, 6, 2));
                echo ('<tr><td>');
            	echo "<input type=\"hidden\" name=\"_new_month_timestamp\" value=\"" . $new_month_timestamp . "\">
								<input type=\"hidden\" name=\"_submit_check_newmonth\" value=\"1\">
								<input type=\"submit\" value=\"Add new month\" name=\"Add new month\">";
                echo "</td><td>";
                echo "<select name=\"monthday_group\">";
                // the group option menus, expected groups selected by default
                $groups = array("odd", "even");
                foreach ($groups as $i) {
                    echo "<option value=\"" . $i . "\"";
                    if ($i == $next_monthday_group)
                        echo " SELECTED";
                    echo ">" . $i . "</option>";
                }
                echo "</td><td>";
                echo "<select name=\"monthend_group\">";
                // the group option menus, expected groups selected by default
                $groups = array("1st", "2nd", "3rd", "4th", "5th");
                foreach ($groups as $i) {
                    echo "<option value=\"" . $i . "\"";
                    if ($i == $monthend_group)
                        echo " SELECTED";
                    echo ">" . $i . "</option>";
                }
                echo "</select></td>";
                echo('<td align="center"><a href="addMonth.php?archive=');
            	if ($_GET['archive'] == "true")
                	echo 'false">Hide Archive</a></td></tr><tr>';
            	else
                	echo 'true">View Archive</a></td></tr><tr>';
                if ($firstmonth) {
                    echo ('<td colspan="4"><select name="month">');
                    $months = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
                    $today = date("M");
                    for ($i = 0; $i <= 12; ++$i) {
                        echo ("<option value=\"" . $i . "\"");
                        if ($_POST['month'] == $i || $today == $months[$i])
                            echo (" SELECTED");
                        echo (">" . $months[$i] . "</option>");
                    }
                    echo ('</select> <select name="day"> <option value="0"></option>');
                    $today = date("j");
                    for ($i = 1; $i <= 31; ++$i) {
                        echo ("<option ");
                        if ($_POST['day'] == $i || $today == $i)
                            echo (" SELECTED");
                        echo (">" . $i . "</option>");
                    }
                    echo ('</select><select name="year"> <option value="0"></option>');
                    $i = date("Y");
                    echo ("<option ");
                    if ($_POST['year'] == $i || $_POST['year'] != $i + 1)
                        echo (" SELECTED");
                    echo (">" . $i . "</option>");
                    echo ("<option ");
                    if ($_POST['year'] == $i + 1)
                        echo (" SELECTED");
                    echo (">" . ($i + 1) . "</option>");
                    echo ("</select></td>");
                }
                else echo "<td colspan = '4'>";    
            }
            echo "</td></tr></table>";    

            // determines what options apply to each month
            function show_month_options($month, $remove) {
            	$id = $month->get_id();
                $status = $month->get_status();
                $end = $month->get_end_of_month_timestamp();
                $options = "";
                $rightnow = time();
                if ($status == "archived") {
                    $options = "archived (<a href=\"calendar.php?id=" . $id . "&venue=house\">view</a>)";
                }
                // see if right now is part of a new month.  If so, archive the previous month.
                // here was a BIG BUG -- today might not have an immediate predecessor month in the database,
                // in which case "month2" below will be null!  So this now accommodates gaps (missing months) in the dbMonths table.
                else if ($rightnow > $end) {
                    $options = "archived (<a href=\"calendar.php?id=" . $id . "&venue=house\">view</a>)";
                    $month2 = get_dbMonths($id);
                    if ($month2 != null) {
                        $month2->set_status("archived");
                        update_dbMonths($month2);
                    }
                } else if ($status == "unpublished") {
                    $options = "unpublished 
							(<a href=\"calendar.php?id=" . $id. "&venue=house&edit=true\">edit</a>)
							(<a href=\"addMonth.php?publish=" . $id . "\">publish</a>)
							(<a href=\"calendar.php?id=" . $id . "&venue=house\">view</a>)";
                } else { // status must be "published"
                    $options = "published (<a href=\"calendar.php?id=" . $id . "&venue=house&edit=true\">edit</a>)
							(<a href=\"addMonth.php?publish=" . $id . "\">unpublish</a>)
							(<a href=\"calendar.php?id=" . $id . "&venue=house\">view</a>)";
                }
                if ($remove)
                    $options = $options . " (<a href=\"addMonth.php?remove=" . $id . "&archive=".$_GET['archive']."\">remove</a>)";
                return $options;
            }
            ?>
    </form></span>
