<?php

class DateObjectActions
{
  public static function areDatesValid(DateTimeImmutable $start, DateTimeImmutable $end,
                                       DateTimeImmutable $validStart, DateTimeImmutable $validEnd): bool
  {
    if ($start >= $validStart && $start <= $validEnd
        && $end >= $validStart && $end <= $validEnd) {

      return true;
    }
    return false;
  }

  public static function convertStringToDateImmutable($dates)
  {
    if (is_array($dates)) {
      $convertedDates = [];
      foreach ($dates as $date) {
        $convertedDates[] = new DateTimeImmutable($date);
      }
      return $convertedDates;
    } else {
      return new DateTimeImmutable($dates);
    }
  }

  public static function getFullDate(String $date): DateTimeImmutable
  {
    if (strpos($date, ".")) {
      $delimeter = ".";
    }
    if (strpos($date, "-")) {
      $delimeter = "-";
    }
    if (strpos($date, "/")) {
      $delimeter = "/";
    }

    $dateArray = explode($delimeter, $date);

    $year = strval($dateArray[1]) >= 4 ? "2020" : "2021";

    $holidayStart = new DateTimeImmutable($date . $delimeter . $year);

    return $holidayStart;
  }

}