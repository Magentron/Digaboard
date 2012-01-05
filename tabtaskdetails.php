<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?PHP

if($state == "") {
//Find project link and pull project name and set project variable
	$pquery = "SELECT item.title, item.IID, item.GID FROM link, item WHERE link.IID = '$val' AND item.IID = link.IID_link";
	//execute the SQL query and return records
	$presult = mysql_query($pquery);
	while($plrow = mysql_fetch_array($presult))
	{
	$project = $plrow[title];
	$project_IID = $plrow[IID];
	$project_GID = $plrow[GID];
	}
}
//Get name of logged in user
	$uquery = "SELECT name FROM `users` WHERE UID = $_COOKIE[user]";
	//execute the SQL query and return records
	$uresult = mysql_query($uquery);
	while($urow = mysql_fetch_array($uresult))
	{
	$logged_in = $urow[name];
	}

$query = "SELECT * FROM item WHERE IID = $val";
//execute the SQL query and return records
$result = mysql_query($query);
//FETCH and set VARIABLES needed
while($row = mysql_fetch_array($result))
{
//task details
$task = $row['title'];
$acceptance = $row['description'];
$itemstatus = $row['Slevel'];
$pstatus = $row['Edate'];
$tag = $row['tag'];
$groupowner = $row['GID'];
}

$datequery = "SELECT * FROM date WHERE IID_link = '$val' ORDER BY Slevel";
//execute the SQL query and return records
$dateresult = mysql_query($datequery);
//FETCH and set VARIABLES needed
while($row = mysql_fetch_array($dateresult))
{
//set the date variables
$status = $row['Slevel'];
$date[$status] = $row['date'];
}

$teamnameq = "SELECT name FROM `group` WHERE GID = '$groupowner'";
//execute the SQL query and return records
$teamnamer = mysql_query($teamnameq);
while($teamrow = mysql_fetch_array($teamnamer))
{
$taskgroupowner = $teamrow['name'];
}

echo "<div style=\"left:0;top:0px;margin-left:5px;height:12px;font-size:12px;color:#999999;font-weight:bold;\">";
	if($groupowner != $project_GID && $state == "" && $_POST[submit_check] != "update_project") {
	echo "<font color=\"red\">ERROR - Invalid project/team</font>";
	} else {
	echo $taskgroupowner;
	if($state == "") {
	echo " >> ";
	}
	echo $project . " | " . $logged_in . " is logged in";
	}
echo "</div>";

// TASK TITLE
  echo "<div class=\"subtitle\" title=\"CLICK TO CLOSE\" onclick=\"window.top.hidePopWin();Set_Cookie('tabber', '0', 1, '/', '', '' );parent.location.href='index.php';\">";
	  echo $task;
	  //close button
	  echo "<div class=\"close\">Close</div>";
  echo "</div>";

//MASTER TABBER NAV CONTAINER
echo "<div style=\"position:absolute;right:0px;margin-top:8px;margin-right:5px;font-size:20px;color:red;font-weight:bold;\">" . $message . "</div>";
echo "<div class=\"tabber\">";

//TASK DETAILS START
echo "<div class=\"tabbertab\">";
echo "<h2>Details</h2>";
  echo "<div title=\"Acceptance Criteria for task completion\" style=\"margin-left:5px;margin-right:5px;width:990;height:120px;\">";
  echo "<div style=\"font-size:20px;\">".$acceptance."</div>";
  echo "</div>";

//START BOTTOM TAB BUTTONS&COMMENTS&WHO
echo "<div style=\"margin-right:5px;margin-left:5px;width:990px;\">";

//STATUS BUTTON CONTAINER
echo "<div style=\"float:left;width:175px;margin-right:5px;background:#ffffbf;\">";

