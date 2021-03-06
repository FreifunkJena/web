<?php
/**
 * Created by PhpStorm.
 * User: andi
 * Date: 27.08.14
 * Time: 21:50
 */
$eventsToShow = 4;

require('ical/class.iCalReader.php');
$ical = new ical('https://ics.freifunk.net/tags/weimar.ics');
$eventCounter = 0;
?>
<link href="/inc/agenda.css" rel="stylesheet">
<dl class="">
    <?php

    foreach ($ical->events() as $event) {
        $output = "";
        if ($eventCounter >= $eventsToShow) {
            break;
        }
        $weekdayFmt = new IntlDateFormatter('de_DE', IntlDateFormatter::FULL, IntlDateFormatter::FULL, 'Europe/Berlin', IntlDateFormatter::GREGORIAN, "eeee");
        $monthFmt = new IntlDateFormatter('de_DE', IntlDateFormatter::FULL, IntlDateFormatter::FULL, 'Europe/Berlin', IntlDateFormatter::GREGORIAN, "MMMM");
        $eventtime = new DateTime($event['DTSTART']);
        $eventtime->setTimezone(new DateTimeZone('Europe/Berlin'));
        $day = date_format($eventtime, 'd');
        $weekday = $weekdayFmt->format($eventtime);
        $month = $monthFmt->format($eventtime);
        $time = date_format($eventtime, 'H:i');
        ?>
        <dt>
            <dfn>
                <?php echo $weekday . ", " . $day . ". " . $month; ?>
            </dfn>
        </dt>
        <dd>
            <?php echo $time. " <a href=\"#\" data-toggle=\"modal\" data-target=\"#event". $eventCounter ."\">" . $event['SUMMARY'] . "</a>"; ?>
        </dd>
        <div id="event<?php echo $eventCounter;?>" class="modal fade bs-example-modal-sm" tabindex="-1">
            <div class="modal-dialog modal-sm">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                        <h4>Am <?php echo $weekday . ", " . $day . ". " . $month . " " . date_format($eventtime, 'Y') . " um " . $time . " Uhr"; ?></h4>
                    </div>
                    <div class="modal-body">
                        <h5>Was?</h5>
                        <p><?php echo $event['SUMMARY'];?></p>
                        <h5>Wo?</h5>
                        <p><?php echo $event['LOCATION'];?></p>
                        <?php
                        if (! empty($event['URL'])) { ?>
                            <h5>Mehr Informationen:</h5>
                            <p><?php echo "<a href=\"" . $event['URL'] . "\" target=\"_blank\">" . $event['URL'] . "</a>";?></p>
                        <?php
                        }
                        ?>

                    </div>
                    <div class="modal-footer">
                        <a href="http://ics.freifunk.net/tags/weimar" target="_blank">Alle Termine</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    </div>
                </div>
            </div>
        </div>

        <?php

        /*if ($event['URL']) {
            $output .= "<a href=\"" . $event['URL'] . "\">" . $event['SUMMARY'] . "</a>";
        } else {
            $output .= "" . $event['SUMMARY'];
        }
        $output .= "</dd><dd>Ort: " . $event['LOCATION']."</dd>";
        echo $output;*/
        $eventCounter++;
        ?>
    <?php
    }
    ?>
</dl>
