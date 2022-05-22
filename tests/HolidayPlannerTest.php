<?php

require_once __DIR__. "/../app/HolidayPlanner.php";

use PHPUnit\Framework\TestCase;

class HolidayPlannerTest extends TestCase
{
  public function testIsDateValid()
  {
    $holidayPlanner = new HolidayPlanner("1.4.2020", "31.03.2021");

    $holidayStart = new DateTimeImmutable('30.04.2020');
    $holidayEnd = new DateTimeImmutable('25.6.2022');

    $this->assertFalse($holidayPlanner->isDateValid($holidayStart, $holidayEnd));
    $holidayEnd = new DateTimeImmutable('25.6.2020');

    $this->assertTrue($holidayPlanner->isDateValid($holidayStart,$holidayEnd));
  }
}
