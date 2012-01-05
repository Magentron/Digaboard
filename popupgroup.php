<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<html>
<head>
<title>
Team Selection
</title>
<link rel="stylesheet" type="text/css" href="css/tabber.css" />
<SCRIPT LANGUAGE="JavaScript">

cookie_name = "group";
var YouEntered;

function putCookie() {

if(document.cookie != document.cookie)
{index = document.cookie.indexOf(cookie_name);}
else
{ index = -1;}

if (index == -1)
{
YouEntered=document.team.select.value;
document.cookie=cookie_name+"="+YouEntered+"; expires=Monday, 04-Apr-2020 05:00:00 GMT";
}

}
</SCRIPT>
</head>
<body>
<?PHP
include('mysql.php');
echo "<div style=\"margin-top:25px;font-size:24px;font-weight:bold;\">Please select the team board you would like to view:</div>";
echo "<form style=\"margin-bottom:0px;margin-top:25px;\" name=\"team\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
//TEAM CONTAINER
	echo "<div style=\"margin-left:300px;height:50px;\">";
		//TEAM LIST ITEMS
		echo "<div style=\"float:left;\"><font size=\"4\">Team: </font></div>";
		echo "<div style=\"float:left;width:10px;\">&nbsp;</div>";
		echo "<div style=\"float:left;\">";
		echo "<select name=\"select\" STYLE=\"font-size:19pt;font-weight:bold;\">";
		$teamquery = "SELECT * FROM `group`";
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
		echo "<div style=\"float:left;width:10px;\">&nbsp;</div>";
		//SELECT BUTTON
		echo "<div style=\"float:left;\">";
			echo "<div class=\"buttons\">";
				echo "<button type=\"submit\" class=\"positive\" onclick=\"putCookie();window.top.hidePopWin();parent.location.href='index.php';\">";
			        echo "<img src=\"/images/001_06.png\" alt=\"\">";
			        echo "Select";
				echo "</button>";
			echo "</div>";
		echo "</div>";
	//END TEAM CONTAINER
	echo "</div>";

echo "</form>";

?>
</body>
</html>
