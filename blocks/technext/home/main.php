<div class="row">
    <div class="col-md-6 order-2 order-md-1 rounded shadow bg-white text-center text-md-left">
        <h1 class="font-weight-bold mb-4"><?= $this->get('settings')['siteName'] ?? '' ?></h1>
        <?php
        $today = new \DateTime();
        $catholicCalendar = new \Caiofior\CatholicLiturgical\Calendar($today->format('Y-m-d'));
        $todayEve = $catholicCalendar->getDateTime();
        $dateFormatter = $this->get('date_formatter');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->get('entity_manager');
        /** @var \Caiofior\Core\model\Option $option */
        $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
        if (!is_object($option)) {
            $option = new \Caiofior\Core\model\Option();
            $option->setOption('default_calendar');
        }
        $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', $option->getValue());
        $searchPrey = new \Caiofior\CatholicLiturgical\SearchPrey($entityManager, $calendar, $today);
        $preys = $searchPrey->getPrey();
        ?>
        <p class="mb-5">                
            <?= $dateFormatter->format($today); ?><br>
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
        <img class="img-fluid" src="<?= $this->get('settings')['baseUrl'] ?>//theme/technext/small-apps/images/spiritualita.png" alt="screenshot">
    </div>
</div>