//BOX TITLE
echo "<div style=\"height:20px;width:100%;background-color:#13758f;font-size:15px;text-align:center;color:#ffffff;\">Item Status</div>";
//CHECK FOR ZOOM COOKIE AND DISPLAY CORRECT BUTTONS
if($state != "") {
	if($state == "active") {
	$new = "inactive";
	} else {
	$new = "active";
	}
	echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"state\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&state=".$new."\">";
	echo "<input type=\"hidden\" name=\"submit_check\" value=\"state\">";
	echo "<input type=\"hidden\" name=\"state\" value=\"".$new."\">";
	echo "<div class=\"buttons\" style=\"float:left;\">";
		echo "<button type=\"submit\" class=\"on\">";
		if ($state == "active"){
	        echo "&nbsp;&nbsp;&nbsp;&nbsp;Deactivate";
		} else {
	        echo "&nbsp;&nbsp;&nbsp;&nbsp;Activate";
		}
		echo "</button>";
	echo "</div>";
	echo "</form>";

} else {

	//DISPLAY BLOCKER TAGGING BUTTON
	echo "<div style=\"height:30px;position:relative;top:0px;\">";
		echo "<form style=\"text-align:center;margin-bottom:0px;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"blocker\">";
		echo "<input type=\"hidden\" name=\"UID\" value=\"".$_COOKIE[user]."\">";
	if($tag == NULL) {
			echo "<div class=\"buttons\">";
				echo "<button type=\"submit\" class=\"noblocker\">";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;Not a Blocker";
				echo "</button>";
			echo "</div>";
		echo "<input type=\"hidden\" name=\"tag\" value=\"blocker\">";
	} else {
			echo "<div class=\"buttons\">";
				echo "<button type=\"submit\" class=\"blocker\">";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;BLOCKER";
				echo "</button>";
			echo "</div>";
		echo "<input type=\"hidden\" name=\"tag\" value=\"NULL\">";
	}
		echo "</form>";
	echo "</div>";

//GRAB STATUS NAMES AND BUILD BUTTONS
$statusquery = "SELECT * FROM status WHERE GID = '$_COOKIE[group]' GROUP BY level DESC";
//execute the SQL query and return records
$statusresult = mysql_query($statusquery);
$numrows = mysql_num_rows($statusresult);
//FETCH and set VARIABLES needed
$submit = $numrows - 1;
echo "<div style=\"height:240px;position:relative;top:0px;\">";
while($srow = mysql_fetch_array($statusresult))
{
$level = $srow['level'];
$statuslimit = $srow['limit'];

	//CHECK HOW MANY ITEMS ARE AT CURRENT BUTTON STATUS LEVEL *************************************************
	$itemsnumq = "SELECT * FROM link, item WHERE link.IID_link = $project_IID AND item.IID = link.IID AND item.Slevel = $level AND item.state <> 'deleted'";
	$itemsnumr = mysql_query($itemsnumq);
	$itemsnumrows = mysql_num_rows($itemsnumr);

	echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"update" . $submit . "\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&PIID=".$piid."\">";
	echo "<input type=\"hidden\" name=\"".$srow['name']."date\" value=\"" . $date[$level] . "\">";
	echo "<input type=\"hidden\" name=\"UID\" value=\"".$_COOKIE[user]."\">";
	if ($status == $level){
	$button = "off";
	} else {
	$button = "on";
	}
	if ($statuslimit != 0 && $itemsnumrows > $statuslimit) {
	$button .= "over";
	}
	echo "<input type=\"hidden\" name=\"submit_check\" value=\"" . $submit . "\">";
	echo "<input type=\"hidden\" name=\"status\" value=\"" . $itemstatus . "\">";
	$searchstring1 = strpos($button, "over");
	if ($searchstring1 === false) {
	echo "<div title=\"".$date[$level]."\" class=\"buttons\">";
	} else {
	echo "<div title=\"Over ".$srow['name']." limit!\" class=\"buttons\">";
	}
		echo "<button type=\"submit\" class=\"".$button."\">";
	        echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $srow['name'];
		$searchstring2 = strpos($button, "off");
		if ($searchstring2 === false) {
		echo "<div style=\"position:absolute;font-size:10px;bottom:12px;right:14px;\">".$itemsnumrows."</div>";
		} else {
		echo "<div style=\"position:absolute;font-size:14px;color:#ffffff;bottom:18px;right:14px;\">".$itemsnumrows."</div>";
		}
		echo "</button>";
	echo "</div>";
	echo "</form>";
$submit--;
}
echo "</div>";
}

echo "</div>";
//***********************************************************************


//DISPLAY LAST COMMENT
echo "<div style=\"float:left;width:600px;margin-right:5px;font-size:20px;background:#ffffbf;\">";
echo "<div style=\"height:20px;width:100%;background-color:#13758f;font-size:15px;text-align:center;color:#ffffff;\">Item Comments</div>";
//QUICK ADD
	echo "<div style=\"width:100%;height:85px;\">";
if($state == "") {
		echo "<form style=\"text-align:center;margin-bottom:0px;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "\">";
} else {
		echo "<form style=\"text-align:center;margin-bottom:0px;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&state=".$state."\">";
}
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"addcomment\">";
		//COMMENT SUBJECT
			echo "<input type=\"hidden\" name=\"subject\" value=\"Comment\">";
			echo "<input type=\"hidden\" name=\"UID\" value=\"".$_COOKIE[user]."\">";
		//ACCEPTANCE TEXTAREA CONTAINER
		echo "<div style=\"width:99%;float:left;\">";
					echo "<textarea name=\"comment\" rows=\"3\" id=\"textarea\" onFocus=\"doClear(this)\">";
					echo "Add Quick Comment...";
					echo "</textarea>";
		echo "</div>";
		echo "<div style=\"float:right;font-size:22px;\">";
			echo "<div class=\"buttons\">";
				echo "<button type=\"submit\" class=\"positive\">";
			        echo "<img src=\"/images/001_50.png\" alt=\"\">";
				echo "Add";
				echo "</button>";
			echo "</div>";
		echo "</div>";
		echo "</form>";
	echo "</div>";
