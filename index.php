<?php
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: inline; filename=ffxivmaintenance.ics');

    $wrapper = "BEGIN:VCALENDAR
PRODID:-//VIMASTER/FFXIV//MAINTENANCE//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:Final Fantasy XIV Maintenance
X-WR-CALDESC:Calendar containing special events. Wrapper for http://www.xenoveritas.org/static/ffxiv/timers.json
X-MS-OLK-FORCEINSPECTOROPEN:TRUE
%s
END:VCALENDAR";

    function eventAssignment($event)
    {
        $eventWrapper = "BEGIN:VEVENT
CLASS:PUBLIC
UID:%s
DTSTAMP:%s
ORGANIZER;CN=%s:MAILTO:%s
DTSTART:%s
DTEND:%s
SUMMARY:%s
END:VEVENT";

        $event->start = gmdate('Ymd\THis\Z', $event->start/1000);
        $event->end = gmdate('Ymd\THis\Z', $event->end/1000);

        return sprintf(
            $eventWrapper,
            md5($event->type.$event->start.$event->end),
            $event->start,
            "SquareEnix",
            "undefined@example.org",
            $event->start,
            $event->end,
            strip_tags($event->name)
        );
    };

    $URL = "http://www.xenoveritas.org/static/ffxiv/timers.json";
    
    $document = json_decode(file_get_contents($URL));

    printf($wrapper, implode("\r\n", array_map("eventAssignment", $document->timers)));
?>