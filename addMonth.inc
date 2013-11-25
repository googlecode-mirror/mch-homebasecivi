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
                $month = $result[$i];
                if (($i == sizeof($result)-1) || $i == 0)
                    $remove = true;
                else
                    $remove = false;
                if(($month->get_status()=="archived" && $_GET['archive']=="true") || $month->get_status()=="published" || 
                    ($month->get_status()=="unpublished" && $_SESSION['access_level']>=2))     
                    echo ("<tr><td>" . $month->get_name() . "</td>" .
                "<td>" . show_month_options($month, $remove, $group) . "</td></tr>");
                    $next_month_first_day = $month->get_end_of_month_timestamp() + 24*60*60;
                    $next_id = date('y-m',$next_month_first_day)."-".$group;
            }
            // finds the parameters for "generate next month" button
            if ($_SESSION['access_level'] >= 2) {
                echo ('<tr><td>');
            	echo "<input type=\"hidden\" name=\"_new_month_timestamp\" value=\"" . $next_id . "\">
								<input type=\"hidden\" name=\"_submit_check_newmonth\" value=\"1\">
								<input type=\"submit\" value=\"Add new month\" name=\"Add new month\">";
                echo "</td>";
                
                echo('<td align="center"><a href="addMonth.php?archive=');
            	if ($_GET['archive'] == "true")
                	echo 'false&group='.$group.'&month='.$month_id.'">Hide Archive</a>';
            	else
                	echo 'true&group='.$group.'&month='.$month_id.'">View Archive</a>';   
            }
            echo "</td></tr></table>";    

            // determines what options apply to each month
            function show_month_options($month, $remove, $group) {
            	$id = $month->get_id();
            	$status = $month->get_status();
                $end = $month->get_end_of_month_timestamp();
                $options = "";
                $rightnow = time();
                if ($status == "archived") {
                    $options = "archived (<a href=\"calendar.php?month=" . substr($id,0,5) . "&edit=false&group=" . $group . "\">view</a>)";
                }
                // see if right now is part of a new month.  If so, archive the previous month.
                else if ($rightnow > $end) {
                    $options = "archived (<a href=\"calendar.php?month=" . substr($id,0,5) . "&edit=false&group=" . $group . "\">view</a>)";
                    $month->set_status("archived");
                    update_dbMonths($month);
                } else if ($status == "unpublished") {
                    $options = "unpublished 
							(<a href=\"addMonth.php?monthid=" . $id . "&archive=false&group=" . $group . "\">publish</a>)
							(<a href=\"calendar.php?month=" . substr($id,0,5) . "&edit=false&group=" . $group . "\">view</a>)";
                } else { // status must be "published"
                    $options = "published 
							(<a href=\"addMonth.php?monthid=" . $id . "&archive=false&group=" . $group . "\">unpublish</a>)
							(<a href=\"calendar.php?month=" . substr($id,0,5) . "&edit=false&group=" . $group . "\">view</a>)";
                }
                if ($remove)
                    $options = $options . " (<a href=\"addMonth.php?monthid=" . $id . "&archive=".$archive."&group=" . $month->get_group() . "\">remove</a>)";
                return $options;
            }
            ?>
    </form></span>
