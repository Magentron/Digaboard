<?PHP
$comingfrom = $_GET['project'];

//QUERY TEAM INFO
$group_id = $_COOKIE[group];
$groupquery = "SELECT * FROM `group` WHERE GID = '$group_id'";
//execute the SQL query and return records
$groupresult = mysql_query($groupquery);
while($grouprow = mysql_fetch_array($groupresult))
{
	$groupname = $grouprow[name];
}
//QUERY LOGGED IN USER NAME
$userquery = "SELECT name ".
"FROM `users` ".
"WHERE UID = $_COOKIE[user]";
$userresult = mysql_query($userquery);
while($userrow = mysql_fetch_array($userresult))
{
	$username = $userrow[name];
}


echo "<div style=\"left:0;top:0px;margin-left:5px;height:12px;font-size:12px;color:#999999;font-weight:bold;\">";
	if ($_COOKIE[group] == "") {
	echo "<font color=\"red\">ERROR: No team selected!</font>";
	} else {
	echo $groupname;
	}
	if($comingfrom != "") {
	echo " >> " . $comingfrom;
	}
	echo " | " . $username . " is logged in";
echo "</div>";


	//TEAM TITLE DISPLAY
  echo "<div class=\"subtitle\" title=\"CLICK TO CLOSE\" onclick=\"window.top.hidePopWin();parent.location.href='index.php';\">";
	  echo "Add new...";
	  //close button
	  echo "<div class=\"close\">Close</div>";
	  echo "</div>";

