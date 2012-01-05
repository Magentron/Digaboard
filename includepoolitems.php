<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?PHP
//set the item status level to 0 in order to return all items within this level
//By default status level 1 is Backlog
$current_status_level = 0;

$poolquery = "SELECT item.IID, item.Tlevel, item.Slevel, item.title, item.state, item.priority ".
	"FROM item ".
	"LEFT JOIN link ON item.IID = link.IID ".
	"WHERE item.Tlevel = 1 AND item.state = 'active' AND item.Slevel = 0 AND link.IID_link = '$project_id' ".
	"ORDER BY item.priority ASC";

//execute the SQL query and return records
$poolresult = mysql_query($poolquery);

$poolnumRows = mysql_num_rows($poolresult);
?>
