<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<html> 
<head>
<title>DigaBoard</title>
	<link REL="SHORTCUT ICON" HREF="images/favicon.ico">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/subModal.css" />
	<link rel="stylesheet" type="text/css" href="css/boardobjects.css" />
	<link rel="stylesheet" type="text/css" href="css/tabber.css" />
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/subModal.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script><script language="Javascript">
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
</head>

<body style="background-color:#13758f;">
<div id="dhtmltooltip"></div>
<!-- Normal page content. -->

<div class="background">

<div class="board">

<?php
include('mysql.php');

//CHECK FOR FORM POST, INSERT TEMP USER INTO DATABASE AND SET COOKIE
if ($_POST[submit_check] == "tempuser") {
	$tempuserQ = "SELECT * ".
		"FROM `users` ".
		"WHERE name = '$_POST[name]' AND temp IS NOT NULL";
	$tempuserR = mysql_query($tempuserQ);
	$tempusernumrows = mysql_num_rows($tempuserR);
	if($tempusernumrows == 0) {
	$tempuser = "INSERT INTO `users` (GID, name, email, temp) VALUES (0, '$_POST[name]', '$_POST[name]', 1)";
	mysql_query($tempuser);
	$temp_UID = mysql_insert_id();
	} else {
	while($urow = mysql_fetch_array($tempuserR))
	{
	$temp_UID = $urow[UID];
	}
	}
echo "<script type=\"text/javascript\">";
echo "Set_Cookie( 'user', '$temp_UID', '7', '/', '', '' );";
echo "parent.location.href='index.php';";
echo "</script>";
} elseif ($_POST[submit_check] == "up") {
	$projectupq = "SELECT `IID`, `priority` FROM `item` WHERE IID = '$_POST[projectidpushed]' OR IID = '$_POST[projectid]'";
	$projectupr = mysql_query($projectupq);
	while($projectuprows = mysql_fetch_array($projectupr)) {
		if ($projectuprows['IID'] == $_POST['projectid']) {
			$newpriority = $projectuprows['priority'] - 1;
//			echo $projectuprows['IID'] . ":" .$projectuprows['priority']. $newpriority;
		} elseif ($projectuprows['IID'] == $_POST['projectidpushed']) {
			$newpriority = $projectuprows['priority'] + 1;
//			echo $projectuprows['IID'] . ":" .$projectuprows['priority']. $newpriority;
		}
		$up_update = "UPDATE `item` SET `priority` = '$newpriority' WHERE IID = '$projectuprows[IID]'";
		mysql_query($up_update);
	}
$submit_check = "";
} elseif ($_POST[submit_check] == "down") {
	$projectupq = "SELECT `IID`, `priority` FROM `item` WHERE IID = '$_POST[projectidpushed]' OR IID = '$_POST[projectid]'";
	$projectupr = mysql_query($projectupq);
	while($projectuprows = mysql_fetch_array($projectupr)) {
		if ($projectuprows['IID'] == $_POST['projectid']) {
			$newpriority = $projectuprows['priority'] + 1;
//			echo $projectuprows['IID'] . ":" .$projectuprows['priority']. $newpriority;
		} elseif ($projectuprows['IID'] == $_POST['projectidpushed']) {
			$newpriority = $projectuprows['priority'] - 1;
//			echo $projectuprows['IID'] . ":" .$projectuprows['priority']. $newpriority;
		}
		$up_update = "UPDATE `item` SET `priority` = '$newpriority' WHERE IID = '$projectuprows[IID]'";
		mysql_query($up_update);
	}
$submit_check = "";
}

