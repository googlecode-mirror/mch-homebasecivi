<p>    <strong> <?php echo $groups[$group]?> Calendar Month Management</strong><br />
    Here you can add new months to the calendar, publish or unpublish future months, 
    or remove and re-add a future month. This last optiion is handy 
    when you want to repopulate a future month after making a number of changes 
    to the master schedule.</p>
<form method="POST">
  <p>  <table border="1">
            <tr><td><b>Month</b></td><td><b>Status (options)</b></td></tr>
            <?php
            $result = getall_dbMonths($group);
            for ($i = 0; $i < sizeof($result); ++$i) {
                $month = $result[$i];
                if (($i == sizeof($result)-1) || $i == 0)
                    $remove = true;
                else
                    $remove = false;
                if(($month->get_status()=="archived" && $_GET['archive']=="true") || $month->get_status()=="published" || 
                    ($month->get_status()=="unpublished" && $_SESSION['access_level']>=2))     
                    echo ("<tr><td>" . $month->get_name() . "</td>" .
                "<td>" . show_month_options($month, $remove, $group, $archive) . "</td></tr>");
                    $next_month_first_day = $month->get_end_of_month_timestamp() + 24*60*60;
                    $next_id = date('y-m',$next_month_first_day)."-".$group;
            }
            // finds the parameters for "generate next month" button
            if ($_SESSION['access_level'] >= 2) {
                echo "<tr><td>";
            	echo "<input type=\"hidden\" name=\"_new_month_timestamp\" value=\"" . $next_id . "\">
								<input type=\"hidden\" name=\"_submit_check_newmonth\" value=\"1\">
								<input type=\"submit\" value=\"Add next month\" name=\"Add next month\">";
                echo "</td>";
                
                echo('<td align="center"><a href="addMonth.php?archive=');
            	if ($_GET['archive'] == "true")
                	echo 'false&group='.$group.'&monthid='.$next_id.'">Hide Archive</a>';
            	else
                	echo 'true&group='.$group.'&monthid='.$next_id.'">View Archive</a>';
               	echo "</td></tr>";   
            }
            echo "</table></p></form>";    

            // determines what options apply to each month
            function show_month_options($month, $remove, $group, $archive) {
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
                    insert_dbMonths($month);
                } else if ($status == "unpublished") {
                    $options = "unpublished 
							(<a href=\"addMonth.php?monthid=" . $id . "&archive=".$archive."&publish=true&group=" . $group . "\">publish</a>)
							(<a href=\"calendar.php?month=" . substr($id,0,5) . "&edit=false&group=" . $group . "\">view</a>)";
                } else { // status must be "published"
                    $options = "published 
							(<a href=\"addMonth.php?monthid=" . $id . "&archive=".$archive."&publish=false&group=" . $group . "\">unpublish</a>)
							(<a href=\"calendar.php?month=" . substr($id,0,5) . "&edit=false&group=" . $group . "\">view</a>)";
                }
                if ($remove)  // add condition here to protect against removing the current month
                    $options = $options . " (<a href=\"addMonth.php?monthid=" . $id . "&archive=".$archive."&remove=true&group=" . $group . "\">remove</a>)";
                return $options;
            }
            ?>

