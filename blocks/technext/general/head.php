
  <!-- Basic Page Needs
  ================================================== -->
  <meta charset="utf-8">
  <title>
  <?=$this->get('settings')['siteName']??'' ?>
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
	echo ' - '.$dateFormatter->format($today);
	echo ' - Tempo '.$todayEve->getTimeDescription();
        echo ' '.$todayEve->getWeekTimeNumber() . ' settimana';
  ?>
  </title>

  <!-- Mobile Specific Metas
  ================================================== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?=$this->get('settings')['siteName']??''?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="author" content="Themefisher">
  <meta name="generator" content="Themefisher Small Apps Template v1.0">

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?=$this->get('settings')['baseUrl'] ?>/vendor/technext/small-apps/images/favicon.png" />
  
  <!-- PLUGINS CSS STYLE -->
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/themify-icons/css/themify-icons.css">
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/slick-carousel/slick/slick-theme.css">
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/fancybox/dist/css/jquery.fancybox.css">
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/aos/dist/aos.css">
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap-table/dist/bootstrap-table.min.css">
  <link rel="stylesheet" href="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap-table/dist/themes/bootstrap-table/bootstrap-table.min.css">

  <!-- CUSTOM CSS -->
  <link href="<?=$this->get('settings')['baseUrl'] ?>/vendor/technext/small-apps/css/style.css" rel="stylesheet">
  <link href="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/css/style.css" rel="stylesheet">