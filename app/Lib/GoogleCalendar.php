<?php

namespace App\Lib;

use App\Models\Appointment;
use Carbon, Google_Client, Google_Service_Calendar, Google_Service_Calendar_Event, Google_Service_Calendar_EventDateTime;

class GoogleCalendar
{
    public static function addEvent(array $eventParams = [])
    {
        $event = new Google_Service_Calendar_Event($eventParams);

        return static::getService()->events->insert(static::getCalendarId(), $event);
    }

    public static function deleteEvent(string $eventId)
    {
        return static::getService()->events->delete(static::getCalendarId(), $eventId);
    }

    public static function getEvent(string $eventId)
    {
        return static::getService()->events->get(static::getCalendarId(), $eventId);
    }

    public static function updateEvent(string $eventId, array $eventParams)
    {
        $event = new Google_Service_Calendar_Event($eventParams);

        return static::getService()->events->update(static::getCalendarId(), $eventId, $event);
    }

    public static function getCalendarId()
    {
        return config('services.google_calendar.calendar_id');
    }

    public static function flushCalendar()
    {
        foreach (static::getService()->events->listEvents(static::getCalendarId()) as $event) {
            static::deleteEvent($event->id);
        }
    }

    public static function getService()
    {
        $client = new Google_Client();
        $client->setApplicationName(config('app.name'));
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(config('services.google_calendar.client_secret_file'));
        $client->setAccessType('offline');

        $accessTokenFile = config('services.google_calendar.access_token_file');
        $accessToken = json_decode(file_get_contents($accessTokenFile), true);

        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($accessTokenFile, json_encode($client->getAccessToken()));
        }

        return new Google_Service_Calendar($client);
    }
}
