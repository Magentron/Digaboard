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
<title>Teams</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link rel="stylesheet" href="/css/tabber.css" TYPE="text/css" MEDIA="screen">
	<script type="text/javascript" src="js/cookie.js"></script>
	<script type="text/javascript" src="/js/tabber.js"></script>
	<script type="text/javascript" src="js/tabber_cookies.js"></script>
<script type="text/javascript">
/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>
</head>
<body>
<?PHP
if($_POST[group] == "") {
$group = $_GET['group'];
} else {
$group = $_POST[group];
}
$group_id = $_GET['GID'];
include("mysql.php");
$statuscheck = $_POST[submit_check];
$user_id = $_POST[uid];
$team_id = $_POST[teamid];
$username = $_POST[username];
$email = $_POST[email];
//CHECK FOR FORM POST AND GET NUMBER OF TYPE LEVELS
if ($statuscheck == "edituser") {
	$updateuser = "UPDATE users SET GID = '$team_id', name = '$username', email = '$email' WHERE UID = $user_id";
	mysql_query($updateuser);	
$message = $_POST[username] . " EDITED";
include("editteam.php");
} elseif($statuscheck == "deluser") {
	$updateuser = "UPDATE `users` SET GID = 0 WHERE UID = $user_id";
  	 //$deleteuser = "DELETE FROM users WHERE UID = $user_id";
	 mysql_query($updateuser);
$message = $_POST[username] . " DELETED";
include("editteam.php");
} elseif($statuscheck == "editteam") {
	$updateteam = "UPDATE `group` SET name = '$_POST[group]' WHERE GID = $_POST[team_id]";
	mysql_query($updateteam);

		//UPDATE TEAM STATUS ITEMS AND LIMITS
		$statusquery = "SELECT * FROM status WHERE GID = '$group_id' GROUP BY level ASC";
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
		$updateteam2 = "UPDATE `status` SET `limit` = '$statuslimit' WHERE GID = '$group_id' AND level = '$srow[level]'";
  		//$insert2 = "INSERT INTO `status` (`GID`, `name`, `level`, `limit`) VALUES ('$insert_GID', '$statusname', '$statuslevel', '$statuslimit')";
		mysql_query($updateteam2);
		}


$message = $_POST[group] . " EDITED";
include("editteam.php");
} elseif($statuscheck == "delteam") {
		$numrows = 0;
		$numrows2 = 0;

		$teamquery = "SELECT * FROM `item` WHERE GID = $_POST[team_id] AND Tlevel = 0 AND Edate IS NULL";
		//execute the SQL query and return records
		$teamresult = mysql_query($teamquery);
		$numrows = mysql_num_rows($teamresult);

		if($numrows != 0 ) {
		$teamquery2 = "SELECT * FROM `item` WHERE GID = $_POST[team_id] AND state <> 'deleted' AND Tlevel = 0";
		//execute the SQL query and return records
		$teamresult2 = mysql_query($teamquery2);
		$numrows = mysql_num_rows($teamresult2);
		}

	if($numrows != 0) {
		$message = "CAN NOT REMOVE TEAM! TEAM STILL HAS OPEN PROJECTS ASSOCIATED!";
		include("editteam.php");
	} else {
	//set the state var to tag the team as "deleted". this way any team/project reference will still exisit.
	$deleteteam = "UPDATE `group` SET state = 1 WHERE GID = $_POST[team_id]";
	 mysql_query($deleteteam);
	  echo "<font color=\"red\" size=\"5\"><b>Team removed</b></font>";
	//CLOSE BUTTON
		echo "<div onclick=\"Set_Cookie('group', '$_POST[team_id]', -30, '/', '', '' );window.top.hidePopWin();parent.location.href='index.php';\" style=\"position:absolute;top:50px;left:0px;\">";
		echo "<div class=\"buttons\">";
			echo "<button type=\"submit\" class=\"standard\">";
		        echo "Close";
			echo "</button>";
		echo "</div>";
     		echo "</div>";
	}
} else {
include("editteam.php");
}
?>
</body>
</html>
