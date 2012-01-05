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
<title>Add New...</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" href="css/tabber.css" TYPE="text/css" MEDIA="screen">
	<script type="text/javascript" src="js/tabber_cookies.js"></script>
	<script type="text/javascript" src="js/tabber.js"></script>
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
if ($statuscheck == "addteam") {
$teamname = $_POST[addteam];
  		$insert = "INSERT INTO `group` (name) VALUES ('$teamname')";
		mysql_query($insert);
		$insert_GID = mysql_insert_id();

		//ADD TEAM STATUS ITEMS AND LIMITS
		$statusquery = "SELECT * FROM status WHERE GID = '0' GROUP BY level ASC";
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
  		$insert2 = "INSERT INTO `status` (`GID`, `name`, `level`, `limit`) VALUES ('$insert_GID', '$statusname', '$statuslevel', '$statuslimit')";
		mysql_query($insert2);
		}

		//UPDATE TEAM STATUS ITEMS AND LIMITS
		$statusquery = "SELECT * FROM `options` WHERE GID = '0'";
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
		$updateoption1 = "INSERT INTO `options` (`GID`, `option`, `status`, `value`) VALUES ('$insert_GID', 'item', '$srow[status]', '$statusvalue')";
		mysql_query($updateoption1);
		} elseif ($srow['option'] == "date") {
		//update done status number of days to display items
		$insertoption2 = "INSERT INTO `options` (`GID`, `option`, `value`) VALUES ('$insert_GID', 'date', '$_POST[donedays]')";
		mysql_query($insertoption2);
		}
		}

		$_POST[submit_check] = "";
		$statuscheck = "";
		$message = "Team ".$_POST[addteam]." sucessfully added!";
		include("addtask.php");
} elseif ($statuscheck == "adduser") {
$username = $_POST[username];
$email = $_POST[email];
$teamid = $_POST[teamid];
  		$insert = "INSERT INTO `users` (GID, name, email) VALUES ('$teamid', '$username', '$email')";
		mysql_query($insert);
		$message = "Team Member, ".$username." sucessfully added!";
		$_POST[submit_check] = "";
		include("addtask.php");

} elseif ($statuscheck != "") {
//GET TYPE LEVELS AND NAMES TO BUILD TABS
$typequery = "SELECT * FROM type GROUP BY level DESC";
//execute the SQL query and return records
$typeresult = mysql_query($typequery);
	while($trow = mysql_fetch_array($typeresult))
	{
	if ($statuscheck == $trow[level]) {
		//set variables
		$addtoproject = $_POST[addtoproject];
		$addtitle = $_POST[addtitle];
		$addacceptance = $_POST[addacceptance];
		$addteam = $_POST[selectteam];
		$adduser = $_POST[UID];
		$addstatus = $_POST[addstatus];
		$setstate = $_POST[setstate];
		$projectpriority = $_POST[priority];
		//string replace for insert into database
		$escaddtitle = str_replace("'", "''", $addtitle);
		$escaddacceptance = str_replace("'", "''", $addacceptance);
  		$insert = "INSERT INTO item (Slevel, Tlevel, GID, UID, Cdate, title, description, priority, state) VALUES ('$addstatus', '$statuscheck', '$addteam', '$adduser', NOW(), '$escaddtitle', '$escaddacceptance', '$projectpriority', '$setstate')";
		mysql_query($insert);
		$item_IID = mysql_insert_id();
  		$insert2 = "INSERT INTO link (IID, IID_link) VALUES ('$item_IID', '$addtoproject')";
		mysql_query($insert2);
		$plus = 0;
		while($plus <= $addstatus)
		{
		$insert3 = "INSERT INTO date (IID_link, Slevel, date) VALUES ($item_IID, $plus, NOW())";
		mysql_query($insert3);
		$plus++;
		}
  		$insert4 = "INSERT INTO comment (IID_link, UID_link, date, comment) VALUES ('$item_IID', '$adduser', NOW(), 'Item Created!')";
		mysql_query($insert4);
		if($statuscheck == 0) {
		$message = "Item <a href=\"popuptab.php?taskid=".$item_IID."&state=".$setstate."\">".$addtitle."</a> sucessfully added!";
		} else {
		$message = "Item <a href=\"popuptab.php?taskid=".$item_IID."\">".$addtitle."</a> sucessfully added!";
		}
		$_POST[submit_check] = "";
		include("addtask.php");
	}
	}
} else {
$_POST[submit_check] = "";
include("addtask.php");
}
?>
</body>
</html>
