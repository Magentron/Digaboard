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
<link rel="stylesheet" type="text/css" href="css/style.css">
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

$val = "";
$piid = "";
$state = "";
$pstatus = "";

$val = $_GET['taskid'];
$piid = $_GET['PIID'];
$state = $_GET['state'];
$pstatus = $_GET['pstatus'];

include("mysql.php");

//GET VARS FOR ITEM
if($state != "") {
//Find project link and pull project name and set project variable
	$pidquery = "SELECT GID, priority FROM item WHERE IID = '$val'";
	//execute the SQL query and return records
	$pidresult = mysql_query($pidquery);
	while($pidrow = mysql_fetch_array($pidresult))
	{
	$p_GID = $pidrow[GID];
	$item_priority = $pidrow[priority];
	}
}

//PERFORM UPDATE IF FORM IS SUBMITTED
if(is_numeric($_POST[submit_check])) {
	//declar vars
		$was = $_POST[status];
		$goingto = $_POST[submit_check];

	//UPDATE ITEMS Slevel
		$mysql_update = "UPDATE item SET Slevel = $goingto WHERE IID = $val";
		mysql_query($mysql_update);

	//QUERY FOR NAME OF STATUS FOR TASK MOVED TO MESSAGE
	$statusquery = "SELECT * FROM status WHERE level = '$goingto'";
	//execute the SQL query and return records
	$statusresult = mysql_query($statusquery);
	while($srow = mysql_fetch_array($statusresult))
	{
	$goingto_name = $srow['name'];
	}

	//FIGURE OUT WHAT DATES TO UPDATE
	if($was < $goingto) {
		$from = $was;
		while($from < $goingto) {
			$level_insert = $from + 1;
			$mysql_insert = "INSERT INTO date (IID_link, Slevel, date) VALUES ($val, $level_insert, NOW())";
			mysql_query($mysql_insert);
			$from++;
		}
	} elseif($was >= $goingto) {
		while($was >= $goingto) {
		$mysql_del = "DELETE FROM date WHERE IID_link = $val AND Slevel = $was";
		mysql_query($mysql_del);
			if($was == $goingto) {
			$mysql_insert = "INSERT INTO date (IID_link, Slevel, date) VALUES ($val, $goingto, NOW())";
			mysql_query($mysql_insert);
			}
			$was--;
		}
	}
	//Set update message
	$message = "Task Moved to " . $goingto_name;
	//INSERT COMMENT
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_POST[UID]', NOW(), 'Status Change', 'Task moved to $goingto_name')";
	mysql_query($comment_insert);
	include("tabtaskdetails.php");
} elseif($_POST[submit_check] == "addcomment") {
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_POST[UID]', NOW(), '$_POST[subject]', '$_POST[comment]')";
	mysql_query($comment_insert);

	//Set update message
	$message = "Comment Added";
	include("tabtaskdetails.php");

} elseif($_POST[submit_check] == "updatedetails") {
  $updatetitle = $_POST[updatetitle];
  $updateacceptance = $_POST[updateacceptance];
  $escupdatetitle = str_replace("'", "''", $updatetitle);
  $escupdateacceptance = str_replace("'", "''", $updateacceptance);//if($projectdropdown == "sp") {
//	$message = "ERROR! - SELECT VALID PROJECT!";
//} else {
  	 $update = "UPDATE item SET title = '$escupdatetitle', description = '$escupdateacceptance' WHERE IID = $val";
	 mysql_query($update);	

	//Set update message
	$message = "Item Details Updated";
//}
  include("tabtaskdetails.php");

//UPDATE THE TEAM ONLY ON DROP DOWN CHANGE
} elseif($_POST[submit_check] == "update_team") {
  $updateteam = $_POST[selectteam];
  	$update = "UPDATE item SET GID = $updateteam WHERE IID = $val";
	mysql_query($update);
		//QUERY TEAM NAME
		$groupquery = "SELECT name FROM `group` WHERE GID = '$updateteam'";
		$groupresult = mysql_query($groupquery);
		while($grow = mysql_fetch_array($groupresult))
		{
		$groupname = $grow[name];
		}
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'Team Change', 'Item has been moved to $groupname')";
	mysql_query($comment_insert);
	if($state != "") {
		//QUERY RELATED ITEMS
		$itemquery = "SELECT * FROM `link` WHERE IID_link = '$val'";
		$itemresult = mysql_query($itemquery);
		while($lrow = mysql_fetch_array($itemresult))
		{
		  	$update1 = "UPDATE item SET GID = '$updateteam' WHERE IID = '$lrow[IID]'";
			mysql_query($update1);
			//CLEAR ASSIGNED USERS SINCE THOSE USERS WILL NOT EXIST IN NEW TEAM
			$deleteassigned = "DELETE FROM assign WHERE IID_link = '$lrow[IID]'";
			mysql_query($deleteassigned);
		}
	//Set update message
	$message = "Project and related items moved to new team";
	} else {
	//Set update message
	$message = "Item moved to new team - Select Project!";
	}
  include("tabtaskdetails.php");

