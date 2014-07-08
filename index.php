<?php

use Sabre\VObject;
require 'vendor/autoload.php';

/**
 * Function to var_dump inside <pre>
 */
function pre_dump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

// Get data from ics-file
$path_to_ics = 'https://mail.uio.no/owa/calendar/l.h.kvale@ub.uio.no/Realfagsbiblioteket/calendar.ics';
$data = file_get_contents ($path_to_ics);
// Parse with Sabre\VObject
$vcal = VObject\Reader::read($data);

// Find iso 8601 date of today:
$today = DateTime::createFromFormat(DateTime::ISO8601, date("c"));
// Find iso 8601 date of a week from today:
$weekFromNow = DateTime::createFromFormat(DateTime::ISO8601, date("c"));
$weekFromNow->add(new DateInterval('P7D')); // Jump 7 days ahead

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Denne uka i foajeen</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>

<div id="wrapper">

<table>

<?php

// Looping through all events
foreach($vcal->vevent as $event) {
	$starts = $event->dtstart->getDateTime();
	
	// If event is between $today and $weekFromNow
	if ($starts > $today && $starts < $weekFromNow) {
		$ends = $event->dtend->getDateTime();
		echo '
			<tr>
				<th>' . $event->summary . '</th>
			</tr>
			<tr>
				<td>
					<b>' . $starts->format('Y-m-d H:i') . '</b><br>
					' . $event->description . '
				</td>
			</tr>
		';
	};
}

?>

</table>

</div>

</body>
</html>