<div class="row">
    <div class="col-12">
        <div class="icon-container">
            <h1 class="text-white font-weight-bold mb-4">Preghiera
        </div>
        </h1>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="profilo" method="post">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <input type="hidden" name="calendario" value="<?=$calendar->getData()['id']?>"/>
                    <input type="hidden" name="giorno" value="<?= $today->format('Y-m-d'); ?>"/>
                    <a href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere/modifica/?calendario=<?=$calendar->getData()['id']?>&giorno=<?= $previousDay->format('Y-m-d');?>" titile="<?= $previousDay->format('d/m/Y'); ?>">
                    <span class="icon-container">
                        <span class="ti-angle-left"></span>
                    </span>
                    </a>
                    <span>
                        <?= $dateFormatter->format($today); ?>
                    </span>
                    <a href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere/modifica/?calendario=<?=$calendar->getData()['id']?>&giorno=<?= $nextDay->format('Y-m-d');?>" title="<?= $nextDay->format('d/m/Y'); ?>">
                        <span class="icon-container">
                        <span class="ti-angle-right"></span>
                    </span>
                    </a>
                </div>
                <div class="col-md-12 mb-2">
                    Periodo liturgico : <?= $todayEve->getTimeDescritpion(); ?>
                    <input type="hidden" name="lithurgic_eve" value="<?=$todayEve->getTime()?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    Settimana periodo liturgico : <?= $todayEve->getWeekTimeNumber(); ?>
                    <input type="hidden" name="lithurgic_week" value="<?= $todayEve->getWeekTimeNumber(); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    Settimana del salterio : <?= $todayEve->getWeekPsalterNumber(); ?>
                    <input type="hidden" name="salter_week" value="<?= $todayEve->getWeekPsalterNumber(); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    Anno liturgico : <?= $catholicCalendar->getLithurgicYear(); ?>
                    <input type="hidden" name="lithurgic_year" value="<?= $catholicCalendar->getLithurgicYear(); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    Giorno della settimana : <?= $today->format('w'); ?>
                    <input type="hidden" name="day_of_week" value="<?= $today->format('w'); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    Giorno dell'anno : <?= $today->format('z'); ?>
                    <input type="hidden" name="day_of_year" value="<?= $today->format('z'); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <input name="hour" class="form-control main" type="text" placeholder="Ora" value=""/>
                </div>
                <div class="col-md-12 mb-2">
                    <input name="title" class="form-control main" type="text" placeholder="Titolo" value=""/>
                </div>
                <div class="col-md-12 mb-2">
                    <input name="referrer" class="form-control main" type="text" placeholder="Riferimento" value=""/>
                </div>
                <div class="col-md-12 mb-2">
                    <textarea id="content" name="content" class="form-control main"></textarea>
                </div>
                <div class="col-12 mb-4">
                    <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>