//DISPLAY WHO ARE YOU IF NO USER COOKIE
if($_COOKIE[user] == "") {
echo "<div style=\"position:absolute;left:0px;right:0px;top:0px;margin:0px;height:100%;z-index:100;\">";

	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";

	echo "<div style=\"float:left;width:70%;height:100%;\">";
		echo "<div style=\"width:100%;height:20%;\">";
		echo "</div>";
		echo "<div style=\"width:100%;height:60%;background-color:#ffffff;\">";
echo "<div class=\"submodalbox\">";
echo "<div class=\"box-content\">";
echo "<p>";
echo "<div style=\"left:0;top:0px;margin-left:5px;height:12px;font-size:12px;color:#999999;font-weight:bold;\">";
//QUERY TEAM INFO
if($_COOKIE[group] != "") {
$groupquery = "SELECT * FROM `group` WHERE GID = '$_COOKIE[group]'";
//execute the SQL query and return records
$groupresult = mysql_query($groupquery);
while($grouprow = mysql_fetch_array($groupresult))
{
	$groupname = $grouprow[name];
}
	echo $groupname . " | ";
}
	echo "Welcome, Please login below.";
echo "</div>";
	echo "<div style=\"font-size:32px;font-weight:bold;text-align:center;color:#000000;\">Who Are You?</div>";
$groupquery = "SELECT * ".
	"FROM `group` ".
	"WHERE state IS NULL ORDER BY name ASC";
$groupresult = mysql_query($groupquery);
$grouprows = mysql_num_rows($groupresult);
if($grouprows > 0) {
$grouppercent =  90 / $grouprows;
$grouprounded = round($grouppercent);
}
echo "<div style=\"width:100%;height:200px;overflow:hidden;\">";
while($grouprow = mysql_fetch_array($groupresult))
{
	echo "<div style=\"float:left;width:".$grouprounded."%;height:95%;background:#ffffbf;margin-left:8px;overflow:hidden;\">";

	if ($groupname == $grouprow[name]) { $bold = "font-weight:bold;"; }
	echo "<div style=\"height:20px;width:100%;background-color:#13758f;font-size:15px;text-align:center;color:#ffffff;".$bold."\">";
	echo $grouprow[name];
	echo "</div>";
	$userquery = "SELECT * ".
	"FROM `users` ".
	"WHERE GID = $grouprow[GID] AND temp IS NULL ORDER BY name ASC";
	$userresult = mysql_query($userquery);
	while($userrow = mysql_fetch_array($userresult))
	{
	$userprint = substr($userrow[name],0,14);
	echo "<div title=\"".$userrow[name]."\" style=\"float:left;height:40px;\">";
			echo "<div class=\"buttons\" onclick=\"Set_Cookie('user', '".$userrow[UID]."', 30, '/', '', '' );parent.location.href='index.php';\">";
				echo "<button type=\"submit\" class=\"check\">";
			        echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $userprint;
				echo "</button>";
			echo "</div>";
	echo "</div>";
	}
	echo "</div>";
}
echo "</div>";

echo "<div style=\"margin-top:10px;height:40px;width:100%;\">";

	echo "<div style=\"text-align:center;width:100%;height:100%;\">";
		echo "<form name=\"tempuser\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"tempuser\">";
		echo "<input type=\"input\" style=\"font-size:23px;font-style:italic;width:300px;\" name=\"name\" value=\"Enter Guest Name...\" onFocus=\"doClear(this)\">";
		echo "<input type=\"submit\" name=\"submit\" value=\"Go\">";
		echo "</form>";
	echo "</div>";

echo "</div>";

echo "</p>";
echo "</div>";
echo "</div>";
		echo "</div>";
		echo "<div style=\"width:100%;height:20%;\">";
		echo "</div>";
	echo "</div>";

	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
echo "</div>";
}

//FIGURE OUT WHAT TO SET THE ZOOM BUTTONS TOO
$statusquery = "SELECT * FROM type";
//execute the SQL query and return records
$statusresult = mysql_query($statusquery);
$numstatusrows = mysql_num_rows($statusresult);

	//SET ITEM ZOOM LEVEL
	if (isset($_COOKIE[zoom])) {
		$zoom_level = $_COOKIE[zoom];
	} else {
		$zoom_level = $numstatusrows - 1;
	}

//LOOK UP GROUP NAME AND SET GROUP COOKIE
if(isset($_COOKIE[group])) {
$group_id = $_COOKIE[group];
} else {
$zoom_level=0;
$group_id=1;
//echo "<center>No team selection, please select team to see project board.</center>";
}
//echo "<script type=\"text/javascript\">";
//echo "Set_Cookie('group', '1', 30, '/', '', '' )";
//echo "</script>";
//$group_id = $_COOKIE[group];

