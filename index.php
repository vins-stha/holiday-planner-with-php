<?php

class HolidayPlanner
{
  private $publicHolidays = ["1.1.2020", "6.1.2020", "10.04.2020", "13.04.2020", "1.5.2020", "21.5.2020", "19.6.2020", "24.12.2020",
      "25.12.2020", "1.1.2021", "6.1.2021", "2.4.2021", "5.4.2021", "13.5.2021", "20.6.2021", "6.12.2021", "24.12.2021"];
  private $holidaysCount;

  private $validStart;

  private $validEnd;

  public function __construct($validStart = "", $validEnd = "")
  {
    $this->holidaysCount = 0;
    $this->validStart = $validStart;
    $this->validEnd = $validEnd;
  }

  public function addPublicHoliday($date)
  {
    $this->publicHolidays[] = $date;
  }

  public function getHolidaysCount(DateTimeImmutable $start, DateTimeImmutable $end)
  {
    // validate start and end dates
    $date = new DateTime($start->format("Y-m-d"));
    $endDate = new DateTime($end->format("Y-m-h"));

    if ($this->isDateValid($date, $endDate)) {
      $publicHolidays = $this->convertStringToDateImmutable($this->publicHolidays);
      $publicHolidayOrSunday = 0;

      // total number of days in given dates
      $totalDays = $start->diff($end)->days + 1;

      while ($date <= $end) {

        if (in_array($date, $publicHolidays) || $this->isSunday($date)) {
          $publicHolidayOrSunday++;
        }
        $date = $date->add(new DateInterval('P1D'));
      }

      $this->holidaysCount = $totalDays - $publicHolidayOrSunday;

      return array(
          "start" => $start,
          "end" => $end,
          "total-days" => $totalDays,
          "total-public-holidays-or-sundays" => $publicHolidayOrSunday,
          "total-holidays" => $this->holidaysCount
      );
    } else {
      var_dump("INVALID DATES");
      return [];
    }
  }

  public function convertStringToDateImmutable($holidays)
  {
    if (is_array($holidays)) {
      $convertedDates = [];
      foreach ($holidays as $holiday) {
        $convertedDates[] = new DateTimeImmutable($holiday);
      }
      return $convertedDates;
    } else {
      return new DateTimeImmutable($holidays);;
    }

  }

  public function isSunday($date)
  {
    return $date->format('D') == 'Sun';
  }

  protected function isDateValid($start, $end)
  {
    $validStart = $this->convertStringToDateImmutable($this->validStart);
    $validEnd = $this->convertStringToDateImmutable($this->validEnd);

    if ($start >= $validStart && $start <= $validEnd
        && $end >= $validStart && $end <= $validEnd) {

      return true;
    }
    return false;

  }
}

$start = new DateTimeImmutable('1.03.2020');
$end = new DateTimeImmutable('1.4.2021');

$holiday = new HolidayPlanner($validStart = "1.4.2020", $validEnd = "31.03.2021");
$result = $holiday->getHolidaysCount($start, $end);
var_dump($result['total-holidays']);





