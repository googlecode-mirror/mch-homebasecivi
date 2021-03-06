<?php
/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>
 */
?>
</div>
<div id="content">
    <?PHP
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    if (($_SERVER['PHP_SELF']) == "/logout.php") {
        //prevents infinite loop of logging in to the page which logs you out...
        echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
    }
    if (!array_key_exists('_submit_check', $_POST)) {
        echo('<div align="left"><p>Access to Homebase requires a Username and a Password. ' .
        '<ul>'
        );
        echo('<li>If you are a volunteer logging in for the first time, your Username is your first name followed by your phone number. ' .
        'After you have logged in, you can change your password.  ');
        echo('(If you are having difficulty logging in or if you have forgotten your Password, please contact the <a href="mailto:bm@mchpp.org"><i>Operations Manager</i></a>.) ');
        echo ('<li><i>If you need to cancel your volunteer shift, you may do so here (after logging in), or else you may the House at 207-725-2716.</i>');
        echo '</ul>';
        echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
    } else {
        //check if they logged in as a guest:
        if ($_POST['user'] == "guest" && $_POST['pass'] == "") {
            $_SESSION['logged_in'] = 1;
            $_SESSION['access_level'] = 0;
            $_SESSION['type'] = "";
            $_SESSION['mygroup'] = "";
            $_SESSION['_id'] = "guest";
            echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
        }
        //otherwise authenticate their password
        else {
            $db_pass = md5($_POST['pass']);
            $db_id = $_POST['user'];
            $person = retrieve_person($db_id);
            if ($person) { //avoids null results
            	if ($person->get_password()=="") {
            		$person->set_password(md5($person->get_id()));  echo "default password = ".$person->get_pasword();
            		change_password($person->get_id(), $person->get_password()); // set default password in the db
            	}
            	if ($person->get_password() == $db_pass) { //if the passwords match, login
                    $_SESSION['logged_in'] = 1;
                    if ($person->get_status() == "applicant")
                        $_SESSION['access_level'] = 0;
                    else if ($person->get_type()=="staff")
                        $_SESSION['access_level'] = 2;
                    else
                        $_SESSION['access_level'] = 1;
                    $_SESSION['f_name'] = $person->get_first_name();
                    $_SESSION['l_name'] = $person->get_last_name();
                    $_SESSION['type'] = $person->get_type();
                    $mygroup = $person->get_group();
                    if (sizeof($mygroup)==0)
                    	$_SESSION['mygroup'] = "foodbank";	
                    else 
                    	$_SESSION['mygroup'] = $mygroup[0];
                    $_SESSION['_id'] = $_POST['user'];
                    echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
                }
                else {
                    echo('<div align="left"><p class="error">Error: invalid username/password<br />if you cannot remember your password, ask a house manager to reset it for you.</p><p>Access to Homebase requires a Username and a Password. <p>For guest access, enter Username <strong>guest</strong> and no Password.</p>');
                    echo('<p>If you are a volunteer, your Username is your first name followed by your phone number with no spaces. ' .
                    'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
                    'then your Username would be <strong>John2071234567</strong>.  ');
                    echo('If you do not remember your password, please contact the <a href="mailto:bm@mchpp.org">Operations Manager</a>.');
                    echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
                }
            } else {
                //At this point, they failed to authenticate
                echo('<div align="left"><p class="error">Error: invalid username/password<br />if you cannot remember your password, ask a house manager to reset it for you.</p><p>Access to Homebase requires a Username and a Password. <p>For guest access, enter Username <strong>guest</strong> and no Password.</p>');
                echo('<p>If you are a volunteer, your Username is your first name followed by your phone number with no spaces. ' .
                'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
                'then your Username would be <strong>John2071234567</strong>.  ');
                echo('If you do not remember your password, please contact the <a href="mailto:bm@mchpp.org">Operations Manager</a>.');
                echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
            }
        }
    }
    ?>
    <?PHP include('footer.inc'); ?>
</div>
</div>
</body>
</html>
