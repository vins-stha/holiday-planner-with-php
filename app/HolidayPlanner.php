<?php
include('ValidateItem.php');
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
    $this->validStart = ValidateItem::convertStringToDateImmutable($validStart);
    $this->validEnd = ValidateItem::convertStringToDateImmutable($validEnd);
  }

  public function addPublicHoliday(String $date):array
  {
    $this->publicHolidays[] = $date;
    return $this->publicHolidays;
  }

  public function getHolidaysCount(DateTimeImmutable $start, DateTimeImmutable $end):int
  {
    $date = new DateTime($start->format("Y-m-d"));

    if(ValidateItem::areDatesValid($start, $end, $this->validStart, $this->validEnd))
    {
      $publicHolidays = ValidateItem::convertStringToDateImmutable($this->publicHolidays);
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

  public function isSunday(DateTime $date):bool
  {
    return $date->format('D') == 'Sun';
  }

  public function __get(String $name):int
  {
    if ($name == "publicHolidays") {
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
  print("Total number of possible holidays in given period (" . $holidayStart->format('Y-m-d') . " - " . $holidayEnd->format('Y-m-d') . ") = " . $result . "\n");

}

main();