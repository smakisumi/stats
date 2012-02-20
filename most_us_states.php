<?php
$title = 'Most US states';
include('assets/templates/header.tpl.php');

// form
$cur = 'most_us_states';
include('assets/forms/beginform.php');
include('assets/forms/years.php');
include('assets/forms/submit.php');
include('assets/forms/endform.php');

// build query
$query = "SELECT   personId, personName, personCountryId, COUNT( DISTINCT state ) count
FROM     Results
         JOIN (
               SELECT id, SUBSTR( cityName, LOCATE(  ',', cityName ) +2 ) AS state
               FROM Competitions
               WHERE countryId =  'USA'
              ) states\n";
if ($_GET['years'] != '')
  $query .= "         JOIN Competitions ON Results.competitionId = Competitions.id\n";
$query .= "WHERE    states.id = Results.CompetitionId
GROUP BY personId
ORDER BY count DESC
LIMIT    100";

// submit query
$result = dbQuery($query);

// table heading
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='results'>\n";
echo "<thead>"
    ."<th class='r'>Rank</th>"
    ."<th>Person</th>"
    ."<th>Country</th>"
    ."<th>States</th>"
    ."<th class='f'>&nbsp;</th>"
    ."</thead>\n";

// write data
$rank = 0;
while($row = mysql_fetch_array($result)) {
  $rank++;
  // handle tie
  if ($row['count'] == $row_prev['count'])
    $rank_to_show = "&nbsp;";
  else
    $rank_to_show = $rank;
  $row_prev = $row;
  // write row
  echo "<tr>";
  echo "<td class='r'>" . $rank_to_show . "</td>";
  echo "<td><a class='p' href='p.php?i=". $row['personId'] ."'>" . $row['personName'] . "</a></td>";
  echo "<td>" . $row['personCountryId'] . "</td>";
  echo "<td class='r'>" . $row['count'] . "</td>";
  echo "<td class='f'>&nbsp;</td>";
  echo "</tr>\n";
}
echo "</table>\n\n";
?>


<h2>MySQL Query</h2>
<p>Based on query by Tim Reynolds.</p>
<pre><?php echo $query; ?></pre>

<?php
include('assets/templates/footer.tpl.php');
?>
