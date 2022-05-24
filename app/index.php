<?php
//
include('HolidayPlanner.php');


function main()
{
  $userInput_StartDateMonth = "30.06";
  $userInput_EndDateMonth = "15.08";

  $holidayStart = DateObjectActions::getFullDate($userInput_StartDateMonth);
  $holidayEnd = DateObjectActions::getFullDate($userInput_EndDateMonth);

  $holiday = new HolidayPlanner($validStart = "1.4.2020", $validEnd = "31.03.2021");
  $holidayCount = $holiday->getHolidaysCount($holidayStart, $holidayEnd);

  print("Total number of possible holidays in given period (" . $holidayStart->format('Y-m-d') . " - " . $holidayEnd->format('Y-m-d') . ") = " . $holidayCount . "\n");

}


main();