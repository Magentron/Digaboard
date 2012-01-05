<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<?PHP
$project_id = $_GET['project'];
include("mysql.php");

$projectquery = "SELECT * FROM item WHERE IID='$project_id'";
//execute the SQL query and return records
$projectresult = mysql_query($projectquery);

//$projectrows = mssql_num_rows($projectresult);

//display the results
while($prow = mysql_fetch_array($projectresult))
{
echo "<div class=\"table\">";
	echo "<div style=\"font-size:20px;font-weight:bold;text-align:center;\">" . $project . "</div>";
echo "</div>";
}
?>
</body>
</html>