if($zoom_level == $numstatusrows - 1) {
	//SHOW TASKS (highest Tlevel)
	//COLUMN BY PROJECT
	//cant zoom in
	$zoom_in = $zoom_level;
	//can zoom out
	$zoom_out = $zoom_level - 1;
} elseif ($zoom_level == ($numstatusrows - $numstatusrows)) {
	//SHOW PROJECTS (lowest Tlevel)
	//COLUMN BY TEAM
	//cant zoom out
	$zoom_out = $zoom_level;
	//can zoom in
	$zoom_in = $zoom_level + 1;
} else {
	$zoom_in = $zoom_level + 1;
	$zoom_out = $zoom_level - 1;
}
//echo $_COOKIE[zoom];
//BUILD LEFT ITEM STATUS BAR
if($zoom_level == 0) {
echo "<div class=\"leftbar\">";
	echo "<div style=\"background-image: url(images/active.png);background-repeat:no-repeat;background-position:center center;width:100%;height:49%;\"></div>";
	echo "<div style=\"background-image: url(images/inactive.png);background-repeat:no-repeat;background-position:center center;width:100%;height:49%;\"></div>";
echo "</div>";

} else {
echo "<div class=\"leftbar\">";
	//BOARD OPTIONS DATE QUERY
	$group_id = $_COOKIE[group];
	$optionsquery = "SELECT * FROM `status` WHERE GID = '$group_id' and `level` <> 0";
	//execute the SQL query and return records
	$optionsresult = mysql_query($optionsquery);
	$counter = 0;
	while($optionrow = mysql_fetch_array($optionsresult))
	{
		$counter++;
		$levelnum[$counter] = $optionrow['limit'];
	}
	echo "<div style=\"background-image: url(images/done.png);background-repeat:no-repeat;background-position:center center;width:100%;height:20%;\"></div>";
	echo "<div style=\"background-image: url(images/review.png);background-repeat:no-repeat;background-position:center center;width:100%;height:20%;position:relative;\"><a class=\"submodal\" href=\"popupoptions.php\"><div class=\"limit\">".$levelnum['3']."</div></a></div>";
	echo "<div style=\"background-image: url(images/doing.png);background-repeat:no-repeat;background-position:center center;width:100%;height:30%;position:relative;\"><a class=\"submodal\" href=\"popupoptions.php\"><div class=\"limit\">".$levelnum['2']."</div></a></div>";
	echo "<div style=\"background-image: url(images/ready.png);background-repeat:no-repeat;background-position:center center;width:100%;height:30%;position:relative;\"><a class=\"submodal\" href=\"popupoptions.php\"><div class=\"limit\">".$levelnum['1']."</div></a></div>";
echo "</div>";
}
//check for state cookie and use if it exists
if($_COOKIE[state] == "") {
$state = "active";
} else {
$state = $_COOKIE[state];
}

