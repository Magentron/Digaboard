<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<html>
<head>
<script language="Javascript">
<!--
//This credit must stay intact
//Script by http://www.java-Scripts.net and http://wsabstract.com
 function doClear(theText) {
     if (theText.value == theText.defaultValue) {
         theText.value = ""
     }
 }
//-->
</script>
<title>Options</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" href="css/tabber.css" TYPE="text/css" MEDIA="screen">
	<script type="text/javascript" src="js/tabber_cookies.js"></script>
	<script type="text/javascript" src="js/tabber.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script>
<script type="text/javascript">
/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>
</head>
<body>
<?PHP
include("mysql.php");
$statuscheck = $_POST[submit_check];

//CHECK FOR FORM POST AND GET NUMBER OF TYPE LEVELS
if ($statuscheck == "statuslimits") {
		//UPDATE TEAM STATUS ITEMS AND LIMITS
		$statusquery = "SELECT * FROM status WHERE GID = '$_COOKIE[group]' GROUP BY level ASC";
		//execute the SQL query and return records
		$statusresult = mysql_query($statusquery);
		$numrows = mysql_num_rows($statusresult);
		//FETCH and set VARIABLES needed
		while($srow = mysql_fetch_array($statusresult))
		{
		$postname = "name" . $srow['level'];
		$statusname = $_POST[$postname];
		$postlevel = "level" . $srow['level'];
		$statuslevel = $_POST[$postlevel];
		$postlimit = "limit" . $srow['level'];
		$statuslimit = $_POST[$postlimit];
		$updateteam2 = "UPDATE `status` SET `limit` = '$statuslimit' WHERE GID = '$_COOKIE[group]' AND level = '$srow[level]'";
		mysql_query($updateteam2);
		}
$message = "Status Limits Updated";
include("projectoptions.php");
} elseif ($statuscheck == "boardvisuals") {
		//UPDATE TEAM STATUS ITEMS AND LIMITS
		$statusquery = "SELECT * FROM `options` WHERE GID = '$_COOKIE[group]'";
		//execute the SQL query and return records
		$statusresult = mysql_query($statusquery);
		$numrows = mysql_num_rows($statusresult);
		//FETCH and set VARIABLES needed
		while($srow = mysql_fetch_array($statusresult))
		{
		if ($srow['option'] == "item") {
		//update board items red status indicator
		$postlevel = "limit" . $srow['status'];
		$statusvalue = $_POST[$postlevel];
		$updateoption1 = "UPDATE `options` SET `value` = '$statusvalue' WHERE GID = '$_COOKIE[group]' AND status = '$srow[status]'";
		mysql_query($updateoption1);
		} elseif ($srow['option'] == "date") {
		//update done status number of days to display items
		$updateoption2 = "UPDATE `options` SET `value` = '$_POST[donedays]' WHERE GID = '$_COOKIE[group]' AND `option` = 'date'";
		mysql_query($updateoption2);

		}
		}
$message = "Status Limits Updated";
include("projectoptions.php");
} else {
$_POST[submit_check] = "";
$statuscheck = "";
include("projectoptions.php");
}
?>
</body>
</html>
