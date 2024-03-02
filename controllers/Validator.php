<?php
class Validator {
    public static function validateId($id) {
      if (!isset($id)) {
        throw new InvalidArgumentException('missing ID');
    }
        if (!is_numeric($id)) {
            throw new InvalidArgumentException('invalid ID, it must be a number');
        }
    }

    public static function validateTitle($data) {
        if (!isset($data['title'])) {
            throw new InvalidArgumentException('missing title');
        }
        if (!is_string($data['title'])) {
          throw new InvalidArgumentException('invalid title, it must be a string');

    }
    }

    public static function validatePlaces($places) {
        if (!isset($places)) {
            throw new InvalidArgumentException('missing places');
        }
        if (!is_numeric($places)) {
            throw new InvalidArgumentException('invalid places, it must be a string');
        }
    }

    public static function validateNations($nations, $nationController) {
      if (!isset($nations)) {
          throw new InvalidArgumentException('missing nations');
      }
      if (!is_array($nations)) {
          throw new InvalidArgumentException('invalid nations, it must be an array');
      }
      foreach ($nations as $n) {
          if (!is_numeric($n)) {
              throw new InvalidArgumentException('invalid nation, it must be a number');
          }
          if ($nationController->get($n) != $n) {
              throw new InvalidArgumentException('invalid nation, ID '.$n.' must be a valid nation');
          }
      }
  }
  } 
