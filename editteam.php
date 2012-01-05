<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?PHP
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
	echo $username . " is logged in";
echo "</div>";


	//TEAM TITLE DISPLAY
	  echo "<div class=\"subtitle\" title=\"CLICK TO CLOSE\" onclick=\"window.top.hidePopWin();parent.location.href='index.php';\">";
	  echo $group;
	  echo "<div class=\"close\">Close</div>";
	  echo "</div>";

//DISPLAY UPDATE MESSAGES
echo "<div style=\"position:absolute;right:0px;margin-top:15px;font-size:12px;color:red;font-weight:bold;\">" . $message . "</div>";

//MASTER TABBER NAV CONTAINER START
echo "<div class=\"tabber\">";

//MEMBERS TAB
echo "<div class=\"tabbertab\">";
	echo "<h2>Members</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
	//CONTENT
	$userquery = "SELECT * FROM users WHERE GID='$group_id'";
	//execute the SQL query and return records
	$userresult = mysql_query($userquery);
	$numrows = mysql_num_rows($userresult);
	//display the results
	if($numrows == 0) {
	echo "<div class=\"table\">";
		echo "<div style=\"font-size:20px;font-weight:bold;text-align:center;\">No Team Members Assigned to Team. <a href=\"popupadd.php\">Add Members?</a></div>";
	echo "</div>";	
	} else {
	$rowcount = 0;
	while($urow = mysql_fetch_array($userresult))
	{
	$rowcount++;
	if($odd = $rowcount%2) {
	$rowcolor = "background-color:#ffffbf;";
	} else {
	$rowcolor = "";
	}
	echo "<div style=\"position:relative;width:100%;height:50px;".$rowcolor."\">";
	//EDIT MEMBER FORM
	echo "<form name=\"edit".$urow['UID']."user\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?group=".$group."&GID=".$group_id."\">";
		echo "<div style=\"float:left;width:150px;padding-right:5px;\"><input type=\"input\" style=\"font-size:20px;font-style:italic;width:200px;\" name=\"username\" value=\"" . $urow['name'] . "\"></div>";
		echo "<div style=\"float:left;width:200px;padding-right:5px;\"><input type=\"input\" style=\"font-size:20px;font-style:italic;width:200px;\" name=\"email\" value=\"" . $urow['email'] . "\"></div>";
		echo "<div style=\"float:left;width:50px;padding-right:5px;\"><font size=\"4\">Team: </font></div>";
		echo "<div style=\"float:left;\">";
		echo "<select name=\"teamid\" STYLE=\"font-size:20px;font-weight:bold;\">";
		$teamquery = "SELECT * FROM `group`";
		//execute the SQL query and return records
		$teamresult = mysql_query($teamquery);
		while($grow = mysql_fetch_array($teamresult))
		{
		$teamprint = substr($grow[name],0,16);
		$teamlist = $grow[GID];
			if($group_id == $teamlist) {
			$teamselected = "SELECTED ";
			} else {
			$teamselected = "";
			}
		echo "<option ".$teamselected."value=\"" . $grow[GID] . "\"><font size=8>". $teamprint ."</font></option>";
		}
		echo "</select>";
		echo "</div>";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"edituser\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$urow['UID']."\">";
	echo "</form>";
	//DELETE MEMBER FORM
	echo "<form name=\"del".$urow['UID']."user\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?group=".$group."&GID=".$group_id."\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"deluser\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$urow['UID']."\">";
	echo "</form>";
		echo "<div style=\"float:left;width:150px;padding-left:10px;\">";
			//UPDATE BUTTON
			echo "<div style=\"float:left;\">";
			echo "<div title=\"Update\" onclick=\"document.edit".$urow['UID']."user.submit();\" class=\"buttons\">";
				echo "<button type=\"submit\" class=\"positive\">";
			        echo "Update";
				echo "</button>";
			echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "<div style=\"float:left;width:150px;padding-left:5px;\">";
			//DELETE BUTTON
			echo "<div style=\"float:left;\">";
			echo "<div title=\"Delete\" onclick=\"document.del".$urow['UID']."user.submit();\" class=\"buttons\">";
				echo "<button type=\"submit\" class=\"negative\">";
			        echo "Delete";
				echo "</button>";
			echo "</div>";
			echo "</div>";
		echo "</div>";

	echo "</div>";
	}
	echo "<div class=\"table\">";
		echo "<div style=\"font-size:20px;font-weight:bold;text-align:right;\"><a class=\"submodal\" href=\"popupadd.php\">Add New Members?</a></div>";
	echo "</div>";
	}
//END MEMBERS TAB
echo "</div>";

//CLOSED PROJECTS TAB
echo "<div class=\"tabbertab\">";
	echo "<h2>Closed Projects</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";
