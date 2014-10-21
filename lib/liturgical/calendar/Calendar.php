<?php
namespace liturgical\calendar;
/**
 * Creates and rertives data of a chatolic lithurgic calendar
 * @author Claufio Fior caiofior@gmail.com
 */
class Calendar {
   /**
    * Weeks in year
    */
   const weeksInYear = 52;
   /**
    * Gets the liturgic calendar data of the required date
    * @param string $dateTimeString
    * @return \stdClass
    */
   public function getCalendar($dateTimeString) {
      $inputDate = new \DateTime($dateTimeString);
      $year = $inputDate->format('Y');
      $calendar = $this->initCalendar($year);
      return $calendar[$inputDate->format('W')];
   }
   /**
    * Generates the calendare of a certain year
    * @param int $year
    * @return array
    */
   private function initCalendar($year) {
      $weekTime = array_fill(1, self::weeksInYear, 'O');
      $date = new \DateTime('+8 days 1 January '.$year);
      $epiphanyWeek = $date->format('W');
      
      for($c = 1; $c <$epiphanyWeek; $c++) {
         $weekTime[$c]='C';
      }
      
      $easterDay = date('z',easter_date($year));
      $date = new \DateTime('+'.$easterDay.' days 1 January '.$year);
      $easterWeek=$date->format('W');
      $lentDay=$easterDay-40;
      $date = new \DateTime('+'.$lentDay.' days 1 January '.$year);
      $lentWeek=$date->format('W');
      
      for($c = $lentWeek; $c <$easterWeek; $c++) {
         $weekTime[$c]='L';
      }
      $weekTime[$easterWeek]='T';
      
      $pentecostWeek = $easterWeek+8;
      
      for($c = $easterWeek+1; $c <$pentecostWeek; $c++) {
         $weekTime[$c]='E';
      }
      
      $date = new \DateTime('26 November '.$year);
      $adventWeek = $date->format('W');
      
      $date = new \DateTime('25 December '.$year);
      $christmasWeek = $date->format('W');
      
      for($c = $adventWeek+1; $c <$christmasWeek; $c++) {
         $weekTime[$c]='A';
      }
      for($c = $christmasWeek; $c <= self::weeksInYear; $c++) {
         $weekTime[$c]='C';
      }
      
      $date = new \DateTime('26 November '.($year-1));
      $previousAdventWeek = $date->format('W');
      $weekStarts  = (self::weeksInYear-$previousAdventWeek)%4;
      $weekPsalterNumber = array();
      for ($c =0; $c < $adventWeek; $c++) {
         $weekPsalterNumber[$c]=($c+$weekStarts)%4+1;
      }
      for ($c = $adventWeek; $c <= self::weeksInYear; $c++) {
         $weekPsalterNumber[$c]=$c%4+1;
      }
      $times = array(
          'C'=>(self::weeksInYear-$previousAdventWeek),
          'O'=>1,
          'L'=>1,
          'T'=>1,
          'E'=>1,
          'A'=>1
      );
      $calendar = array();
      foreach ($weekTime as $weekNumber=>$currentWeekTime) {
         if ( $currentWeekTime =='C' &&
              $weekNumber >3 &&
              $times[$currentWeekTime] > (self::weeksInYear-$previousAdventWeek)
                 ) {
            $times[$currentWeekTime]=1;
         }
         $week =new \stdClass();
         $week->time = $currentWeekTime;
         $week->weekTimeNumber = $times[$currentWeekTime]++;
         $week->weekPsalterNumber = $weekPsalterNumber[$weekNumber];
         $calendar[$weekNumber]=$week;
      }
      return $calendar;
   }
}