//CHECK ZOOM LEVEL AND BUILD BOARD FOR APPROPRIATE LEVEL
if($zoom_level == 0) {
	//get active groups to build board
$projectquery = "SELECT * ".
	"FROM `group` ".
	"WHERE state IS NULL ORDER BY name ASC";
} else {
	//get projects to build board
$projectquery = "SELECT item.IID, item.GID, item.Tlevel, item.Slevel, item.title, item.state, item.priority ".
	"FROM item ".
	"WHERE item.Tlevel = 0 AND item.state = '$state' AND item.gid = '$group_id' AND item.Edate IS NULL ".
	"ORDER BY item.priority ASC";
}
//execute the SQL query and return records
$projectresult = mysql_query($projectquery);
$projectrows = mysql_num_rows($projectresult);
if($projectrows > 0) {
$percent =  99.5 / $projectrows;
$rounded = round($percent);
}
$rowcount=1;
//display the results
if ($projectrows == 0) {
	echo "<div style=\"float:left;width:15%;height:95%;\">";
	echo "</div>";

	echo "<div style=\"float:left;width:70%;height:95%;\">";
		echo "<div style=\"width:100%;height:20%;\">";
		echo "</div>";

		echo "<div style=\"width:100%;height:50%;background-color:#ffffc6;border:1px solid #000000;font-size:20px;\">";
			echo "<center><b>Oops! You are looking at ".$state." projects and there is nothing to display. </b></center><br>";
			echo "This can happen for two reasons:";
			echo "<ul><li>No projects are in the current Active or Inactive view.</li>";
			echo "<li>You do not have any open projects to display.</li></ul>";
			echo "<p><center><b>You are looking at ".$state." projects</b></center>";
		echo "</div>";

		echo "<div style=\"width:100%;height:20%;\">";
		echo "</div>";
	echo "</div>";

	echo "<div style=\"float:left;width:15%;height:95%;\">";
	echo "</div>";
}
while($prow = mysql_fetch_array($projectresult))
{
$numcount = $numcount + 1;
if($zoom_level == 0) {
	$group = $prow["name"];
	$group_id = $prow["GID"];
} else {
	$project = $prow["title"];
	$project_id = $prow["IID"];
	$state = $prow["state"];
	$priority = $prow["priority"];
}
//if no rows are returned then show message on board

	echo "<div style=\"background-color:#13758f;z-index:0;float:left;height:100%;width:".$percent."%;display:inline;\">";
	include('project.php');
	$rowcount++;
	echo "</div>";
}
?>
</div>