//UPDATE THE PROJECT ONLY ON DROP DOWN CHANGE
} elseif($_POST[submit_check] == "update_project") {
  $projectdropdown = $_POST[projectdropdown];
	//UPDATE LINK
  	 $updatelink = "UPDATE link SET IID_link = '$projectdropdown' WHERE IID = $val";
	 mysql_query($updatelink);

		//QUERY PROJECT NAME
		$projectquery = "SELECT title FROM `item` WHERE IID = '$projectdropdown'";
		$projectresult = mysql_query($projectquery);
		while($prow = mysql_fetch_array($projectresult))
		{
		$projectname = $prow[title];
		}
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'Project Change', 'Item has been moved to $projectname')";
	mysql_query($comment_insert);
	//Set update message
	$message = "Item moved to " . $projectname;
  include("tabtaskdetails.php");

//PROJECT SUBMODAL ACTIVATE OR DEACTIVE UPDATE
} elseif($_POST[submit_check] == "state") {

	if ($_POST['state'] == "active") {
		//GET NUMBER OF ACTIVE PROJECTS ON THE BOARD AND SET PRIORITY TO RESULTS PLUS ONE
		//this will make sure re-activing an older project doesnt mess up the priority. it is important to keep the pri num sequential.
		$projectquery = "SELECT item.IID, item.GID, item.Edate, item.Tlevel, item.Slevel, item.title, item.state, item.priority ".
			"FROM item ".
			"WHERE item.Tlevel = 0 AND item.GID = $p_GID AND item.Edate IS NULL AND `state` = 'active'";
		//execute the SQL query and return records
		$projectresult = mysql_query($projectquery);
		$projectnum = mysql_num_rows($projectresult);
		//add one to the number of projects to set prioirty
		$priority = $projectnum + 1;

	$update = "UPDATE item SET state = '$_POST[state]', priority = '$priority' WHERE IID = $val";
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'State Change', 'Project is now Active!')";
	//Set update message
	$message = "State set to Active";
	} elseif ($_POST['state'] == "inactive") {

		//GET ALL PROJECTS WITH A LOWER PRIORITY AND ADD ONE TO THEIR PRIORITY 
		//this will keep the priority numbers sequential which is important to keep the priorty shifting arrows operational.
		$projectquery = "SELECT item.IID, item.GID, item.Edate, item.Tlevel, item.Slevel, item.title, item.state, item.priority ".
			"FROM item ".
			"WHERE item.Tlevel = 0 AND item.GID = '$p_GID' AND item.Edate IS NULL AND `state` = 'active' AND item.priority > '$item_priority'";
		//execute the SQL query and return records
		$projectresult = mysql_query($projectquery);
		while($prow = mysql_fetch_array($projectresult))
		{
		$plus_one = $prow['priority'] - 1;
		$update_pri = "UPDATE item SET priority = '$plus_one' WHERE IID = $prow[IID]";
		mysql_query($update_pri);
		}

	//Set update message
	$message = "Item State Changed";
	$update = "UPDATE item SET state = '$_POST[state]' WHERE IID = $val";
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'Status Change', 'Project is now Inactive')";
	//Set update message
	$message = "Item State Changed";
	} else {
	//Set update message
	$message = "ERROR - Invalid state change!";	
	}
	mysql_query($update);
	mysql_query($comment_insert);
  include("tabtaskdetails.php");

//PROJECT SUBMODAL CLOSE PROJECT
} elseif($_POST[submit_check] == "close_project") {if ($pstatus == "") {
  	 $update = "UPDATE item SET Edate = NOW() WHERE IID = $val";
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'Status Change', 'Project Closed!')";
	mysql_query($comment_insert);
} else {
  	 $update = "UPDATE item SET Edate = NULL WHERE IID = $val";
	$comment_insert = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'Status Change', 'Project Re-opened!')";
	mysql_query($comment_insert);
}
	 mysql_query($update);
  //DISPLAY MESSAGE
