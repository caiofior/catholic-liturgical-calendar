                <div class="row align-items-center">
			<div class="col-md-6 order-2 order-md-1 text-center text-md-left">
				<h1 class="text-white font-weight-bold mb-4"><?=$this->get('settings')['siteName']??''?></h1>
				<p class="text-white mb-5"><?php 
                                $calendar = new \Caiofior\CatholicLiturgical\Calendar('today');
                                $today = $calendar->getDateTime();
                                echo $_SESSION['username']??'';
                                echo $today->getTime();
                                echo $today->getWeekTimeNumber();
                                echo $today->getWeekPsalterNumber();
                                echo $calendar->getLithurgicYear();
                                ?></p>
			</div>
			<div class="col-md-6 text-center order-1 order-md-2">
				<img class="img-fluid" src="<?=$this->get('settings')['baseUrl'] ?>/vendor/technext/small-apps/images/mobile.png" alt="screenshot">
			</div>
		</div>