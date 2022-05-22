<?php

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
    $this->validStart = $validStart;
    $this->validEnd = $validEnd;
  }

  public function addPublicHoliday($date)
  {
    $this->publicHolidays[] = $date;
    return $this->publicHolidays;
  }

  public function getHolidaysCount(DateTimeImmutable $start, DateTimeImmutable $end)
  {
    // validate start and end dates
    $date = new DateTime($start->format("Y-m-d"));
    $endDate = new DateTime($end->format("Y-m-h"));

    if ($this->isDateValid($date, $endDate)) {
      $publicHolidays = $this->convertStringToDateImmutable($this->publicHolidays);
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
        return array(
            "desired_start" => $start->format("d M, Y"),
            "desired_end" => $end->format("d M, Y"),
            "total-days" => $totalDays,
            "total-public-holidays-or-sundays" => $publicHolidayOrSunday,
            "total_holidays" => $this->holidaysCount,
            "last_date" => $date->format("d M, Y"),
            "message" => "Maximum length of the time span reached on " . $date->format("d M, Y"),
        );
      }
      return array(
          "desired_start" => $start->format("d M, Y"),
          "desired_end" => $end->format("d M, Y"),
          "total-days" => $totalDays,
          "total-public-holidays-or-sundays" => $publicHolidayOrSunday,
          "total_holidays" => $this->holidaysCount,
      );
    } else {
      exit ("INVALID DATES provided. Valid start date is " . $this->validStart . " and end date is " . $this->validEnd);
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

  public function isDateValid($start, $end)
  {
    $validStart = $this->convertStringToDateImmutable($this->validStart);
    $validEnd = $this->convertStringToDateImmutable($this->validEnd);

    if ($start >= $validStart && $start <= $validEnd
        && $end >= $validStart && $end <= $validEnd) {

      return true;
    }
    return false;
  }

  public function __get($name)
  {
    if($name == "publicHolidays")
    {
      return count($this->publicHolidays);
    }
  }
}

;

function main()
{
  $holidayStart = new DateTimeImmutable('30.04.2020');
  $holidayEnd = new DateTimeImmutable('25.6.2020');

  $holiday = new HolidayPlanner($validStart = "1.4.2020", $validEnd = "31.03.2021");
  $result = $holiday->getHolidaysCount($holidayStart, $holidayEnd);
  print("Total number of possible holidays in given period (" . $result['desired_start'] . " - " . $result['desired_end'] . ") = " . $result["total_holidays"]);

}

main();