<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?PHP

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
	echo $groupname;
	echo " | " . $username . " is logged in";
echo "</div>";


	//SUBMODAL TITLE DISPLAY
	  echo "<div class=\"subtitle\" title=\"CLICK TO CLOSE\" onclick=\"window.top.hidePopWin();Set_Cookie('tabber', '0', 1, '/', '', '' );parent.location.href='index.php';\">";
	  echo "Configure Options";
	  //close button
	  echo "<div class=\"close\">Close</div>";
	  echo "</div>";


//MASTER TABBER NAV CONTAINER
echo "<div style=\"position:absolute;right:0px;margin-top:8px;margin-right:5px;font-size:20px;color:red;font-weight:bold;\">" . $message . "</div>";
echo "<div class=\"tabber\">";

//EDIT STATUS LIMITS
echo "<div class=\"tabbertab\">";
	echo "<h2>Status Limits</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
if($_COOKIE[group] == ""){
	//LIMIT STATUS TITLE BOX
	echo "<div style=\"float:left;height:25px;width:100%;font-size:23px;color:red;\">Please select team from the team board first!</div>";
} else {
	//DYNAMICALY BUILD STATUS LIMIT BOXES
	echo "<div style=\"position:relative;width:100%;height:50px;\">";
		echo "<form name=\"statuslimits\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"statuslimits\">";

	//LIMIT STATUS TITLE BOX
	echo "<div style=\"float:left;height:25px;width:100%;font-size:23px;\">Define Item Status Limits for ".$groupname." <font size=2>(NOTE: 0 = unlimited and you can edit this later.)</font></div>";

	//BUILD ITEM STATUS LIMIT BOXES
	$statusquery = "SELECT * FROM status WHERE GID = '$_COOKIE[group]' GROUP BY level ASC";
	//execute the SQL query and return records
	$statusresult = mysql_query($statusquery);
	$numrows = mysql_num_rows($statusresult);
	//FETCH and set VARIABLES needed
	while($srow = mysql_fetch_array($statusresult))
	{
	echo "<div style=\"margin-left:25px;font-size:20px;float:left\">".$srow['name'].": <input type=\"input\" style=\"font-size:23px;font-style:italic;width:35px;\" name=\"limit".$srow['level']."\" value=\"".$srow['limit']."\">&nbsp;&nbsp;</div>";
	echo "<input type=\"hidden\" name=\"name".$srow['level']."\" value=\"".$srow['name']."\">";
	echo "<input type=\"hidden\" name=\"level".$srow['level']."\" value=\"".$srow['level']."\">";
	}

	echo "</form>";
	echo "</div>";

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

	//UPDATE BUTTON
	echo "<div style=\"float:right;\">";
	echo "<div title=\"Add\" onclick=\"document.statuslimits.submit();\" class=\"buttons\">";
		echo "<button type=\"submit\" class=\"positive\">";
	        echo "Update";
		echo "</button>";
	echo "</div>";
	echo "</div>";
}
//END EDIT STATUS LIMITS
echo "</div>";


//BOARD VISUALS
echo "<div class=\"tabbertab\">";
	echo "<h2>Board Visuals</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
if($_COOKIE[group] == ""){
	//Board Item Visuals no group cookie
	echo "<div style=\"float:left;height:25px;width:100%;font-size:23px;color:red;\">Please select team from the team board first!</div>";
} else {
	//DYNAMICALY BUILD Board Item Visuals
	echo "<div style=\"position:relative;width:100%;height:50px;\">";
		echo "<form name=\"boardvisuals\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"boardvisuals\">";

	//Board Item Visuals submodal title
	echo "<div style=\"float:left;height:25px;width:100%;font-size:23px;\">Board item visuals for ".$groupname."</div>";

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

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
	echo "<div style=\"margin-left:40px;font-size:20px;float:left\">".$levelname." for <input type=\"input\" style=\"font-size:20px;font-style:italic;width:38px;\" name=\"limit".$optionrow['status']."\" value=\"".$optionrow['value']."\"> days</div>";
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
	echo "<div style=\"margin-left:25px;font-size:20px;float:left\">-Done board items shown for <input type=\"input\" style=\"font-size:23px;font-style:italic;width:35px;\" name=\"donedays\" value=\"".$donedaysrow['value']."\">&nbsp;days</div>";
	}


	echo "</form>";
	echo "</div>";

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

	//UPDATE BUTTON
	echo "<div style=\"float:right;\">";
	echo "<div title=\"Add\" onclick=\"document.boardvisuals.submit();\" class=\"buttons\">";
		echo "<button type=\"submit\" class=\"positive\">";
	        echo "Update";
		echo "</button>";
	echo "</div>";
	echo "</div>";
}

//END DATES TAB
echo "</div>";

//MASTER TABBER NAV CONTAINER END
echo "</div>";
?>