if ($_COOKIE[group] == "") {
	echo "<div style=\"text-align:center;font-size:20px;padding-top:40px;\">";
	echo "No team has been selected! Close this add dialog and select a team from the team board.";
	echo "</div>";
} else {

//MASTER TABBER NAV CONTAINER
echo "<div style=\"position:absolute;right:0px;margin-top:15px;font-size:12px;color:red;font-weight:bold;\">" . $message . "</div>";
echo "<div class=\"tabber\">";

//GET TYPE LEVELS AND NAMES TO BUILD TABS
$typequery = "SELECT * FROM type GROUP BY level DESC";
//execute the SQL query and return records
$typeresult = mysql_query($typequery);
while($trow = mysql_fetch_array($typeresult))
{
//BUILD TABS AND CONTENT
echo "<div class=\"tabbertab\">";
	echo "<h2>" . $trow[name] . "</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";

	//PROJECT AND STATUS CONTAINER
	echo "<div style=\"position:relative;width:100%;height:50px;\">";
		echo "<form name=\"add".$trow[name]."\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"" . $trow[level] . "\">";
	
	//dont display in top level tab (level 0)
	if($trow[level] != 0) {
		//PROJECT LIST ITEMS
		echo "<div style=\"float:left;width:120px;font-size:18px;\">Add ".$trow[name]." Into:</div>";
		echo "<div style=\"float:left;\">";
		echo "<select name=\"addtoproject\" STYLE=\"font-size:24pt;font-weight:bold;\">";
		//Menu selection of type level 0 items
		//By default type level 0 items are Projects
		//$menu_type_level = 0;

		//set the item status level to 1 in order to return all items within this level
		//By default status level 1 is Iteration Backlog
		//$current_status_level = 2;

		$projectquery = "SELECT item.IID, item.GID, item.Edate, item.Tlevel, item.Slevel, item.title, item.state, item.priority ".
			"FROM item ".
			"WHERE item.Tlevel = 0 AND item.GID = $_COOKIE[group] AND item.Edate IS NULL ".
			"ORDER BY item.title ASC";
		//execute the SQL query and return records
		$projectresult = mysql_query($projectquery);
		$projectnum = mysql_num_rows($projectresult);
		while($prow = mysql_fetch_array($projectresult, MYSQL_ASSOC))
		{
		$projectlist = $prow[title];

		if($_POST[addtoproject] == "") {
			//check what project add task was clicked from to set proper project
			if($comingfrom == $prow[title]){
			$projectselected = "SELECTED ";
			} else {
			$projectselected = "";
			}

		} else {
			//check last added task project name to set proper project
			if($_POST[addtoproject] == $prow[IID]){
			$projectselected = "SELECTED ";
			} else {
			$projectselected = "";
			}
		}

		echo "<option ".$projectselected."value=\"" . $prow[IID] . "\"><font size=8>". $projectlist ."</font></option>";
		}
		echo "</select>";
		echo "</div>";
		echo "<div style=\"float:left;width:10px;\">&nbsp;</div>";
	} else {
		echo "<input type=\"hidden\" name=\"addtoproject\" value=\"0\">";
	}

	//dont display in top level tab (level 0)
	if($trow[level] != 0) {
		//STATUS LIST
		echo "<div style=\"float:left;\"><font size=\"4\">Status: </font></div>";
		echo "<div style=\"float:left;\">";
		echo "<select name=\"addstatus\" STYLE=\"font-size:24pt;font-weight:bold;\">";
		$statusquery = "SELECT * ".
			"FROM status ".
			" WHERE GID = '$_COOKIE[group]' ORDER BY level ASC";
		//execute the SQL query and return records
		$statusresult = mysql_query($statusquery);
		while($srow = mysql_fetch_array($statusresult, MYSQL_ASSOC))
		{
		$projectlist = $srow[title];
			//check last added task project name to set proper project
			if($_POST[addstatus] == $srow[level]){
			$statusselected = "SELECTED ";
			} else {
			$statusselected = "";
			}
		echo "<option ".$statusselected."value=\"" . $srow[level] . "\"><font size=8>". $srow['name'] ."</font></option>";
		}
		echo "</select>";
		echo "</div>";
		echo "<input type=\"hidden\" name=\"setstate\" value=\"active\">";
	} else {
	//SHOW STATE IF TOP LEVEL (ACTIVE/INACTIVE)
		echo "<div style=\"float:left;width:120px;font-size:18px;\">State:</div>";
		echo "<div style=\"float:left;\">";
		echo "<select name=\"setstate\" STYLE=\"font-size:24pt;font-weight:bold;\">";
			echo "<option SELECTED value=\"active\"><font size=8>Active</font></option>";
			echo "<option value=\"inactive\"><font size=8>Inactive</font></option>";
		echo "</select>";
		echo "</div>";
	}
	//END PROJECT AND STATUS CONTAINER
	echo "</div>";

//TEAM AND CREATOR CONTAINER
	echo "<div style=\"position:relative;width:100%;height:50px;\">";
		//TEAM LIST ITEMS
		echo "<div style=\"float:left;width:120px;font-size:18px\">Team:</div>";
		echo "<div style=\"float:left;width:10px;\"></div>";
		echo "<div style=\"float:left;width:120px;font-size:22px;color:#999999;\">".$groupname."</div>";
		echo "<input type=\"hidden\" name=\"selectteam\" value=\"".$_COOKIE[group]."\">";
		//CREATOR LIST ITEMS
		echo "<div style=\"float:left;width:120px;font-size:18px\">Created By:</div>";
		echo "<div style=\"float:left;width:10px;\"></div>";
		echo "<div style=\"float:left;width:120px;font-size:22px;color:#999999;\">".$username."</div>";
		echo "<input type=\"hidden\" name=\"UID\" value=\"".$_COOKIE[user]."\">";
	echo "</div>";
	
	//ONLY SHOW IF PROJECT LEVEL
	if($trow[level] == 0) {
	//SET PRIORITY VAR
		$priority = $projectnum + 1;
		echo "<input type=\"hidden\" name=\"priority\" value=\"".$priority."\">";
	}

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

	//TASK TITLE
	echo "<div style=\"width:100%;\">";
		echo "<input type=\"input\" style=\"font-size:23px;font-style:italic;width:700px;\" name=\"addtitle\" value=\"Enter ".$trow[name]." Name...\" onFocus=\"doClear(this)\">";
	echo "</div>";

	//ACCEPTANCE TEXTAREA CONTAINER
	echo "<div style=\"width:98%;text-align:center;\">";
		echo "<div style=\"float:right;width:50px;\"></div>";
		echo "<div style=\"float:right;width:900px;\">";
		echo "<textarea name=\"addacceptance\" rows=\"3\" id=\"textarea\" onFocus=\"doClear(this)\">";
		echo "What defines this ".$trow[name]." as being done...";
		echo "</textarea>";
		echo "</div>";
	echo "</div>";
	//FORM HAS TO END BEFORE BUTTON OR IE7 DOUBLE SUBMITS
	echo "</form>";
	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

	//ADD BUTTON
	echo "<div style=\"float:right;\">";
	echo "<div title=\"Add\" onclick=\"document.add".$trow[name].".submit();\" class=\"buttons\">";
		echo "<button type=\"submit\" class=\"positive\">";
	        echo "Add";
		echo "</button>";
	echo "</div>";

	echo "</div>";

//END BUILD TABS AND CONTENT
echo "</div>";
}

//ADD TEAM TAB
echo "<div class=\"tabbertab\">";
	echo "<h2>Team</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
	//Team container
	echo "<div style=\"position:relative;width:100%;height:50px;\">";
		echo "<form name=\"addteam\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"addteam\">";
	echo "<div style=\"width:100%;\">";
		echo "<input type=\"input\" style=\"font-size:23px;font-style:italic;width:700px;\" name=\"addteam\" value=\"Enter New Team Name...\" onFocus=\"doClear(this)\">";
	echo "</div>";
	echo "</div>";
	//SPACER
	echo "<div style=\"float:left;height:10px;width:100%;\"></div>";

	//LIMIT STATUS TITLE BOX
	echo "<div style=\"float:left;height:25px;width:100%;font-size:23px;\">Define Item Status Limits <font size=2>(NOTE: 0 = unlimited and you can edit this later.)</font></div>";

	//BUILD ITEM STATUS LIMIT BOXES
	$statusquery = "SELECT * FROM status WHERE GID = '$_COOKIE[group]' GROUP BY level ASC";
	//execute the SQL query and return records
	$statusresult = mysql_query($statusquery);
	$numrows = mysql_num_rows($statusresult);
	//FETCH and set VARIABLES needed
	while($srow = mysql_fetch_array($statusresult))
	{
	echo "<div style=\"margin-left:25px;font-size:20px;float:left\">".$srow['name'].": <input type=\"input\" style=\"font-size:23px;font-style:italic;width:25px;\" name=\"limit".$srow['level']."\" value=\"0\">&nbsp;&nbsp;</div>";
	echo "<input type=\"hidden\" name=\"name".$srow['level']."\" value=\"".$srow['name']."\">";
	echo "<input type=\"hidden\" name=\"level".$srow['level']."\" value=\"".$srow['level']."\">";
	}

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

	//Board Item Visuals submodal title
	echo "<div style=\"float:left;height:25px;width:100%;font-size:23px;\">Board item visuals</div>";

	//SPACER
	echo "<div style=\"float:left;height:2px;width:100%;\"></div>";

	//ITEMS STATUS IDICATOR TURNS RED AFTER X AMOUNT OF DAYS
	echo "<div style=\"margin-left:25px;font-size:20px;float:left\">";
	echo "-Show red visual queue after item has been idle in ";
	echo "</div>";
	//SPACER
	echo "<div style=\"float:left;height:1px;width:100%;\"></div>";
	//BOARD OPTIONS DATE QUERY
	$group_id = $_COOKIE[group];
	$optionsquery = "SELECT * FROM `options` WHERE GID = '$group_id' and `option` = 'item'";
	//execute the SQL query and return records
	$optionsresult = mysql_query($optionsquery);
	while($optionrow = mysql_fetch_array($optionsresult))
	{
		//grab level name
		$levelnameq = "SELECT `name` FROM `status` WHERE GID = '$group_id' and `level` = '$optionrow[status]'";
		//execute the SQL query and return records
		$levelnamer = mysql_query($levelnameq);
		while($levelrow = mysql_fetch_array($levelnamer))
		{
			$levelname = $levelrow['name'];
		}
	echo "<div style=\"margin-left:40px;font-size:20px;float:left\">".$levelname." for <input type=\"input\" style=\"font-size:20px;font-style:italic;width:38px;\" name=\"limit".$optionrow['status']."\" value=\"5\"> days</div>";
	}

	//SPACER
	echo "<div style=\"float:left;height:10px;width:100%;\"></div>";

	//Board Done status item display for X amount of days
	//BOARD OPTIONS DATE QUERY
	$donedaysq = "SELECT * FROM `options` WHERE GID = '$group_id' and `option` = 'date'";
	//execute the SQL query and return records
	$donedaysr = mysql_query($donedaysq);
	while($donedaysrow = mysql_fetch_array($donedaysr))
	{
	echo "<div style=\"margin-left:25px;font-size:20px;float:left\">-Done board items shown for <input type=\"input\" style=\"font-size:23px;font-style:italic;width:35px;\" name=\"donedays\" value=\"5\">&nbsp;days</div>";
	}

	echo "</form>";
	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";
	//ADD BUTTON
	echo "<div style=\"float:right;\">";
	echo "<div title=\"Add\" onclick=\"document.addteam.submit();\" class=\"buttons\">";
		echo "<button type=\"submit\" class=\"positive\">";
	        echo "Add";
		echo "</button>";
	echo "</div>";
	echo "</div>";

//END ADD TEAM TAB
echo "</div>";

//ADD TEAM MEMBERS TAB
echo "<div class=\"tabbertab\">";
	echo "<h2>Members</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
echo "<form name=\"adduser\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
	//user container
	echo "<div style=\"width:100%;\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"adduser\">";
		echo "<input type=\"input\" style=\"font-size:23px;font-style:italic;width:700px;\" name=\"username\" value=\"Enter Team Members Name...\" onFocus=\"doClear(this)\">";
	echo "</div>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
	echo "<div style=\"width:100%;\">";
		echo "<input type=\"input\" style=\"font-size:23px;font-style:italic;width:700px;\" name=\"email\" value=\"Enter Email Address...\" onFocus=\"doClear(this)\">";
	echo "</div>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
	echo "<div style=\"width:100%;\">";
		//TEAM LIST ITEMS
		echo "<div style=\"float:left;width:120px;\"><font size=\"4\">Member of: </font></div>";
		echo "<div style=\"float:left;\">";
		echo "<select name=\"teamid\" STYLE=\"font-size:24pt;font-weight:bold;\">";
		$teamquery = "SELECT * FROM `group` WHERE state is NULL";
		//execute the SQL query and return records
		$teamresult = mysql_query($teamquery);
		while($grow = mysql_fetch_array($teamresult))
		{
		$teamlist = $grow[name];
			if($_POST[selectteam] == $teamlist){
			$teamselected = "SELECTED ";
			} else {
			$teamselected = "";
			}
		echo "<option ".$teamselected."value=\"" . $grow[GID] . "\"><font size=8>". $teamlist ."</font></option>";
		}
		echo "</select>";
		echo "</div>";
	echo "</div>";
echo "</form>";
	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

	//ADD BUTTON
	echo "<div style=\"float:right;\">";
	echo "<div title=\"Add\" onclick=\"document.adduser.submit();\" class=\"buttons\">";
		echo "<button type=\"submit\" class=\"positive\">";
	        echo "Add";
		echo "</button>";
	echo "</div>";
	echo "</div>";

//END ADD TEAM MEMBERS TAB
echo "</div>";

//MASTER TABBER NAV CONTAINER END
echo "</div>";
}
?>
