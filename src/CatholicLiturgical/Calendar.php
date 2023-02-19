<?php
declare(strict_types=1);
namespace Caiofior\CatholicLiturgical;
/**
 * Creates and rertives data of a chatolic lithurgic calendar
 * @author Claufio Fior caiofior@gmail.com
 */
class Calendar {
   /**
    * Weeks in year
    */
   const weeksInYear = 52;
   private $calendar = [];
   private $inputDate = null ;
   private $lithurgicYear = null;
   private $specialFest = [
       '01-06'=>'Epiphany',
       '01-08'=>'Jesus baptism',
       '02-02'=>'Presentation in the Temple'
   ];
   /**
    * Gets the liturgic calendar data of the required date
    * @param $dateTimeString
    * @return \stdClass
    */
   public function __construct(string $dateTimeString) {
      $this->inputDate = new \DateTime($dateTimeString);
      $year = (int)$this->inputDate->format('Y');
      $this->initCalendar($year);
   }
   /**
    * Returns the day time
    * @return Week
    */
   public function getDateTime() : object {
        $weekOfYear = (int)$this->inputDate->format('W');
        if((int)$this->inputDate->format('N')==7) {
            $weekOfYear++;
        }
        return $this->calendar[$weekOfYear];
   }
   /**
    * Returns lithurgic year
    * @return string
    */
   public function getLithurgicYear() {
       return $this->lithurgicYear;
   }
   /**
    * Generates the calendare of a certain year
    * @param int $year
    * @return array
    */
   private function initCalendar(int $year) {
      $weekTime = array_fill(1, self::weeksInYear, 'O');
      $date = new \DateTime('+8 days 1 January '.$year);
      $epiphanyWeek = $date->format('W');
      for($c = 1; $c < $epiphanyWeek; $c++) {
         $weekTime[$c]='C';
      }
      $easterDay = date('z',easter_date((int)$year));
      $date = new \DateTime('+'.$easterDay.' days 1 January '.$year);
      $easterWeek=(int)$date->format('W');
      $lentDay=$easterDay-42;
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
      
      $moduleYear = $year % 3;
      if ($this->inputDate->format('W') > $adventWeek) {
          $moduleYear +=1;
      }
      if ($moduleYear>2) {
          $moduleYear=0;
      }
      switch($moduleYear) {
          case 0 :
              $this->lithurgicYear = 'C';
          break;
          case 1 :
              $this->lithurgicYear = 'A';
          break;
          case 2 :
              $this->lithurgicYear = 'B';
          break;
      }
      
      $date = new \DateTime('25 December '.$year);
      $christmasWeek = $date->format('W');
      
      for($c = $adventWeek; $c <$christmasWeek; $c++) {
         $weekTime[$c]='A';
      }
      for($c = $christmasWeek; $c <= self::weeksInYear; $c++) {
         $weekTime[$c]='C';
      }
      
      $date = new \DateTime('26 November '.($year-1));
      $previousAdventWeek = $date->format('W');   
      
      $weekStarts  = (self::weeksInYear-$previousAdventWeek)%4;
      $weekPsalterNumber = [];
      for ($c =0; $c < $adventWeek; $c++) {
         $weekPsalterNumber[$c]=($c+$weekStarts)%3+1;
      }
      for ($c = $adventWeek; $c <= self::weeksInYear; $c++) {
         $weekPsalterNumber[$c]=$c%3+1;
      }
      $times = array(
          'C'=>(self::weeksInYear-$previousAdventWeek),
          'O'=>1,
          'L'=>1,
          'T'=>1,
          'E'=>1,
          'A'=>1
      );
      $this->calendar = [];
      foreach ($weekTime as $weekNumber=>$currentWeekTime) {
         if ( $currentWeekTime == 'C' &&
              $weekNumber > 3 &&
              $times[$currentWeekTime] > (self::weeksInYear-$previousAdventWeek)
                 ) {
            $times[$currentWeekTime]=1;
         }
         if (
                 $currentWeekTime == 'L' &&
                 $times[$currentWeekTime] == 1
                 ) {
             
                $ashWednesday = new \DateTime();
                $ashWednesday->setTime(0, 0, 0);
                $ashWednesday->setISODate($year, $weekNumber-1, 3);
                $this->specialFest[$ashWednesday->format('m-d')]='Ash Wednesday';
         }
         $week =new Week();
         $week->setTime($currentWeekTime);
         $week->setWeekTimeNumber($times[$currentWeekTime]++);
         $week->setWeekPsalterNumber($weekPsalterNumber[(int)$weekNumber]);
         $this->calendar[$weekNumber]=$week;

      }
   }
   
   public function getSpecialFest() {
       return $this->specialFest[$this->inputDate->format('m-d')]??false;
   }
}

