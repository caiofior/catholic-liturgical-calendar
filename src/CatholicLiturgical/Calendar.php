<?php
declare(strict_types=1);
namespace Caiofior\CatholicLiturgical;
/**
 * Creates and rertives data of a chatolic lithurgic calendar
 * @author Claudio Fior caiofior@gmail.com
 */
class Calendar {
   /**
    * Weeks in year
    */
   const weeksInYear = 53;
   private $calendar = [];
   private $inputDate = null ;
   private $lithurgicYear = null;
   private $specialFest = [
       '12-08'=>'Immaculate Conception',
       '12-25'=>'Christmas',
       '12-30'=>'Holy Family',
       '01-01'=>'Holy Mother of God',
       '01-06'=>'Epiphany',
       '01-08'=>'Jesus baptism',
       '02-02'=>'Presentation in the Temple',
       '03-19'=>'Saint Joseph',
       '03-25'=>'Saint Annunciation',
       '06-24'=>'Saint John',
       '06-29'=>'Saint Peter',
       '08-15'=>'Assumption of Mary',
       '09-14'=>'Exaltation of the cross',
       '11-01'=>'All Saints',
       '11-02'=>'Day of the Dead',
       '11-09'=>'Dedication of the Lateran Basilica',
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
      $saints = array_flip($this->specialFest);
      $saintJosep = \DateTime::createFromFormat('Y-m-d',$year.'-'.$saints['Saint Joseph']);
      if($saintJosep->format('w') == 0) {
         unset($this->specialFest['03-19']);
         $this->specialFest['03-20']='Saint Joseph';
      }
      
      $weekTime = array_fill(1, self::weeksInYear, 'O');
      $date = new \DateTime('+8 days 1 January '.$year);
      $epiphanyWeek = $date->format('W');
      for($c = 1; $c < $epiphanyWeek; $c++) {
         $weekTime[$c]='C';
      }
      $easterDay = date('z',easter_date((int)$year));
      $date = new \DateTime('+'.$easterDay.' days 1 January '.$year);
      $easterWeek=(int)$date->format('W');
      $lentDay=$easterDay-35;
      $date = new \DateTime('+'.$lentDay.' days 1 January '.$year);
      $lentWeek=(int)$date->format('W');
      for($c = $lentWeek; $c <$easterWeek; $c++) {
         $weekTime[$c]='L';
      }
      $weekTime[$easterWeek]='T';
      
      $pentecostWeek = $easterWeek+9;
      
      for($c = $easterWeek+1; $c <$pentecostWeek; $c++) {
         $weekTime[$c]='E';
      }
      $date = new \DateTime('27 November '.$year);
      $adventWeek = $date->format('W')+1;
      
      $moduleYear = $year % 3;
      if (
              (int)$this->inputDate->format('W') >= $adventWeek && 
              (int)$this->inputDate->format('z') > 5
              ) {
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
      $christmasWeek = $date->format('W')+1;
      
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
         if (
                 $currentWeekTime == 'T' &&
                 $times[$currentWeekTime] == 1
                 ) {
             
                $palmSunday = new \DateTime();
                $palmSunday->setTime(0, 0, 0);
                $palmSunday->setISODate($year, $weekNumber, 0);
                $this->specialFest[$palmSunday->format('m-d')]='Palm Sunday';
                
                $holyThurday = new \DateTime();
                $holyThurday->setTime(0, 0, 0);
                $holyThurday->setISODate($year, $weekNumber, 4);
                $this->specialFest[$holyThurday->format('m-d')]='Holy Thursday';
                
                $goodFriday = new \DateTime();
                $goodFriday->setTime(0, 0, 0);
                $goodFriday->setISODate($year, $weekNumber, 5);
                $this->specialFest[$goodFriday->format('m-d')]='Good Friday';
                
                $holySatuday = new \DateTime();
                $holySatuday->setTime(0, 0, 0);
                $holySatuday->setISODate($year, $weekNumber, 6);
                $this->specialFest[$holySatuday->format('m-d')]='Holy Saturday';
                
         }
         if (
                 $currentWeekTime == 'E' &&
                 $times[$currentWeekTime] == 1
                 ) {
                
                $easterMonday = new \DateTime();
                $easterMonday->setTime(0, 0, 0);
                $easterMonday->setISODate($year, $weekNumber, 1);
                $this->specialFest[$easterMonday->format('m-d')]='Easter Monday';
                                
         }
         if (
                 $currentWeekTime == 'E' &&
                 $times[$currentWeekTime] == 7
                 ) {
                
                   $ascensionDay = new \DateTime();
                   $ascensionDay->setTime(0, 0, 0);
                   $ascensionDay->setISODate($year, $weekNumber, 0);
                   $this->specialFest[$ascensionDay->format('m-d')]='Ascension Day';
                                
         }
         if (
                 $currentWeekTime == 'E' &&
                 $times[$currentWeekTime] == 8
                 ) {
                
                   $pentecost = new \DateTime();
                   $pentecost->setTime(0, 0, 0);
                   $pentecost->setISODate($year, $weekNumber, 0);
                   $this->specialFest[$pentecost->format('m-d')]='Pentecost';
                   
                   $times['O']++;
                   
                   $holyTrinity = new \DateTime();
                   $holyTrinity->setTime(0, 0, 0);
                   $holyTrinity->setISODate($year, $weekNumber+1, 0);
                   $this->specialFest[$holyTrinity->format('m-d')]='Holy Trinity';
                   
                   $corpusChristi = new \DateTime();
                   $corpusChristi->setTime(0, 0, 0);
                   $corpusChristi->setISODate($year, $weekNumber+2, 0);
                   $this->specialFest[$corpusChristi->format('m-d')]='Corpus Christi';
                   
                   $sacredHeart = new \DateTime();
                   $sacredHeart->setTime(0, 0, 0);
                   $sacredHeart->setISODate($year, $weekNumber+2, 5);
                   $this->specialFest[$sacredHeart->format('m-d')]='Sacred Heart';
                                
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

