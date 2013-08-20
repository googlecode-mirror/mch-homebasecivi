<?php

/*
 * This file is only the connection information for the database.
 * This file will be modified for every installation.
 * @author Max Palmer <mpalmer@bowdoin.edu>
 * @version updated 2/12/08
 */

function connect() {
    $host = "localhost";
    $database = "rmhhomebaseDB";
    $user = "rmhhomebaseDB";
    $pass = "foodyWr1";

    $connected = mysql_connect($host, $user, $pass);
    if (!$connected)
        return mysql_error();
    $selected = mysql_select_db($database, $connected);
    if (!$selected)
        return mysql_error();
    else
        return true;
}

?>
