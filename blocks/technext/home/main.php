<div class="row">
    <div class="col-md-6 order-2 order-md-1 rounded shadow bg-white text-center text-md-left">
        <h1 class="font-weight-bold mb-4"><?= $this->get('settings')['siteName'] ?? '' ?></h1>
        <?php
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
        ?>
        <p class="mb-5">
            <?php
            $calendarColl = $entityManager->getRepository('\Caiofior\CatholicLiturgical\model\CalendarProperties')->findAll();
            /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendarItem */
            $calendarArray = [];
            foreach ($calendarColl as $calendarItem) {
                if($calendarItem->getData()['id']==$defaultCalendarId) {
                    array_unshift($calendarArray,$calendarItem);
                } else {
                    array_push($calendarArray,$calendarItem);
                }
            }
            foreach ($calendarArray as $calendarItem): ?>
                <a href="<?= $this->get('settings')['baseUrl'].'/?giorno='.$today->format('Y-m-d').'&calendario='.$calendarItem->getData()['id'];?>"><?=$calendarItem->getData()['name'];?></a>
            <?php endforeach; ?><br>
            <a href="<?= $this->get('settings')['baseUrl'] ?>/?calendario=<?=$calendar->getData()['id']??null?>&giorno=<?= $previousDay->format('Y-m-d');?>" title="<?= $previousDay->format('d/m/Y'); ?>">
            <span class="icon-container">
                <span class="ti-angle-left"></span>
            </span>
            </a>
            <?= $dateFormatter->format($today); ?>
            <a href="<?= $this->get('settings')['baseUrl'] ?>/?calendario=<?=$calendar->getData()['id']??null?>&giorno=<?= $nextDay->format('Y-m-d');?>" title="<?= $nextDay->format('d/m/Y'); ?>">
            <span class="icon-container">
                <span class="ti-angle-right"></span>
            </span>
            </a>
            <br>
            <?= $todayEve->getTimeDescription(); ?>                    
            , <?= $todayEve->getWeekTimeNumber(); ?> settimana<br>
            Settimana del salterio : <?= $todayEve->getWeekPsalterNumber(); ?><br>
            <?php foreach ($preys as $prey) : ?>
                <strong><?= $prey['title']; ?></strong><br>
                <em><?= $prey['reference']; ?></em>
                <?= $prey['content']; ?>

            <?php endforeach; ?>
        </p>
    </div>
    <div class="col-md-6 text-center order-1 order-md-2">
        <picture>
	    <source media="(min-width: 650px)" srcset="<?= $this->get('settings')['baseUrl'] ?>//theme/technext/small-apps/images/spiritualita.png">
            <img class="img-fluid" src="" alt="screenshot">
        <picture>
    </div>
</div>