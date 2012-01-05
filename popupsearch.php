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
<title>Search</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
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
include("mysql.php");
$statuscheck = $_POST[submit_check];

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
	if ($groupname == "") {
	echo "<font color=\"red\">ERROR - Select team</font>";
	} else {
	echo $groupname;
	}
	echo " | " . $username . " is logged in";
echo "</div>";


	//SUBMODAL TITLE DISPLAY
	  echo "<div class=\"subtitle\" title=\"CLICK TO CLOSE\" onclick=\"window.top.hidePopWin();Set_Cookie('tabber', '0', 1, '/', '', '' );parent.location.href='index.php';\">";
	  echo "Search...";
	  //close button
	  echo "<div class=\"close\">Close</div>";
	  echo "</div>";


//MASTER TABBER NAV CONTAINER
echo "<div style=\"position:absolute;right:0px;margin-top:8px;margin-right:5px;font-size:20px;color:red;font-weight:bold;\">" . $message . "</div>";
echo "<div class=\"tabber\">";
//BUILD TABS FOR EACH SEARCH ELEMENT
$tabarr = array("Task");
reset($tabarr);
foreach ($tabarr as $searching) {

// Search
echo "<div class=\"tabbertab\">";
	echo "<h2>$searching</h2>";
	//SPACER
	echo "<div style=\"height:10px;\"></div>";
if($_COOKIE[group] == ""){
	//Board Item Visuals no group cookie
	echo "<div style=\"float:left;height:25px;width:100%;font-size:23px;color:red;\">Please select team from the team board first!</div>";
} else {
	//BUILD SEARCH USER TAB
	echo "<div style=\"position:relative;width:100%;\">";
		echo "<form name=\"search".$searching."\" style=\"margin:0;\" method=\"GET\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
			echo "<input type=\"hidden\" name=\"submit_check\" value=\"".$searching."\">";
			echo "<div style=\"text-align:center;width:100%;height:50px;font-size:23px;\">";
			echo "<input type=\"input\" style=\"font-size:23px;font-style:italic;width:300px;\" name=\"searchterm\" value=\"".$_GET['searchterm']."\">";
				//GO BUTTON
					echo "<button type=\"submit\">";
				        echo "Go";
					echo "</button>";
			echo "</div>";
		echo "</form>";
	echo "</div>";

	//PRINT THE SEARCH RESULTS
	echo "<div style=\"float:left;height:300px;width:100%;\">";
		include("search.php");
	echo "</div>";
}
//END USER Search
echo "</div>";

//END FOR CONDITION
}

//MASTER TABBER NAV CONTAINER END
echo "</div>";
?>
</body>
</html>
