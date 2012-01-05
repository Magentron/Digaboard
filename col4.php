
<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE --><?php
//set the item status level to 1 in order to return all items within this level
//By default status level 1 is Iteration Backlog
$current_status_level = 4;
//$query = "SELECT item.IID, item.Tlevel, item.Slevel, item.title, item.state, item.priority ".
//	"FROM item ".
//	"LEFT JOIN link ON item.IID = link.IID ".
//	"WHERE item.Tlevel = $zoom_level AND item.state = 'active' AND item.Slevel = $current_status_level AND link.IID_link = '$project_id' ".
//	"ORDER BY item.priority ASC";
$query = "SELECT * ".
	"FROM item, link, `date` ".
	"WHERE item.IID = link.IID AND item.IID = date.IID_link AND date.Slevel = 4 AND item.Slevel = 4 AND item.Tlevel = $zoom_level AND item.state = 'active' AND item.Slevel = $current_status_level AND link.IID_link = '$project_id' AND date.date BETWEEN '$begindate' and '$enddate' ".
	"ORDER BY item.priority ASC";


//execute the SQL query and return records
$result = mysql_query($query);

$numRows = mysql_num_rows($result);
//if ($numRows == 0){
//	echo "no items";
//} else {

//display the results
while($row = mysql_fetch_array($result))
{

//shorten task title
if ($numRows == 1) {
	$title = substr($row["title"],0,45);
} elseif ($numRows == 2) {
	$title = substr($row["title"],0,25);
} elseif ($numRows == 3) {
	$title = substr($row["title"],0,15);
} elseif ($numRows == 4) {
	$title = substr($row["title"],0,10);
} else {
	$title = substr($row["title"],0,8);
}

$tasktitle = str_replace("'", "\'", $row["title"]);

//FIND DATES AND FIGURE OUT HOW LONG ITS BEEN ON THE BOARD

//FIND DATE ITEM ENTERED BACKLOG
//$datequery = "SELECT * FROM date WHERE IID_link = '$row[IID]' AND Slevel = $current_status_level ORDER BY Slevel";
$datequery = "SELECT * FROM date WHERE IID_link = '$row[IID]' AND Slevel = 1 ORDER BY Slevel";
//execute the SQL query and return records
$dateresult = mysql_query($datequery);
//FETCH and set VARIABLES needed
while($drow = mysql_fetch_array($dateresult))
{
//set the date variables
$leveldate = $drow['date'];
}

//FIND DATE ITEM ENTERED DONE
//$datequery = "SELECT * FROM date WHERE IID_link = '$row[IID]' AND Slevel = $current_status_level ORDER BY Slevel";
$ddatequery = "SELECT * FROM date WHERE IID_link = '$row[IID]' AND Slevel = 4 ORDER BY Slevel";
//execute the SQL query and return records
$ddateresult = mysql_query($ddatequery);
//FETCH and set VARIABLES needed
while($ddrow = mysql_fetch_array($ddateresult))
{
//set the date variables
$dleveldate = $ddrow['date'];
}


//Figure out how long task has been sitting in current status
	$daycount = 0;
//convert date to integer
$convertleveldate = strtotime($leveldate);
$convertdone = strtotime($dleveldate);
	if ($convertleveldate < $convertdone) {
		while($convertleveldate < $convertdone) {
		$day = date("D", $convertleveldate);
			if ($day == "Sun" || $day == "Sat") {
				$daycount = $daycount + 0;
			} else {
				$daycount = $daycount + 1;
			}
		$addday = mktime(0,0,0,date("m", $convertleveldate),date("d", $convertleveldate)+1,date("Y", $convertleveldate));
		$leveldate = date("n/j/Y", $addday);
		$convertleveldate = strtotime($leveldate);
		}
	}

//BOARD OPTIONS DATE QUERY
$group_id = $_COOKIE[group];
$optionsquery = "SELECT `value` FROM `options` WHERE GID = '$group_id' and `status` = '$current_status_level'";
//execute the SQL query and return records
$optionsresult = mysql_query($optionsquery);
while($optionrow = mysql_fetch_array($optionsresult))
{
	$red = $optionrow[value];
}

if ($daycount == 0){
	//blue
	$color = "#00FFFF";
} elseif ($daycount > $red) {
	//red
	$color = "#ff4040";
} else {
	//green
	$color = "#4fff42";
}

//echo "<a style=\"text-decoration:none\" class=\"submodal\" href=\"popuptab.php?taskid=". $row["IID"] ."&project=". $project ."\">";
echo "<div class=\"infobox\" onMouseover=\"ddrivetip('<div>".$tasktitle."</div><br><div style=padding:4px;background-color:black;position:absolute;bottom:0px;left:0px;text-align:left;font-size:12px;color:".$color.";width:300px;>Days to complete: ".$daycount."</div>','#ffffff', 300)\"; onMouseout=\"hideddrivetip()\">";
	echo "<div class=\"boxlink\"><a style=\"text-decoration:none\" class=\"submodal\" href=\"popuptab.php?taskid=". $row["IID"] ."\">".$title."</a></div>";
	echo "<div class=\"status\" style=\"background-color:".$color.";\" width=\"1\" align=\"center\"></div>";
echo "</div>";
//echo "</a>";
}
//}
?>
