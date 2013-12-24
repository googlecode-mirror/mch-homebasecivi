<?PHP
session_start();
session_cache_expire(30);
?>
<!--
        addMonth.php
        @author Max Palmer, Allen Tucker, Brian Jacobel
        @version 3/25/08, revised 10/19/13, 12/17/2013
-->
<html>
    <head>
        <title>
            Add a Month to a group's calendar
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            <div id="content">
                <?PHP
                include_once('database/dbMonths.php');
                include_once('database/dbMasterSchedule.php');
                include_once('database/dbPersons.php');
                include_once('database/dbLog.php');
                include_once('domain/Month.php');
                include_once('domain/Person.php');
                
                // Check to see if there are already months in the db
                // connects to the database to see if there are any months in the dbMonths table
                $group = $_GET['group'];
                $month_id = $_GET['monthid'];
                $archive = $_GET['archive'];
                $groups = array("foodbank"=>"Food Bank", "foodpantry"=>"Food Pantry","soupkitchen"=>"Soup Kitchen");
                $result = getall_dbMonths($group);
                
            // If no months exist, then create the current one
                if (sizeof($result) == 0) {
                	$result[] = retrieve_dbMonths($month_id); // create a new month as needed
                }
                // publishes a month if the user is a manager
                if ($_GET['publish'] && $_SESSION['access_level'] >= 2) {
                	$id = $_GET['publish'];
                    $monthid = $_GET['monthid'];
                    $month = retrieve_dbMonths($monthid);
                    if ($month->get_status() == "unpublished")
                        $month->set_status("published");
                    else if ($month->get_status() == "published")
                        $month->set_status("unpublished");
                    update_dbMonths($month);
                    add_log_entry('<a href=\"personEdit.php?id=' . $_SESSION['_id'] . '\">' . $_SESSION['f_name'] . ' ' . $_SESSION['l_name'] . '</a> ' .
                            $month->get_status() . ' the month of <a href=\"calendar.php?id=' . $month->get_id() . '&edit=true\">' . $month->get_name() . '</a>.');
                    echo "<p>Month \"" . $month->get_name() . "\" " .
                    $month->get_status() . ".<br>";
					include('addMonth.inc');
                }
                // removes a month if user is a manager
                else if ($_GET['remove'] && $_SESSION['access_level'] >= 2) {
                    $id = $_GET['monthid'];
                    $month = retrieve_dbMonths($id);
                    if ($month) {
                      if ($month->get_status() == "unpublished" || $month->get_status() == "archived") {
                        delete_dbMonths($month);
                        add_log_entry('<a href=\"personEdit.php?id=' . $_SESSION['_id'] . '\">' . $_SESSION['f_name'] . ' ' . $_SESSION['l_name'] . '</a> removed the month of <a href=\"calendar.php?id=' . $month->get_id() . '&edit=true\">' . $month->get_name() . '</a>.');
                        echo "<p>Month \"" . $month->get_name() . "\" removed.<br>";
                      }
                      else
                        echo "<p>Month \"" . $month->get_name() . "\" is published, so it cannot be removed.<br>";
					  include('addMonth.inc');
                    }
                }
                else if (!array_key_exists('_submit_check_newmonth', $_POST)) {
                    include('addMonth.inc');
                } else {
                	$month_id = $_POST['_new_month_timestamp'];
                	// add the newe month to the database and refresh the view
                	newMonth($month_id);
                	include('addMonth.inc');
                }
                ?>
                <?PHP include('footer.inc'); ?>
            </div>
        </div>
    </body>
</html>