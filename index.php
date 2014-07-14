<?php

use Sabre\VObject;
require 'vendor/autoload.php';

/**
 * Function to var_dump inside <pre>. Useful if you want to display more
 * information about your event: use pre_dump($vcal) to see what's inside.
 */
function pre_dump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

// How many days ahead should the calender show events for?
$days = 1;
if (isset($_GET['days'])) $days = abs(intval($_GET['days']));

// For some reason I have to do this to make the specified $days display
// events that are $days ahead of today. Must be something I don't understand
// when using DateTime diff in lines 68-74.
$days--;


// Config
$path_to_ics = 'https://mail.uio.no/owa/calendar/l.h.kvale@ub.uio.no/Realfagsbiblioteket/calendar.ics';
$title_text = 'Vilhelm Bjerknes Hus';
$header_text = '<img src="UiO_UBREAL_Segl_A.png" alt="Vilhelm Bjerknes Hus"><br><br> Hva skjer?';
$time_text = '';
$activity_text = '';
$none_found_text = 'Ingen aktiviteter funnet';

// Get data and parse
$data = file_get_contents ($path_to_ics);
$vcal = VObject\Reader::read($data);

// Create DateTime-object of today
$today = new DateTime();

// Array for norwegian month names
$mnds = array(
	"januar",
	"februar",
	"mars",
	"april",
	"nai",
	"juni",
	"juli",
	"august",
	"september",
	"oktober",
	"november",
	"desember"
);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?= $title_text ?></title>
    <link rel="stylesheet" href="style.css" />
    <script type="text/javascript" src="clock.js"></script>
    <link type="image/x-icon" href="favicon.ico" rel="shortcut icon" />
    <link type="image/x-icon" href="favicon.ico" rel="icon" />
</head>

<body>

<div id="bg">
	<img src="haeckel_new.png" alt="">
</div>

<div id="wrapper">

<table class="infoscreen-caption">
	<tr>
		<td class="infoscreen-left"><?= $header_text ?></td>
	</tr>
</table>


<table class="smaller" >

<?php

$i = 0; // Used to see how many events are displayed

// Looping through all events
foreach($vcal->vevent as $event) {
	$starts = $event->dtstart->getDateTime();
	
	// Get DateTime for the difference between today and the event start time
	$interval = $today->diff($starts, false);
	// Get the difference in days as a number
	$diff = (int)$interval->format("%r%a");

	// Now we can display only events that are <= $days ahead of today
	if ($diff > 0 && $diff <= $days) {
		$ends = $event->dtend->getDateTime();
		$mnd_index = (int) $starts->format('n') - 1;
		echo '
		<tr>
			<td class="time">' .
				$starts->format('d') . '. ' . $mnds[$mnd_index] . '<br>' .
				$starts->format('H:i') . ' - ' .
				$ends->format('H:i') . '
			</td>
			<th>' .
				$event->summary . '
			</th>
		</tr>
		';

		$i++;
	};
}

// No events displayed?
if ($i === 0) {
	echo '
	<tr>
		<td style="padding: 20px;">' . $none_found_text . '</td>
	</tr>';
}

?>

</table>

</div>
</body>

</html>