if ($pstatus == "") {
  echo "<font color=\"red\" size=\"5\"><b>Project Closed</b></font><br>";
  echo "<font size=\"4\">To re-open go to Team Board, click the team name at the top and go to the closed projects tab.</font>";
     //CLOSE SUBMODAL BUTTON
     echo "<div onclick=\"window.top.hidePopWin();parent.location.href='index.php';\" style=\"position:absolute;top:75px;left:0px;\">";
	echo "<div class=\"buttons\">";
		echo "<button type=\"submit\" class=\"standard\">";
	        echo "Close";
		echo "</button>";
	echo "</div>";
     echo "</div>";
} else {
$message = "Project Re-opened";
include("tabtaskdetails.php");
}

} elseif($_POST[submit_check] == "deletetask") {
		$num_rows = 0;

		//Query for project item links that are not done
		$linkquery = "SELECT * FROM link, item WHERE link.IID_link = $val AND item.IID = link.IID AND item.Slevel < 4";
		//execute the SQL query and return records
		$linkresult = mysql_query($linkquery);
		$num_rows = mysql_num_rows($linkresult);

		if($num_rows > 0) {
		//Query for project item links that are not done
		$linkquery2 = "SELECT * FROM link, item WHERE link.IID_link = $val AND item.IID = link.IID AND item.Slevel < 4 AND state <> 'deleted'";
		//execute the SQL query and return records
		$linkresult2 = mysql_query($linkquery2);
		$num_rows = mysql_num_rows($linkresult2);
		}

	if($num_rows > 0) {
	$message = "ERROR: Cannot delete project. Items linked.";
	include("tabtaskdetails.php");
	} else {
	 $deleteitem = "UPDATE item SET state = 'deleted' WHERE IID = $val";
	 mysql_query($deleteitem);
	 $blocker_comment = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_POST[UID]', NOW(), 'ITEM DELETED', 'This item has been tagged as deleted. Nothing is actually deleted but tagged to not display.')";
	 mysql_query($deleteitem);
	 echo "<font color=\"red\" size=\"5\"><b>Item removed</b></font>";
		//CLOSE BUTTON
		     echo "<div onclick=\"window.top.hidePopWin();Set_Cookie('tabber', '0', 1, '/', '', '' );parent.location.href='index.php';\" style=\"position:absolute;top:50px;left:0px;\">";
			echo "<div class=\"buttons\">";
				echo "<button type=\"submit\" class=\"standard\">";
			        echo "Close";
				echo "</button>";
			echo "</div>";
		     echo "</div>";
		//
	}

} elseif($_POST[submit_check] == "add_assign") {
if($_POST[check_status] == "check") {
	$check_insert = "INSERT INTO assign (IID_link, UID_link) VALUES ($val, '$_POST[uid]')";
	mysql_query($check_insert);
	$blocker_comment = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'User Assigned', '$_POST[username] has been assigned!')";

} else {
  	 $deletechecked = "DELETE FROM assign WHERE IID_link = $val AND UID_link = '$_POST[uid]'";
	 mysql_query($deletechecked);
	$blocker_comment = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_COOKIE[user]', NOW(), 'User Unassigned', '$_POST[username] has been unassigned!')";
}
	mysql_query($blocker_comment);
	//Set update message
	$message = "User Assigned";
	include("tabtaskdetails.php");

//SET ITEM BLOCKER
} elseif($_POST[submit_check] == "blocker") {	if($_POST[tag] == "blocker") {
  	 $update = "UPDATE item SET tag = '$_POST[tag]' WHERE IID = $val";
	 $blocker_comment = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_POST[UID]', NOW(), 'BLOCKER ADDED', 'Item is now a blocker!')";
	} else {
  	 $update = "UPDATE item SET tag = NULL WHERE IID = $val";
	 $blocker_comment = "INSERT INTO comment (IID_link, UID_link, date, subject, comment) VALUES ($val, '$_POST[UID]', NOW(), 'BLOCKER REMOVED', 'Blocker has been removed!')";
	}
	mysql_query($update);
	mysql_query($blocker_comment);
	//Set update message
	$message = "Item Updated";
  include("tabtaskdetails.php");

} else {
	include("tabtaskdetails.php");
}

?>
</body>
</html>
