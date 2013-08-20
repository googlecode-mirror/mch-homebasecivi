<!--
		RMH Homebase is free software.
		It comes with absolutely no warranty.
		You can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation.
-->


<!-- Begin Header -->
<div id="header" align="center">
<br><br><img src="images/rmhHeader2.gif" align="top-center"><br><br>
<!--<h1><br><br>Homebase <br></h1>-->

</div>

<div align="center" id="navigationLinks">

<?PHP
	//Log-in security
	//If they aren't logged in, display our log-in form.
	if(!isset($_SESSION['logged_in'])){
		include('login_form.php');
		die();
	}
	else if($_SESSION['logged_in']){

		/**Set our permission array.
		 * anything a guest can do, a volunteer and house manager can also do
		 * anything a volunteer can do, a house manager can do.
		 *
		 * If a page is not specified in the permission array, anyone logged into the system
		 * can view it. If someone logged into the system attempts to access a page above their
		 * permission level, they will be sent back to the home page.
		 */
		//pages guests are allowed to view
		$permission_array['index.php']=0;
		$permission_array['about.php']=0;
		//pages volunteers can view
		$permission_array['viewPerson.php']=1;
		$permission_array['searchPeople.php']=1;
		$permission_array['calendar.php']=1;
		$permission_array['view.php']=1;
		$permission_array['edit.php']=1;
		$permission_array['masterSchedule.php']=1;
		$permission_array['addWeek.php']=1;
		//pages only managers can view
		$permission_array['rmh.php']=2;
		$permission_array['log.php']=2;
		//$permission_array['pagename.php']=2;

		//Check if they're at a valid page for their access level.
		$current_page = substr($_SERVER['PHP_SELF'],1);
		if($permission_array[$current_page]>$_SESSION['access_level']){
			//in this case, the user doesn't have permission to view this page.
			//we redirect them to the index page.
			echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
			//note: if javascript is disabled for a user's browser, it would still show the page.
			//so we die().
			die();
		}

		//This line gives us the path to the html pages in question, useful if the server isn't installed @ root.
		$path = strrev(substr(strrev($_SERVER['SCRIPT_NAME']),strpos(strrev($_SERVER['SCRIPT_NAME']),'/')));

		//they're logged in and session variables are set.
		if($_SESSION['access_level']>=0){
			echo('<a href="'.$path.'index.php">home</a> | ');
			echo('<a href="'.$path.'about.php">about</a>');
		}
		if($_SESSION['access_level']>=1){
			echo(' | <strong>calendar :</strong> <a href="'.$path.'calendar.php">view</a> <a href="'.$path.'addWeek.php">manage</a> | ');
			echo('<strong>people :</strong> <a href="'.$path.'view.php">view</a> <a href="'.$path.'searchPeople.php">search</a> ');
		}
		if($_SESSION['access_level']>=2){
			echo('<a href="rmh.php">add</a><br> <a href="'.$path.'masterSchedule.php">master schedule</a>');
		}
		if($_SESSION['access_level']>=0)
			echo(' | <a href="'.$path.'help.php?helpPage='.$current_page.'">help</a> | <a href="'.$path.'logout.php">logout</a> <br>');
	}
?>
</div>
<!--End Header-->