//CONTENT
$closedquery = "SELECT item.IID, item.GID, item.Tlevel, item.Slevel, item.title, item.state, item.priority, item.Cdate, item.Edate ".
	"FROM item ".
	"WHERE item.Tlevel = 0 AND item.gid = '$group_id' AND item.Edate IS NOT NULL ".
	"ORDER BY item.priority ASC";
//execute the SQL query and return records
$closedresult = mysql_query($closedquery);
$closedrows = mysql_num_rows($closedresult);
//display the results
echo "<div style=\"width:100%;height:22px;text-decoration:underline;\">";
echo "<div style=\"float:left;font-size:20px;width:150px;height:22px;\">Project Name</div><div style=\"float:left;font-size:20px;width:250px;height:22px;\">Created On</div><div style=\"float:left;font-size:20px;width:250px;height:22px;\">Closed On</div><br>";
echo "</div>";

while($crow = mysql_fetch_array($closedresult))
{
echo "<div style=\"width:100%;height:22px;\">";
echo "<div style=\"float:left;font-size:20px;width:150px;height:22px;\">" . $crow[title] . "</div><div style=\"float:left;font-size:20px;width:250px;height:22px;\">" . $crow[Cdate] . "</div><div style=\"float:left;font-size:20px;width:250px;height:22px;\">" . $crow[Edate] . "</div><br>";
echo "</div>";
}
//END CLOSED PROJECTS TAB
echo "</div>";


//EDIT TEAM TAB
echo "<div class=\"tabbertab\">";
	echo "<h2>Edit</h2>";
	//SPACER
	echo "<div style=\"height:20px;\"></div>";

	//Team container
	echo "<div style=\"position:relative;width:100%;height:50px;\">";
		echo "<form name=\"editteam\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?group=".$group."&GID=".$group_id."\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"editteam\">";
		echo "<input type=\"hidden\" name=\"team_id\" value=\"".$group_id."\">";
	echo "<div style=\"width:100%;\">";
		echo "<input type=\"input\" style=\"font-size:23px;font-style:italic;width:700px;\" name=\"group\" value=\"".$group."\">";
	echo "</div>";

	//SPACER
	echo "<div style=\"float:left;height:10px;width:100%;\"></div>";

	//LIMIT STATUS TITLE BOX
	echo "<div style=\"height:40px;width:100%;font-size:23px;\">Define Item Status Limits for ".$group." <font size=2>(NOTE: 0 = unlimited and you can edit this later.)</font></div>";

	echo "<div style=\"height:40px;width:100%;\">";
	//BUILD ITEM STATUS LIMIT BOXES
	$statusquery = "SELECT * FROM status WHERE GID = '$group_id' GROUP BY level ASC";
	//execute the SQL query and return records
	$statusresult = mysql_query($statusquery);
	$numrows = mysql_num_rows($statusresult);
	//FETCH and set VARIABLES needed
	while($srow = mysql_fetch_array($statusresult))
	{
	echo "<div style=\"margin-left:25px;font-size:20px;float:left\">".$srow['name'].": <input type=\"input\" style=\"font-size:23px;font-style:italic;width:25px;\" name=\"limit".$srow['level']."\" value=\"".$srow['limit']."\">&nbsp;&nbsp;</div>";
	echo "<input type=\"hidden\" name=\"name".$srow['level']."\" value=\"".$srow['name']."\">";
	echo "<input type=\"hidden\" name=\"level".$srow['level']."\" value=\"".$srow['level']."\">";
	}
	echo "</div>";

	echo "</form>";
	echo "</div>";

	//SPACER
	echo "<div style=\"float:left;height:100px;width:100%;\"></div>";

	echo "<form name=\"delteam\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?group=".$group."&GID=".$group_id."\">";
		echo "<input type=\"hidden\" name=\"submit_check\" value=\"delteam\">";
		echo "<input type=\"hidden\" name=\"team_id\" value=\"".$group_id."\">";
	echo "</form>";

	//UPDATE BUTTON
	echo "<div style=\"float:right;\">";
	echo "<div title=\"Update\" onclick=\"document.editteam.submit();\" class=\"buttons\">";
		echo "<button type=\"submit\" class=\"positive\">";
	        echo "Update";
		echo "</button>";
	echo "</div>";
	echo "</div>";

	//DELETE BUTTON
	echo "<div style=\"float:left;\">";
	echo "<div title=\"Delete\" onclick=\"document.delteam.submit();\" class=\"buttons\">";
		echo "<button type=\"submit\" class=\"negative\">";
	        echo "Delete";
		echo "</button>";
	echo "</div>";
	echo "</div>";


//END EDIT TEAM TAB
echo "</div>";


//MASTER TABBER NAV CONTAINER END
echo "</div>";

?>