<div class="menu">
<?PHP
if($_COOKIE[group] != "") {
//GROUP AND USER QUERY FOR MENU HOVERS
		//QUERY TEAM INFO
		$group_id = $_COOKIE[group];
		$groupquery = "SELECT * FROM `group` WHERE GID = '$group_id'";
		//execute the SQL query and return records
		$groupresult = mysql_query($groupquery);
		while($grouprow = mysql_fetch_array($groupresult))
		{
			$groupname = $grouprow[name];
		}
}
if ($_COOKIE[user] != "") {
		//QUERY LOGGED IN USER NAME
		$userquery = "SELECT name ".
		"FROM `users` ".
		"WHERE UID = $_COOKIE[user]";
		$userresult = mysql_query($userquery);
		while($userrow = mysql_fetch_array($userresult))
		{
			$username = $userrow[name];
		}
}
//gray out the project board button if no team is selected and display message to select a team
if($_COOKIE[group] == "") {
echo "<div class=\"teamgray\" border=\"0\">";
//echo "<img src=\"images/001_57.png\" onclick=\"Set_Cookie('zoom', '".$zoom_out."', 30, '/', '', '' )\" border=\"0\">";
echo "<div class=\"over\">";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
	echo "<div style=\"float:left;width:70%;height:100%;\">";
		echo "<div style=\"width:100%;height:10%;\">";
		echo "</div>";

		echo "<div style=\"position:relative;width:100%;height:30%;background-color:#ffff91;border:4px solid #000000;\">";
			echo "<div style=\"bottom:0px;width:100%;height:60%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#000000;font-size:300%;overflow:hidden;\">You are on the <b>Team Board</b></div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;background-color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#ffff91;font-size:200%;\">Select team to view project board!</div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"position:absolute;bottom:0px;left:0px;font-size:150%;\">";
			if ($groupname == "") {
			echo "<font color=\"red\">ERROR - Select Team!</font>";
			} else {
			echo $groupname;
			}
			echo "</div>";
			echo "<div style=\"position:absolute;bottom:0px;right:0px;font-size:150%;\">".$username." is logged in.</div>";
			echo "</div>";
		echo "</div>";

		echo "<div style=\"width:100%;height:60%;\">";
		echo "</div>";
	echo "</div>";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";echo "</div>";
echo "</div>";
} elseif($_COOKIE[zoom] == 0) {
//echo "<a style=\"text-decoration:none;\" href=\"index.php\">";
echo "<div class=\"team\" onclick=\"Set_Cookie('zoom', '".$zoom_in."', 30, '/', '', '' );parent.location.href='index.php';\" border=\"0\">";
//echo "<img src=\"images/001_57.png\" onclick=\"Set_Cookie('zoom', '".$zoom_out."', 30, '/', '', '' )\" border=\"0\">";
echo "<div class=\"over\">";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
	echo "<div style=\"float:left;width:70%;height:100%;\">";
		echo "<div style=\"width:100%;height:10%;\">";
		echo "</div>";

		echo "<div style=\"position:relative;width:100%;height:30%;background-color:#ffff91;border:4px solid #000000;\">";
			echo "<div style=\"bottom:0px;width:100%;height:60%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#000000;font-size:300%;overflow:hidden;\">You are on the <b>Team Board</b></div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;background-color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#ffff91;font-size:200%;\">Show the Project board?</div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"position:absolute;bottom:0px;left:0px;font-size:150%;\">".$groupname."</div>";
			echo "<div style=\"position:absolute;bottom:0px;right:0px;font-size:150%;\">".$username." is logged in.</div>";
			echo "</div>";
		echo "</div>";

		echo "<div style=\"width:100%;height:60%;\">";
		echo "</div>";
	echo "</div>";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
echo "</div>";
echo "</div>";
//echo "</a>";
} elseif($_COOKIE[zoom] == 1) {
//if($_COOKIE[zoom] == "" AND $_COOKIE[group] == "") {
//echo "<a class=\"submodal\" style=\"text-decoration:none;\" href=\"popupgroup.php\">";
//} else {
//echo "<a style=\"text-decoration:none;\" href=\"index.php\">";
//}
echo "<div class=\"project\" onclick=\"Set_Cookie('zoom', '".$zoom_out."', 30, '/', '', '' );parent.location.href='index.php';\" border=\"0\">";
//echo "<img src=\"images/001_50_p.png\" onclick=\"Set_Cookie('zoom', '".$zoom_in."', 30, '/', '', '' )\" border=\"0\">";
echo "<div class=\"over\">";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
	echo "<div style=\"float:left;width:70%;height:100%;\">";
		echo "<div style=\"width:100%;height:10%;\">";
		echo "</div>";

		echo "<div style=\"position:relative;width:100%;height:30%;background-color:#ffff91;border:4px solid #000000;\">";
			echo "<div style=\"bottom:0px;width:100%;height:60%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#000000;font-size:300%;overflow:hidden;\">You are on the <b>Project Board</b></div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;background-color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#ffff91;font-size:200%;\">Show the team board?</div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"position:absolute;bottom:0px;left:0px;font-size:150%;\">".$groupname."</div>";
			echo "<div style=\"position:absolute;bottom:0px;right:0px;font-size:150%;\">".$username." is logged in.</div>";
			echo "</div>";
		echo "</div>";

		echo "<div style=\"width:100%;height:60%;\">";
		echo "</div>";
	echo "</div>";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
echo "</div>";

echo "</div>";
//echo "</a>";
}
if($zoom_level != ($numstatusrows - $numstatusrows)) {
if($_COOKIE[state] == active) {
//echo "<a style=\"text-decoration:none;\" href=\"index.php\" onclick=\"Set_Cookie('state', 'inactive', 30, '/', '', '' )\">";
echo "<div class=\"act\" onclick=\"Set_Cookie('state', 'inactive', 30, '/', '', '' );parent.location.href='index.php';\">";
//echo "<img src=\"images/menu/active.png\" onclick=\"Set_Cookie('state', 'active', 30, '/', '', '' )\" border=\"0\">";

echo "<div class=\"over\">";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
	echo "<div style=\"float:left;width:70%;height:100%;\">";
		echo "<div style=\"width:100%;height:10%;\">";
		echo "</div>";

		echo "<div style=\"position:relative;width:100%;height:30%;background-color:#ffff91;border:4px solid #000000;\">";
			echo "<div style=\"bottom:0px;width:100%;height:60%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#000000;font-size:300%;overflow:hidden;\">Showing <b>Active Projects</b></div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;background-color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#ffff91;font-size:200%;\">View Inactive Projects?</div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"position:absolute;bottom:0px;left:0px;font-size:150%;\">".$groupname."</div>";
			echo "<div style=\"position:absolute;bottom:0px;right:0px;font-size:150%;\">".$username." is logged in.</div>";
			echo "</div>";
		echo "</div>";

		echo "<div style=\"width:100%;height:60%;\">";
		echo "</div>";
	echo "</div>";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
echo "</div>";

echo "</div>";
//echo "</a>";
} elseif($_COOKIE[state] == inactive) {
//echo "<a style=\"text-decoration:none;\" href=\"index.php\" onclick=\"Set_Cookie('state', 'active', 30, '/', '', '' )\">";
echo "<div class=\"inact\" onclick=\"Set_Cookie('state', 'active', 30, '/', '', '' );parent.location.href='index.php';\">";
//echo "<img src=\"images/menu/inactive.png\" onclick=\"Set_Cookie('state', 'inactive', 30, '/', '', '' )\" border=\"0\">";
echo "<div class=\"over\">";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
	echo "<div style=\"float:left;width:70%;height:100%;\">";
		echo "<div style=\"width:100%;height:10%;\">";
		echo "</div>";

		echo "<div style=\"position:relative;width:100%;height:30%;background-color:#ffff91;border:4px solid #000000;\">";
			echo "<div style=\"bottom:0px;width:100%;height:60%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#000000;font-size:300%;overflow:hidden;\">Showing <b>Inactive Projects</b></div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;background-color:#000000;overflow:hidden;\">";
			echo "<div style=\"text-align:center;color:#ffff91;font-size:200%;\">View Active Projects?</div>";
			echo "</div>";

			echo "<div style=\"bottom:0px;width:100%;height:20%;color:#000000;overflow:hidden;\">";
			echo "<div style=\"position:absolute;bottom:0px;left:0px;font-size:150%;\">".$groupname."</div>";
			echo "<div style=\"position:absolute;bottom:0px;right:0px;font-size:150%;\">".$username." is logged in.</div>";
			echo "</div>";
		echo "</div>";


		echo "<div style=\"width:100%;height:60%;\">";
		echo "</div>";
	echo "</div>";
	echo "<div style=\"float:left;width:15%;height:100%;\">";
	echo "</div>";
echo "</div>";
echo "</div>";
//echo "</a>";
}
}
?>
<a style="text-decoration:none;" class="submodal" href="popupsearch.php">
<div class="item" onMouseover="ddrivetip('Search','#ffffff', 95)"; onMouseout="hideddrivetip()">
<img border="0" src="css/menu/search.png">
</div>
</a>