//QUERY AND DISPLAY LAST COMMENT
	echo "<div style=\"width:100%;\">";
		//PROJECT QUERY FOR PROJECT INFORMATION
		$commentquery = "SELECT * FROM comment WHERE IID_link = '$val' GROUP BY date DESC LIMIT 1";
		//execute the SQL query and return records
		$commentresult = mysql_query($commentquery);
		//FETCH and set VARIABLES needed
		while($crow = mysql_fetch_array($commentresult))
		{
		//QUERY USERNAME
		$userquery = "SELECT name FROM `users` WHERE UID = '$crow[UID_link]'";
		$userresult = mysql_query($userquery);
		while($urow = mysql_fetch_array($userresult))
		{
		$username = $urow[name];
		}
		$comment = substr($crow[comment],0,140);
		echo "<font size=\"4\"><b>" . $username . ":</b></font> <font size=\"3\">".$crow[subject]."</font><font size=\"2\"><i> ".$crow[date]."</i></font><br>";
		//echo "<font size=\"2\">User " . $username . " - <i>".$crow[date]."</i></font><br>";
		echo "<font size=\"4\">" . $comment . "...</font><p>";
		}
		$comment = "";
		$username = "";
	echo "</div>";
//END LAST COMMENT DISPLAY

//*************************************************************
echo "</div>";


//ASSIGNED USERS
//SHOW WHO IS WORKING ONLY WHEN ITEM IS HIGHEST LEVEL (TASK)
if($state == "") {
echo "<div style=\"bottom:0px;float:left;width:200px;margin-right:5px;background:#ffffBF;\">";
echo "<div style=\"height:20px;width:100%;background-color:#13758f;font-size:15px;text-align:center;color:#ffffff;\">Who is working on this?</div>";
		//USER QUERY FOR INFORMATION
		$usersquery = "SELECT * FROM users WHERE UID <> 0 AND GID = '$_COOKIE[group]'";
		//execute the SQL query and return records
		$usersresult = mysql_query($usersquery);
		//FETCH and set VARIABLES needed
		while($urow = mysql_fetch_array($usersresult))
		{
		$checkbox = "check";
		echo "<div style=\"left:0px;font-size:10px;width:100%;\">";
		//FIGURE OUT IF THE USER IS ASSIGNED
		$assignquery = "SELECT * FROM assign WHERE IID_link = '$val'";
		//execute the SQL query and return records
		$assignresult = mysql_query($assignquery);
		//FETCH and set VARIABLES needed
		while($arow = mysql_fetch_array($assignresult))
		{
			if($urow[UID] == $arow[UID_link]) {
			$checkbox = "checked";
			}
		}
		$username = substr($urow["name"],0,14);
		echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"assign\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&PIID=".$piid."\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"add_assign\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$urow[UID]."\">";
		echo "<input type=\"hidden\" name=\"username\" value=\"".$username."\">";
		echo "<input type=\"hidden\" name=\"check_status\" value=\"".$checkbox."\">";
			echo "<div class=\"buttons\">";
				echo "<button type=\"submit\" class=\"".$checkbox."\">";
			        echo "&nbsp;&nbsp;&nbsp;" . $username;
				echo "</button>";
			echo "</div>";
		echo "</form>";
		echo "</div>";
		}
echo "</div>";

} else {
//CLOSE PROJECT BUTTON
echo "<div style=\"float:right;font-size:10px;\">";
	echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"state\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&pstatus=".$pstatus."&state=".$state."\">";
	echo "<input type=\"hidden\" name=\"submit_check\" value=\"close_project\">";
	echo "<input type=\"hidden\" name=\"pstatus\" value=\"".$pstatus."\">";
	//echo "<div style=\"width:350px;height:60px;\">";
	echo "<div class=\"buttons\" style=\"float:right;\">";
		echo "<button type=\"submit\" class=\"close\">";
		if ($pstatus == "") {
	        echo "Close Project";
		} else {
		echo "Re-open Project";
		}
		echo "</button>";
	//echo "</div>";
	echo "</div>";
	echo "</form>";
echo "</div>";
}

//******************************************************************************************

//END BOTTOM
echo "</div>";

//TASK DETAILS TAB END
echo "</div>";

