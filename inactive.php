<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?php
//set the item status level to 1 in order to return all items within this level
//By default status level 1 is Iteration Backlog
$current_status_level = 1;
$query = "SELECT item.IID, item.Tlevel, item.Slevel, item.title, item.state, item.priority ".
	"FROM item ".
	"LEFT JOIN link ON item.IID = link.IID ".
	"WHERE item.Tlevel = 0 AND item.state = 'inactive' AND GID = $group_id AND item.Edate IS NULL ".
	"ORDER BY item.priority ASC";

//execute the SQL query and return records
$result = mysql_query($query);

$numRows = mysql_num_rows($result);
//if ($numRows == 0){
//	echo "no items";
//} else {

//display the results
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
//shorten task title
if ($projectrows == 1){
		$title = substr($row["title"],0,50);
} elseif ($projectrows == 2){
		$title = substr($row["title"],0,50);
} elseif ($projectrows == 3){
	if ($numRows == 1) {
		$title = substr($row["title"],0,50);
	} elseif ($numRows == 2) {
		$title = substr($row["title"],0,50);
	} elseif ($numRows == 3) {
		$title = substr($row["title"],0,50);
	} elseif ($numRows == 4) {
		$title = substr($row["title"],0,50);
	} else {
		$title = substr($row["title"],0,40);
	}
} elseif ($projectrows == 4){
	if ($numRows == 1) {
		$title = substr($row["title"],0,50);
	} elseif ($numRows == 2) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 3) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 4) {
		$title = substr($row["title"],0,35);
	} else {
		$title = substr($row["title"],0,25);
	}
} elseif ($projectrows == 5){
	if ($numRows == 1) {
		$title = substr($row["title"],0,50);
	} elseif ($numRows == 2) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 3) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 4) {
		$title = substr($row["title"],0,25);
	} else {
		$title = substr($row["title"],0,15);
	}
} elseif ($projectrows == 6){
	if ($numRows == 1) {
		$title = substr($row["title"],0,50);
	} elseif ($numRows == 2) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 3) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 4) {
		$title = substr($row["title"],0,25);
	} else {
		$title = substr($row["title"],0,10);
	}
} elseif ($projectrows == 7){
	if ($numRows == 1) {
		$title = substr($row["title"],0,50);
	} elseif ($numRows == 2) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 3) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 4) {
		$title = substr($row["title"],0,25);
	} else {
		$title = substr($row["title"],0,15);
	}
} else {
	if ($numRows == 1) {
		$title = substr($row["title"],0,35);
	} elseif ($numRows == 2) {
		$title = substr($row["title"],0,25);
	} elseif ($numRows == 3) {
		$title = substr($row["title"],0,20);
	} elseif ($numRows == 4) {
		$title = substr($row["title"],0,15);
	} else {
		$title = substr($row["title"],0,10);
	}
}
$tasktitle = str_replace("'", "\'", $row["title"]);
echo "<a style=\"text-decoration:none\" class=\"submodal\" href=\"popuptab.php?taskid=". $row["IID"] ."&state=". $row["state"] ."\">";
echo "<div class=\"infobox\" onMouseover=\"ddrivetip('<div>".$tasktitle."</div>','#ffffff', 200)\"; onMouseout=\"hideddrivetip()\">";
	echo "<div>".$title."</div>";
echo "</div>";
echo "</a>";
}
//}
?>
