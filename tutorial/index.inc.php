<?PHP
/*
 * Copyright 2008 by Oliver Radwan, Maxwell Palmer, Nolan McNair,
 * Taylor Talmage, and Allen Tucker.  This program is part of RMH Homebase.
 * RMH Homebase is free software.  It comes with absolutely no warranty.
 * You can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
*/
	session_start();
	session_cache_expire(30);
?>
<html>
	<head>
		<title>
			MCH Homebase
		</title>
	</head>
	<body>
	<ol>
		<li>	<a href="?helpPage=login.php">Signing in and out of the System</a></li><br>
			<ul><li><a href="?helpPage=index.php">About your Personal Home Page</a></li></ul><br>
		
		<li>	<a href="?helpPage=addMonth.php">Working with the Calendar</a></li><br>
				<ul>
					<li><strong>Editing a Crew on a calendar month</strong></li>
					<p>	<ul>
							<li><a href="help.php?helpPage=assignToCrew.php">Filling a vacancy</a></li>
							<li><a href="help.php?helpPage=removeFromCrew.php">Removing a volunteer</a></li>
							<li><a href="help.php?helpPage=addSlotToCrew.php">Adding/removing a slot</a> (Staff Only)</li>
				
						</ul><br>
					<li><a href="?helpPage=generateMonth.php">Publishing, removing, and generating future calendar months</a> (Staff Only)</li>
					
				</ul><br>
		<li>   <a href="?helpPage=masterSchedule.php">Working with the Master Schedule</a> (Staff Only)</li><br>
		<li>	<strong>Working with the Volunteer Database</strong> (Staff Only)</li><br>
			<ul><li><a href="?helpPage=searchPeople.php">Searching for People</a></li>
			    <li><a href="?helpPage=edit.php">Editing People</a></li>
			    <li><a href="?helpPage=rmh.php">Adding People </a></li>
			</ul><br>
		 
</ol>
		<p>If these help pages don't answer your questions, please contact the <a href="mailto:bm@mchpp.org">Operations Manager</a>
		 or call the oFfice (207-725-2716).</p>
	</body>
</html>