//TASK COMMENTS TAB
	echo "<div class=\"tabbertab\">";
	echo "<h2>Comments</h2>";
	//FORM FIELDS TO ADD COMMENT
	echo "<div style=\"width:100%;height:110px;\">";
if($state == "") {
		echo "<form style=\"text-align:center;margin-bottom:0px;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&PIID=".$piid."\">";
} else {
		echo "<form style=\"text-align:center;margin-bottom:0px;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&state=".$state."\">";
}
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"addcomment\">";
		echo "<input type=\"hidden\" name=\"subject\" value=\"Comment\">";
		echo "<input type=\"hidden\" name=\"UID\" value=\"".$_COOKIE[user]."\">";
		//COMMENT SUBJECT

		echo "<div style=\"width:100%;height:100px;text-align:center;\">";
				echo "<div style=\"width:90%;\">";
					echo "<textarea name=\"comment\" rows=\"3\" id=\"textarea\" onFocus=\"doClear(this)\">";
					echo "What comments do you have...";
					echo "</textarea>";
				echo "</div>";
		echo "</div>";

		echo "<div style=\"float:right;text-align:center;\">";
		echo "<div class=\"buttons\">";
			echo "<button type=\"submit\" class=\"positive\">";
		        echo "<img src=\"/images/001_50.png\" alt=\"\">";
		        echo "Add";
			echo "</button>";
		echo "</div>";
		echo "</div>";
		//
		echo "</form>";
	echo "</div>";
	//
	//LOAD COMMENTS
	echo "<div style=\"width:100%;\">";
	echo "</div>";
	//CONTAINER FOR COMMENTS
	echo "<div style=\"width:100%;\">";
		//Grab comments related to this item
		$commentquery = "SELECT * FROM comment WHERE IID_link = '$val' GROUP BY date DESC";
		//execute the SQL query and return records
		$commentresult = mysql_query($commentquery);
		//FETCH and set VARIABLES needed
		while($crow = mysql_fetch_array($commentresult))
		{
		//QUERY USERNAME
		$userquery = "SELECT name FROM `users` WHERE UID = '$crow[UID_link]'";
		$userresult = mysql_query($userquery);
		while($urow = mysql_fetch_array($userresult))
		{
		$username = $urow[name];
		}
		echo "<font size=\"4\"><b>" . $username . ":</b></font> <font size=\"3\">".$crow[subject]."</font> <i>".$crow[date]."</i><br>";
		echo "<font size=\"4\">" . $crow[comment] . "</font><p>";
		}
		$username = "";
	echo "</div>";
	//
	echo "</div>";
//TASK COMMENTS TAB END

//LINKS TAB
		//Find Linked Items
		$linkquery = "SELECT * FROM link, item WHERE link.IID_link = $val AND item.IID = link.IID AND item.state <> 'deleted' ORDER BY item.Slevel";
		//execute the SQL query and return records
		$linkresult = mysql_query($linkquery);
		$num_rows = mysql_num_rows($linkresult);
		//FETCH and set VARIABLES needed
if($num_rows > 0) {
     echo "<div class=\"tabbertab\">";
	  echo "<h2>Items</h2>";
		while($lrow = mysql_fetch_array($linkresult))
		{
				//FIND ITEM STATUS LEVEL
				$statusquery = "SELECT * FROM status WHERE level = '$lrow[Slevel]'";
				//execute the SQL query and return records
				$statusresult = mysql_query($statusquery);
				//FETCH and set VARIABLES needed
				while($srow = mysql_fetch_array($statusresult))
				{
					$itemstatus = $srow[name];
				}
			echo "<div style=\"width:100px;font-size:20px;float:left\">";
			echo $itemstatus . " ";
			echo "</div>";
			echo "<div style=\"width:800px;font-size:20px;float:left;text-decoration:none;\">";
			echo "<a class=\"submodal\" href=\"popuptab.php?taskid=".$lrow[IID]."&project=".$task."\">".$lrow[title] . "</a><br>";
			echo "</div>";
		}
     echo "</div>";
}
//LINKS TAB END

//EDIT TASK DETAILS START
     echo "<div class=\"tabbertab\">";
	  echo "<h2>Edit</h2>";
	  include("edittask.php");
     echo "</div>";
//EDIT TASK DETAILS END

//CLOSE BUTTON
//     echo "<div onclick=\"window.top.hidePopWin();parent.location.href='index.php';\" style=\"position:absolute;top:56px;right:0px;z-index:1000;\">";
//	echo "<div class=\"buttons\">";
//		echo "<button type=\"submit\" class=\"close\">";
	        //echo "<img src=\"/images/001_05.png\" alt=\"\">";
//	        echo "Close";
//		echo "</button>";
//	echo "</div>";
  //   echo "</div>";
//

//END MASTER TABBER CONTAINER
echo "</div>";
?>
