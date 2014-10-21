<?php
namespace liturgical\calendar;
class Calendar {
   const weeksInYear = 52;
   private $year;
   private $weekTime=array();
   private $weekNumber=array();
   
   public function __construct($year) {
      $this->year = $year;
      $this->weekTime = array_fill(1, self::weeksInYear, 'O');
      $date = new \DateTime('+8 days 1 January '.$this->year);
      $epiphanyWeek = $date->format('W');
      
      for($c = 1; $c <$epiphanyWeek; $c++) {
         $this->weekTime[$c]='C';
      }
      
      $easterDay = date('z',easter_date($this->year));
      $date = new \DateTime('+'.$easterDay.' days 1 January '.$this->year);
      $easterWeek=$date->format('W');
      $lentDay=$easterDay-40;
      $date = new \DateTime('+'.$lentDay.' days 1 January '.$this->year);
      $lentWeek=$date->format('W');
      
      for($c = $lentWeek; $c <$easterWeek; $c++) {
         $this->weekTime[$c]='L';
      }
      $this->weekTime[$easterWeek]='T';
      
      $pentecostWeek = $easterWeek+8;
      
      for($c = $easterWeek+1; $c <$pentecostWeek; $c++) {
         $this->weekTime[$c]='E';
      }
      
      $date = new \DateTime('26 November '.$this->year);
      $adventWeek = $date->format('W');
      
      $date = new \DateTime('25 December '.$this->year);
      $christmasWeek = $date->format('W');
      
      for($c = $adventWeek+1; $c <$christmasWeek; $c++) {
         $this->weekTime[$c]='A';
      }
      for($c = $christmasWeek; $c <= sizeof($this->weekTime); $c++) {
         $this->weekTime[$c]='C';
      }
      
      $date = new \DateTime('26 November '.($this->year-1));
      $previousAdventWeek = $date->format('W');
      $weekStarts  = (self::weeksInYear-$previousAdventWeek)%4;
      
      for ($c =0; $c < $adventWeek; $c++) {
         $this->weekNumber[$c]=($c+$weekStarts)%4+1;
      }
      for ($c = $adventWeek; $c <= self::weeksInYear; $c++) {
         $this->weekNumber[$c]=$c%4+1;
      }
      var_dump($this->weekNumber);
   }
}

