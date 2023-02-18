                <div class="row align-items-center">
			<div class="col-md-6 order-2 order-md-1 text-center text-md-left">
				<h1 class="text-white font-weight-bold mb-4"><?=$this->get('settings')['siteName']??''?></h1>
				<?php 
                                $today = new \DateTime();
                                $catholicCalendar = new \Caiofior\CatholicLiturgical\Calendar($today->format('Y-m-d'));
                                $todayEve = $catholicCalendar->getDateTime();
                                $dateFormatter = $this->get('date_formatter');
                                ?>
                <p class="text-white mb-5">                
                    <?= $dateFormatter->format($today); ?>
                </p>    
                <p class="text-white mb-5">
                    <?= $todayEve->getTimeDescription(); ?>                    
                </p>                
                <p class="text-white mb-5">
                    Periodo liturgico : <?= $todayEve->getTimeDescription(); ?>                    
                </p>
                <p class="text-white mb-5">
                    Settimana periodo liturgico : <?= $todayEve->getWeekTimeNumber(); ?>                    
                </p>
                <p class="text-white mb-5">
                    Settimana del salterio : <?= $todayEve->getWeekPsalterNumber(); ?>                    
                </p>
                <p class="text-white mb-5">
                    Anno liturgico : <?= $catholicCalendar->getLithurgicYear(); ?>                    
                </p>
			</div>
			<div class="col-md-6 text-center order-1 order-md-2">
				<img class="img-fluid" src="<?=$this->get('settings')['baseUrl'] ?>/vendor/technext/small-apps/images/mobile.png" alt="screenshot">
			</div>
		</div>