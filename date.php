<!-- DO NOT REMOVE
Digaboard - Visual task managment web application for use with Agile/Lean practices (aka Scrum/Kanban).
Copyright (C) 2009 Brandon Ragan
This program is free and distributed WITHOUT ANY WARRANTY under the GNU General Public License (v2).  For more
information see the license file included with the source code or visit http://www.opensource.org/
DO NOT REMOVE -->
<?PHP
$daycount = 0;
$current = time();
$convertcurrent = date("m/d/Y H:i:s", $current);
$today = strtotime($convertcurrent);
$startdate = $today;
		while($daycount < 5) {
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
echo $begindate . "<br>";
echo $enddate;
?>
