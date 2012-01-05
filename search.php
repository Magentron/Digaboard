<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?PHP
if ($_GET['submit_check'] != "") {
echo "<div style=\"position:relative;width:100%;background-color:#999999;color:#ffffff;font-size:14px;font-weight:bold;\">";
	//QUERY FOR SEARCH
	$searchq = "SELECT * FROM item WHERE title LIKE '%$_GET[searchterm]%' AND Tlevel != 0 AND state = 'active' GROUP BY title ASC";
	//execute the SQL query and return records
	$searchr = mysql_query($searchq);
	$numrows = mysql_num_rows($searchr);
	echo $numrows . " items found for &quot;" . $_GET['searchterm'] . "&quot;</div><p>";
	//FETCH and PRINT RESULTS OF SEARCH STRING
	while($srow = mysql_fetch_array($searchr))
	{
		$shortdescription = substr($srow['description'],0,100);
		echo "<div style=\"font-size:14px;font-weight:bold;\"><a class=\"submodal\" href=\"popuptab.php?taskid=$srow[IID]\">" . $srow['title'] . "</a></div><div style=\"font-size:14px;font-weight:normal;\">" . $shortdescription . "</div>";
		//Find project link and pull project name and set project variable
		$pquery = "SELECT item.title, item.IID, item.GID FROM link, item WHERE link.IID = '$srow[IID]' AND item.IID = link.IID_link";
		//execute the SQL query and return records
		$presult = mysql_query($pquery);
		while($plrow = mysql_fetch_array($presult))
		{
			//QUERY TEAM INFO
			$groupq = "SELECT * FROM `group` WHERE GID = '$plrow[GID]'";
			//execute the SQL query and return records
			$groupr = mysql_query($groupq);
			while($searchgroup = mysql_fetch_array($groupr))
			{
				$searchrteamname = $searchgroup[name];
			}
		echo "<div style=\"font-size:14px;color:green;\">" . $searchrteamname . " >> " . $plrow[title] . "</div><p>";
		}
	}
}
?>
