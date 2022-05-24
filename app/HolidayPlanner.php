<?php
include('DateObjectActions.php');

class HolidayPlanner
{
  const MAX_TIME_SPAN = 50;

  private $publicHolidays = ["1.1.2020", "6.1.2020", "10.04.2020", "13.04.2020", "1.5.2020", "21.5.2020", "19.6.2020", "24.12.2020",
      "25.12.2020", "1.1.2021", "6.1.2021", "2.4.2021", "5.4.2021", "13.5.2021", "20.6.2021", "6.12.2021", "24.12.2021"];
  private $holidaysCount;

  private $validStart;

  private $validEnd;

  public function __construct($validStart = "", $validEnd = "")
  {
    $this->holidaysCount = 0;
    $this->validStart = DateObjectActions::convertStringToDateImmutable($validStart);
    $this->validEnd = DateObjectActions::convertStringToDateImmutable($validEnd);
  }

  public function addPublicHoliday(String $date): array
  {
    $this->publicHolidays[] = $date;
    return $this->publicHolidays;
  }

  public function getHolidaysCount(DateTimeImmutable $start, DateTimeImmutable $end): int
  {
    $date = new DateTime($start->format("Y-m-d"));

    if (DateObjectActions::isTimeSpanValid($start, $end) && DateObjectActions::areDatesValid($start, $end, $this->validStart, $this->validEnd)) {
      $publicHolidays = DateObjectActions::convertStringToDateImmutable($this->publicHolidays);

      $publicHolidayOrSunday = 0;
      $actualHolidaysCount = 0;

      // total number of days in given dates
      $totalDays = $start->diff($end)->days + 1;

      while ($date <= $end) {

        if (in_array($date, $publicHolidays) || $this->isSunday($date)) {
          $publicHolidayOrSunday++;
        } else {
          $actualHolidaysCount++;

          if ($actualHolidaysCount == self::MAX_TIME_SPAN) {
            break;
          }
        }

        $date = $date->add(new DateInterval('P1D'));
      }

      $this->holidaysCount = $actualHolidaysCount;

      if ($this->holidaysCount >= self::MAX_TIME_SPAN) {
        // to do
      }
      return $this->holidaysCount;
    } else {
      exit ("INVALID DATES provided. Valid start date is " . $this->validStart . " and end date is " . $this->validEnd);
    }
  }

  public function isSunday(DateTime $date): bool
  {
    return $date->format('D') == 'Sun';
  }

  public function __get(String $name): int
  {
    if ($name == "publicHolidays") {
      return count($this->publicHolidays);
    }
  }

  public static function getMaxTimeSpan():int
  {
    return self::MAX_TIME_SPAN;
  }
}

;

