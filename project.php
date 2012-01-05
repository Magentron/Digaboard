<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?php
if($zoom_level == 0) {
//BUILD GROUP COLUMNS IF ZOOM LEVEL IS 0

//check if group cookie is set to the current group in the loop and set var to indicate the selected team on the board
$selected_groupA = "";
$selected_groupB = "";
if($_COOKIE[group] == $group_id) {
if($_COOKIE[state] == active) {
$selected_groupA = "sel";
} elseif($_COOKIE[state] == inactive) {
$selected_groupB = "sel";
}
}
//echo "<a href=\"index.php\">";
echo "<div class=\"grouptop\">";
echo "<div class=\"".$selected_groupA."roundbox-tr\">";
echo "<div class=\"".$selected_groupA."roundbox-tl\">";
echo "<div class=\"".$selected_groupA."roundbox-br\">";
echo "<div class=\"".$selected_groupA."roundbox-bl\">";
echo "<div class=\"roundbox-content\">";
echo "<p>";
echo "<a class=\"submodal\" href=\"popupteam.php?group=".$group."&GID=".$group_id."\">";
echo "<div class=\"title\">";
echo $group;
echo "</div>";
echo "</a>";
include("active.php");
echo "</p>";

echo "<a href=\"index.php\" onclick=\"Set_Cookie('state', 'active', 30, '/', '', '' );Set_Cookie('group', '".$group_id."', 30, '/', '', '' );Set_Cookie('zoom', '0', 30, '/', '', '' );\">";
echo "<div style=\"z-index:1;position:absolute;margin:0px;left:0px;height:90%;width:100%;cursor:pointer;\"></div>";
echo "</a>";

echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
//echo "</a>";

//echo "<a href=\"index.php\">";
echo "<div class=\"groupbottom\">";
echo "<div class=\"".$selected_groupB."roundbox-tr\">";
echo "<div class=\"".$selected_groupB."roundbox-tl\">";
echo "<div class=\"".$selected_groupB."roundbox-br\">";
echo "<div class=\"".$selected_groupB."roundbox-bl\">";
echo "<div class=\"roundbox-content\">";
echo "<p>";
include("inactive.php");
echo "</p>";

echo "<a href=\"index.php\" onclick=\"Set_Cookie('state', 'inactive', 30, '/', '', '' );Set_Cookie('group', '".$group_id."', 30, '/', '', '' );Set_Cookie('zoom', '0', 30, '/', '', '' );\">";
echo "<div style=\"z-index:1;position:absolute;margin:0px;left:0px;height:90%;width:100%;cursor:pointer;\"></div>";
echo "</a>";

echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
//echo "</a>";

} else {
//calculate date range for sql query
$daycount = 0;
$current = time();
$convertcurrent = date("m/d/Y H:i:s", $current);
$today = strtotime($convertcurrent);
$startdate = $today;

//BOARD OPTIONS DATE QUERY
$group_id = $_COOKIE[group];
$optionsquery = "SELECT `value` FROM `options` WHERE GID = '$group_id' and `option` = 'date'";
//execute the SQL query and return records
$optionsresult = mysql_query($optionsquery);
while($optionrow = mysql_fetch_array($optionsresult))
{
	$datecount = $optionrow[value];
}

		while($daycount < $datecount) {
		$day = date("D", $startdate);
		//echo $day . "<br>";
			if ($day == "Sun" || $day == "Sat") {
				$daycount = $daycount + 0;
			} else {
				$daycount = $daycount + 1;
			}
		$minusday = mktime(0,0,0,date("m", $startdate),date("d", $startdate)-1,date("Y", $startdate));
		$convertstartdate = date("m/d/Y H:i:s", $minusday);
		$startdate = strtotime($convertstartdate);
		}
$begindate = date("Y-m-d H:i:s", $startdate);
$enddate = date("Y-m-d H:i:s", $today);
//echo $begindate . "<br>";
//echo $enddate;

//convert dates to readable format
$displaybegindate = date("m/d", $startdate);
$displayenddate = date("m/d", $today);

//DYNAMICALLY BUILD PROJECT COLUMNS IF ZOOM LEVEL IS > 0

//QUERY FOR STATUS LEVELS AND BUILD BOARD COLUMNS
$projectcolq = "SELECT * FROM `status` WHERE `level` > '0' AND GID = '$_COOKIE[group]' ORDER BY level DESC";
$projectcolr = mysql_query($projectcolq);
$projectcolnumrows = mysql_num_rows($projectcolr);
$count = $projectcolnumrows;
while($projectcolrows = mysql_fetch_array($projectcolr)) {
	if($projectcolrows[level] >= 3) {
	$projectbox = "top";
	} else {
	$projectbox = "bottom";
	}
$statuslimit = $projectcolrows['limit'];
	//CHECK HOW MANY ITEMS ARE AT CURRENT BUTTON STATUS LEVEL *************************************************
	$itemsnumq = "SELECT * FROM link, item WHERE link.IID_link = $project_id AND item.IID = link.IID AND item.Slevel = $count AND item.state <> 'deleted'";
	$itemsnumr = mysql_query($itemsnumq);
	$itemsnumrows = mysql_num_rows($itemsnumr);
	
	$over = "";
	if ($statuslimit != 0 && $itemsnumrows > $statuslimit) {
	$over = "over";
	}

	echo "<div class=\"project".$projectbox."\">";
	echo "<div class=\"".$over."roundbox-tr\">";
	echo "<div class=\"".$over."roundbox-tl\">";
	echo "<div class=\"".$over."roundbox-br\">";
	echo "<div class=\"".$over."roundbox-bl\">";
	echo "<div class=\"roundbox-content\">";
	echo "<p>";

	if($projectcolnumrows == $count) {
	//PROJECT PRIORITY
	if($rowcount != 1) {
	echo "<form name=\"priorityup".$rowcount."\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
	echo "<input type=\"hidden\" name=\"submit_check\" value=\"up\">";
	echo "<input type=\"hidden\" name=\"projectid\" value=\"".$project_id."\">";
	$prioritypushed = $priority - 1;
	$projectupq = "SELECT * FROM `item` WHERE GID = '$_COOKIE[group]' AND `priority` = $prioritypushed";
	$projectupr = mysql_query($projectupq);
	while($projectuprows = mysql_fetch_array($projectupr)) {
	echo "<input type=\"hidden\" name=\"projectidpushed\" value=\"".$projectuprows[IID]."\">";
	}

	echo "<div style=\"cursor:pointer;position:absolute;left:5px;top:4px;\" onclick=\"document.priorityup".$rowcount.".submit();\"><img src=\"images/001_59_lt.png\" border=\"0\"></div>";
	echo "</form>";
	} else {
	echo "<div style=\"position:absolute;left:5px;top:4px;\"><img src=\"images/001_59_lt_gray.png\" border=\"0\"></div>";
	}
	echo "<a class=\"submodal\" href=\"popuptab.php?taskid=".$project_id."&state=".$state."\">";
	echo "<div class=\"title\">";
	echo "<div>".$project."</div>";
	echo "</div>";
	echo "</a>";
	if($projectrows != $rowcount) {
	echo "<form name=\"prioritydown".$rowcount."\" style=\"margin:0;\" method=\"POST\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
	echo "<input type=\"hidden\" name=\"submit_check\" value=\"down\">";
	echo "<input type=\"hidden\" name=\"projectid\" value=\"".$project_id."\">";
	$prioritypushed = $priority + 1;
	$projectupq = "SELECT * FROM `item` WHERE GID = '$_COOKIE[group]' AND `priority` = $prioritypushed";
	$projectupr = mysql_query($projectupq);
	while($projectuprows = mysql_fetch_array($projectupr)) {
	echo "<input type=\"hidden\" name=\"projectidpushed\" value=\"".$projectuprows[IID]."\">";
	}

	echo "<div style=\"cursor:pointer;position:absolute;right:5px;top:4px;\" onclick=\"document.prioritydown".$rowcount.".submit();\"><img src=\"images/001_59_rt.png\" border=\"0\"></div>";
	echo "</form>";
	} else {
	echo "<div style=\"position:absolute;right:5px;top:4px;\"><img src=\"images/001_59_rt_gray.png\" border=\"0\"></div>";
	}
	}

	include("col$count.php");
	if ($over == "over"){
	echo "<div title=\"Over queue limit! Too many items!\" style=\"position:absolute;right:4px;bottom:0px;\"><img src=\"images/001_30.png\" border=\"0\"></div>";
	}
	echo "</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
$count--;
}
}

