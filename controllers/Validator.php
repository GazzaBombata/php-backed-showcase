<?php
class Validator
{
    public static function validateId($id)
    {
        if (!isset($id)) {
            throw new InvalidArgumentException('missing ID');
        }
        if (!is_numeric($id)) {
            throw new InvalidArgumentException('invalid ID, it must be a number');
        }
    }

    public static function validateTitle($data)
    {
        if (!isset($data['title'])) {
            throw new InvalidArgumentException('missing title');
        }
        if (!is_string($data['title'])) {
            throw new InvalidArgumentException('invalid title, it must be a string');

        }
    }

    public static function validatePlaces($places)
    {
        if (!isset($places)) {
            throw new InvalidArgumentException('missing places');
        }
        if (!is_numeric($places)) {
            throw new InvalidArgumentException('invalid places, it must be a number');
        }
    }

    public static function validateNation($nation_id, $nation)
    {
        $result = $nation->get($nation_id);
        if ($result === false) {
            throw new InvalidArgumentException('Nation ID ' . $nation_id. ' not found');
        }
    }

    public static function validateTrip($trip_id, $trip)
    {

        if (!$trip->get($trip_id)) {
            throw new InvalidArgumentException('Trip ID ' . $trip_id . ' not found');
        }

    }
}
