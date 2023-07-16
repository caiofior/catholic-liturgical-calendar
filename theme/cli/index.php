<?php
echo "\033[31m". ($this->get('settings')['siteName'] ?? '')."\033[0m".PHP_EOL.PHP_EOL;
$today = \DateTime::createFromFormat('Y-m-d', ($request->getQueryParams()['giorno']??''));
if(!is_object($today)) {
    $today = new \DateTime();
}
$previousDay = clone $today;
$previousDay = $previousDay->sub(new \DateInterval('P1D'));
$nextDay = clone $today;
$nextDay = $nextDay->add(new \DateInterval('P1D'));
$catholicCalendar = new \Caiofior\CatholicLiturgical\Calendar($today->format('Y-m-d'));
$todayEve = $catholicCalendar->getDateTime();
$dateFormatter = $this->get('date_formatter');

/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = $this->get('entity_manager');
$calendarId = (int)($request->getQueryParams()['calendario'] ?? 0);
/** @var \Caiofior\Core\model\Option $option */
    $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
    if (!is_object($option)) {
        $option = new \Caiofior\Core\model\Option();
        $option->setOption('default_calendar');
    }
$defaultCalendarId = $option->getValue();
if ($calendarId == 0) {
    $calendarId=$defaultCalendarId;
}
$calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', $calendarId);
$searchPrey = new \Caiofior\CatholicLiturgical\SearchPrey($entityManager, $calendar, $today);
$preys = $searchPrey->getPrey();
    
echo "\033[32m".'Tempo '.$todayEve->getTimeDescription()."\033[0m".PHP_EOL;
echo $todayEve->getWeekTimeNumber().' settimana'.PHP_EOL;
echo $todayEve->getWeekPsalterNumber().' settimana del salterio '.PHP_EOL;
foreach ($preys as $prey) : 
echo PHP_EOL."\033[1m".html_entity_decode(strip_tags($prey['title']))."\033[0m".PHP_EOL; 
echo html_entity_decode(strip_tags($prey['reference'])).PHP_EOL;
echo html_entity_decode(strip_tags($prey['content'])).PHP_EOL;
endforeach;