if($zoom_level > 0) {
  include("includepoolitems.php");
  $nn = 1;
if($poolnumRows == 0){
$message = "Add Pool Items?";
} else {
$message = $poolnumRows . " Pool Items";
}

while($poolrow = mysql_fetch_array($poolresult))
{
if ($projectrows == 1){
	$pooltasktitle = substr($poolrow[title],0,50);
} elseif ($projectrows == 2){
	$pooltasktitle = substr($poolrow[title],0,45);
} elseif ($projectrows == 3){
	$pooltasktitle = substr($poolrow[title],0,35);
} elseif ($projectrows == 4){
	$pooltasktitle = substr($poolrow[title],0,30);
} elseif ($projectrows == 5){
	$pooltasktitle = substr($poolrow[title],0,25);
} elseif ($projectrows == 6){
	$pooltasktitle = substr($poolrow[title],0,20);
} elseif ($projectrows == 7){
	$pooltasktitle = substr($poolrow[title],0,15);
} else {
	$pooltasktitle = substr($poolrow[title],0,10);
}
$item[$nn] = $pooltasktitle;

$itemfull[$nn] = str_replace("'", "\'", $poolrow[title]);
$itemid[$nn] = $poolrow[IID];
$nn = $nn+1;
}

$poolpercent = $percent - 2;

echo "<div class=\"poolbox\" style=\"width:".$percent."%;\">";
	echo $message;
	echo "<div class=\"more\">";
		for ( $counter = 1; $counter <= $poolnumRows; $counter++) {
		echo "<a style=\"text-decoration:none;\" onMouseover=\"ddrivetip('".$itemfull[$counter]."','#ffffff', 300)\"; onMouseout=\"hideddrivetip()\" class=\"submodal\" href=\"popuptab.php?taskid=".$itemid[$counter]."&project=".$project."\"><div class=\"item\">" . $item[$counter] . "</div></a>";
		}
		echo "<a style=\"text-decoration:none;\" onMouseover=\"ddrivetip('Add New Item?','#ffffff', 300)\"; onMouseout=\"hideddrivetip()\" class=\"submodal\" href=\"popupadd.php?project=".$project."\"><div class=\"item\">Add New Item?</div></a>";
	echo "</div>";
echo "</div>";

}
?>
