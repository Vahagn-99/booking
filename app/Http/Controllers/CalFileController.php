<?php

namespace App\Http\Controllers;

use App\Models\Icals;
use App\Models\Properties;
use App\Models\Reservations;
use Redirect;
use ICal\ICal;

class CalFileController
{

    public function export($id = null)
    {
        if ($id) {
            $calendars = Icals::where('property', $id)->get();
            foreach ($calendars as $cal) {
                $this->delete($id, $cal->name, $cal->link);
                $this->import($id, $cal->name, $cal->link);
            }
        }

        $reservations = Reservations::where('property', $id)->get();

        $ical = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//BookingFWI manager//Booking calendar 1.0//EN\r\nCALSCALE:GREGORIAN\r\n";
        foreach ($reservations as $reservation) {
            $eventStart = "BEGIN:VEVENT";
            $eventIdentifier = "UID:" . explode(".", $reservation->reservation_check_in)[2] . "" . explode(".", $reservation->reservation_check_in)[1] . "" . explode(".", $reservation->reservation_check_in)[0] . "@bookingfwimanager";
            $eventDateStart = "DTSTART;VALUE=DATE:" . explode(".", $reservation->reservation_check_in)[2] . "" . explode(".", $reservation->reservation_check_in)[1] . "" . explode(".", $reservation->reservation_check_in)[0];
            $eventDateEnd = "DTEND;VALUE=DATE:" . explode(".", $reservation->reservation_check_out)[2] . "" . explode(".", $reservation->reservation_check_out)[1] . "" . explode(".", $reservation->reservation_check_out)[0];
            $eventCreated = "DTSTAMP:" . explode(".", $reservation->reservation_check_in)[2] . "" . explode(".", $reservation->reservation_check_in)[1] . "" . explode(".", $reservation->reservation_check_in)[0] . "T" . date("His") . "Z";
            $property = Properties::findOrFail($id);
            $eventSummary = "SUMMARY:" . $property->name;
            $eventDescription = "DESCRIPTION:" . $property->name;
            $eventEnd = "END:VEVENT";
            $ical .= $eventStart . "\r\n" . $eventIdentifier . "\r\n" . $eventDateStart . "\r\n" . $eventDateEnd . "\r\n" . $eventCreated . "\r\n" . $eventSummary . "\r\n" . $eventDescription . "\r\n" . $eventEnd . "\r\n";
        }

        $ical .= "END:VCALENDAR";
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename=' . $id . 'calendar.ics');
        echo $ical;
    }

    public function import($id, $icalName, $icalLink)
    {
        // Import and create reservations for it
        $parsedCalendar = null;
        try {
            $parsedCalendar = new ICal($icalLink, array(
                'defaultSpan' => 2,     // Default value
                'defaultTimeZone' => 'UTC',
                'defaultWeekStart' => 'MO',  // Default value
                'disableCharacterReplacement' => false, // Default value
                'filterDaysAfter' => null,  // Default value
                'filterDaysBefore' => null,  // Default value
                'skipRecurrence' => false, // Default value
            ));
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['msg' => 'Ical link is blocked.']);
        }
        if (Icals::where('link', $icalLink)->first() != null) {
            $oldIcal = Icals::where('link', $icalLink)->first();
            $oldIcal->delete();
        }
            $ical = new Icals;
            $ical->name = $icalName;
            $ical->link = $icalLink;
            $ical->property = $id;
            $ical->updated = date('d/m/Y H:i:s');
            $ical->save();



        foreach ($parsedCalendar->events() as $event) {
            if (Reservations::where('property', $id)
                    ->where('reservation_check_in', date("d.m.Y", strtotime($event->dtstart)))
                    ->first() != null) {
                continue;
            }

            $reservation = new Reservations;
            $reservation->property = $id;
            $reservation->reservation_time = date("d/m/Y H:i:s P", strtotime($event->created));
            $reservation->reservation_check_in = date("d.m.Y", strtotime($event->dtstart));
            $reservation->reservation_check_out = date("d.m.Y", strtotime($event->dtend));
            $reservation->type = "ical" . $ical->id;
            $reservation->service = $ical->name;

            $reservation->save();
        }
    }

    public function delete($id, $icalName, $icalLink)
    {
        // Delete all reservations made from this calendar
        $oldCalendar = Icals::where('property', $id)
            ->where('name', $icalName)
            ->where('link', $icalLink)
            ->first();

        $reservationsToDelete = Reservations::where('type', 'ical' . $oldCalendar->id)
            ->where('property', $oldCalendar->property)
            ->get();
        foreach ($reservationsToDelete as $reservation) {
            $reservation->delete();
        }
        $oldCalendar->delete();
    }
}
