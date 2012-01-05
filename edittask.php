<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?PHP
$query = "SELECT * FROM item WHERE IID = '$val'";

//execute the SQL query and return records
$result = mysql_query($query);

//FETCH and set VARIABLES needed
while($row = mysql_fetch_array($result))
{
//task details
$task = $row['title'];
$acceptance = $row['description'];
$priority = $row['priority'];
}

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

//TEAM DROP DOWN BOX
echo "<div style=\"position:relative;width:100%;height:50px;\">";

//TEAM FORM POST
if($state == "") {
  echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"update_team\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "\">";
} else {
  echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"update_team\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&state=".$state."\">";
  echo "<input type=\"hidden\" name=\"old_gid\" value=\"".$groupowner."\">";
}
  echo "<input type=\"hidden\" name=\"submit_check\" value=\"update_team\">";

		//TEAM DROP DOWN
		echo "<div style=\"float:left;width:120px;\"><font size=\"4\">Team:</font></div>";
		echo "<div style=\"float:left;\">";
		echo "<select name=\"selectteam\" STYLE=\"font-size:24pt;font-weight:bold;\" onChange=\"document.forms['update_team'].submit();\">";
		$teamquery = "SELECT * FROM `group` WHERE state IS NULL";
		//execute the SQL query and return records
		$teamresult = mysql_query($teamquery);
		while($grow = mysql_fetch_array($teamresult))
		{
		$teamlist = $grow[name];
			if($groupowner == $grow[GID]){
			$teamselected = "SELECTED ";
			} else {
			$teamselected = "";
			}
		echo "<option ".$teamselected."value=\"" . $grow[GID] . "\"><font size=8>". $teamlist ."</font></option>";
		}
		echo "</select>";
		echo "</div>";
  echo "</form>";

//END TEAM DROP DOWN BOX
echo "</div>";

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

//PROJECT DROP DOWN
if($state == "") {
//PROJECT FORM POST -- UPDATE ON SELECTION
echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"update_project\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "\">";
echo "<input type=\"hidden\" name=\"submit_check\" value=\"update_project\">";

//PROJECT NAME CONTAINER
echo "<div style=\"position:relative;width:100%;height:50px;\">";
	if($groupowner != $project_GID && $_POST[submit_check] != "update_project") {
		$color="red";
	}
		echo "<div style=\"float:left;width:120px;\"><font size=\"4\" color=\"".$color."\">Project:</font></div>";
		echo "<div style=\"float:left;width:200px;\">";
  echo "<select name=\"projectdropdown\" STYLE=\"font-size:24pt;font-weight:bold;\" onChange=\"document.forms['update_project'].submit();\">";
	echo "<option value=\"sp\"><font size=\"8\">--Select Project--</font></option>";
	$projectquery = "SELECT * FROM item WHERE Tlevel = 0 AND GID = $groupowner AND Edate IS NULL";
	//execute the SQL query and return records
	$projectresult = mysql_query($projectquery);
	while($prow = mysql_fetch_array($projectresult))
	{
	$projectlist = $prow["title"];
	if ($project == $projectlist) {
		$selected = " SELECTED ";
		$projectname = $prow[title];
	} else {
		$selected = " ";
	}
	echo "<option" . $selected . "value=\"" . $prow[IID] . "\"><font size=8>". $projectlist ."</font></option>";
	}
  echo "</select>";
	echo "<input type=\"hidden\" name=\"projectname\" value=\"".$projectname."\">";
		echo "</div>";
  echo "</div>";
}
  echo "</form>";

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

if($state == "") {
  echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"update\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "\">";
} else {
  echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"update\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&state=".$state."\">";
}
  echo "<input type=\"hidden\" name=\"submit_check\" value=\"updatedetails\">";

	//TASK TITLE
	echo "<div style=\"float:left;text-align:left;width:100%;\">";
	echo "<input type=\"input\" style=\"align:left;font-size:23px;font-weight:bold;width:700px;\" name=\"updatetitle\" value=\"". $task ."\">";
	echo "</div>";

	//ACCEPTANCE TEXTAREA CONTAINER
	echo "<div style=\"width:95%;\">";
		echo "<div style=\"float:right;width:50px;\"></div>";
		echo "<div style=\"float:right;width:900px;\">";
		  echo "<textarea name=\"updateacceptance\" rows=\"3\" id=\"textarea\">";
		  echo $acceptance;
		  echo "</textarea>";
		echo "</div>";
	echo "</div>";

	//SPACER
	echo "<div style=\"float:left;height:20px;width:100%;\"></div>";

//UPDATE BUTTON
echo "<div style=\"float:right;\">";
	echo "<div class=\"buttons\">";
		echo "<button type=\"submit\" class=\"positive\">";
	        echo "<img src=\"/images/001_06.png\" alt=\"\">";
	        echo "Update";
		echo "</button>";
	echo "</div>";
echo "</form>";
echo "</div>";

//DELETE BUTTON AND FORM
echo "<div style=\"float:left;\">";
if($state == "") {
  echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"delete\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&PIID=".$piid."\">";
} else {
  echo "<form style=\"text-align:center;margin-bottom:0px;\" name=\"delete\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "?taskid=" . $val . "&state=".$state."\">";
}
	echo "<input type=\"hidden\" name=\"submit_check\" value=\"deletetask\">";
	echo "<div class=\"buttons\">";
		echo "<button type=\"submit\" class=\"negative\">";
	        echo "<img src=\"/images/001_02.png\" alt=\"\">";
	        echo "Delete";
		echo "</button>";
	echo "</div>";
	echo "</form>";  
echo "</div>";
?>