<a style="text-decoration:none;" class="submodal" href="popupadd.php">
<div class="item" onMouseover="ddrivetip('Add','#ffffff', 60)"; onMouseout="hideddrivetip()">
<img border="0" src="css/menu/add.png">
</div>
</a>

<a style="text-decoration:none;" class="submodal" href="popupoptions.php">
<div class="item" onMouseover="ddrivetip('Edit Board','#ffffff', 120)"; onMouseout="hideddrivetip()">
<img border="0" src="css/menu/options.png">
</div>
</a>

<!--
<a style="text-decoration:none;" class="submodal" href="selectmetric.php">
<div class="item" onMouseover="ddrivetip('Performance Metrics','#ffffff', 260)"; onMouseout="hideddrivetip()">
<img border="0" src="images/001_98.png">
</div>
</a>
-->

<a style="text-decoration:none;" class="submodal" href="help.php">
<div class="item" onMouseover="ddrivetip('Help','#ffffff', 60)"; onMouseout="hideddrivetip()">
<img border="0" src="css/menu/help.png">
</div>
</a>

<div class="item" onclick="Set_Cookie('user', '', -30, '/', '', '' );parent.location.href='index.php';" onMouseover="ddrivetip('Log-Out','#ffffff', 100);" onMouseout="hideddrivetip()">
<img border="0" src="css/menu/logout.png">
</div>

</div>

</div>


<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip
</script>
</body>
</html>
