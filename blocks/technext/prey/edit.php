<?php
/** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendar */
/** @var \Caiofior\CatholicLiturgical\Week $todayEve */
?><div class="row">
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
                    <input type="hidden" name="id" value="<?=$prey->getData()['id']??''?>"/>
                    <input type="hidden" name="calendar_id" value="<?=$calendar->getData()['id']?>"/>
                    <input type="checkbox" name="use_today"/>
                    <a href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere/modifica/?calendario=<?=$calendar->getData()['id']?>&giorno=<?= $previousDay->format('Y-m-d');?>" titile="<?= $previousDay->format('d/m/Y'); ?>">
                    <span class="icon-container">
                        <span class="ti-angle-left"></span>
                    </span>
                    </a>
                    <span>       
                        <input name="today" value="<?= $today->format('Y-m-d'); ?>"/>
                        <?= $dateFormatter->format($today); ?>
                    </span>
                    <a href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere/modifica/?calendario=<?=$calendar->getData()['id']?>&giorno=<?= $nextDay->format('Y-m-d');?>" title="<?= $nextDay->format('d/m/Y'); ?>">
                    <span class="icon-container">
                        <span class="ti-angle-right"></span>
                    </span>
                    </a>
                </div>
                <?php if($catholicCalendar->getSpecialFest() !== false) : ?>
                <div class="col-md-12 mb-2">
                    <?php
                    $checked = ''; 
                    if(!empty($prey->getData()['special_fest'])) {
                        $checked = ' checked="checked" ';    
                    }
                    ?>
                    <input type="checkbox" name="use_special_fest"<?=$checked?>/>
                    Festa : <?= $catholicCalendar->getSpecialFest(); ?>
                    <input type="hidden" name="special_fest" value="<?= $catholicCalendar->getSpecialFest(); ?>"/>
                </div>
                <?php endif; ?>
                <div class="col-md-12 mb-2">
                    <?php
                    $checked = '';
                    if(
                            !empty($prey->getData()['lithurgicEve']) ||
                            !empty($calendar->getData()['lithurgicEve'])
                            ) {
                                $checked = ' checked="checked" ';
                    }
                    ?>
                    <input type="checkbox" name="use_lithurgic_eve" <?=$checked?>/>
                    Periodo liturgico : <?= $todayEve->getTimeDescription(); ?>
                    <input type="hidden" name="lithurgic_eve" value="<?=$todayEve->getTime()?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <?php
                    
                    $checked = '';
                    if(
                            !empty($prey->getData()['lithurgicYear']) ||
                            !empty($calendar->getData()['lithurgicYear'])
                            ) {
                        $checked = ' checked="checked" ';        
                        
                    }
                    ?>
                    <input type="checkbox" name="use_lithurgic_week" <?=$checked?>/>
                    Settimana periodo liturgico : <?= $todayEve->getWeekTimeNumber(); ?>
                    <input type="hidden" name="lithurgic_week" value="<?= $todayEve->getWeekTimeNumber(); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <?php
                    $checked = '';
                    if(
                            !empty($prey->getData()['salther']) ||
                            !empty($calendar->getData()['salther'])
                            ) {
                                $checked = ' checked="checked" ';
                    }
                    ?>
                    <input type="checkbox" name="use_salter_week" <?=$checked?>/>
                    Settimana del salterio : <?= $todayEve->getWeekPsalterNumber(); ?>
                    <input type="hidden" name="salter_week" value="<?= $todayEve->getWeekPsalterNumber(); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <?php
                    $checked = '';
                    if(
                            !empty($prey->getData()['lithurgicYear']) ||
                            !empty($calendar->getData()['lithurgicYear'])
                            ) {
                            $checked = ' checked="checked" ';
                    }
                    ?>
                    <input type="checkbox" name="use_lithurgic_year" <?=$checked?>/>
                    Anno liturgico : <?= $catholicCalendar->getLithurgicYear(); ?>
                    <input type="hidden" name="lithurgic_year" value="<?= $catholicCalendar->getLithurgicYear(); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <?php
                    $checked = '';
                    if(
                            !empty($prey->getData()['day_of_week'])
                            ) {
                            $checked = ' checked="checked" ';
                    }
                    ?>
                    <input type="checkbox" name="use_day_of_week" <?=$checked?>/>
                    Giorno della settimana : <?= $today->format('w'); ?>
                    <input type="hidden" name="day_of_week" value="<?= $today->format('w'); ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                     <?php
                    $checked = ''; 
                    if(
                            !empty($prey->getData()['day_of_year'])
                            ) {
                            $checked = ' checked="checked" ';
                    }
                    ?>
                    <input type="checkbox" name="use_day_of_year" <?=$checked?>/>
                    Giorno dell'anno : <?= $today->format('z'); ?>
                    <input type="hidden" name="day_of_year" value="<?= $today->format('z'); ?>"/>
                </div>
                <div class="col-md-6 mb-2">
                    <input name="hour" class="form-control main" type="text" placeholder="Ora" value="<?= $prey->getData()['hour'] ?? '' ?>"/>
                </div>
                <div class="col-md-6 mb-2">
                    <input name="sort" class="form-control main" type="text" placeholder="Ordine" value="<?= $prey->getData()['sort'] ?? '' ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <input name="title" class="form-control main" type="text" placeholder="Titolo" value="<?= $prey->getData()['title'] ?? '' ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <input name="reference" class="form-control main" type="text" placeholder="Riferimento" value="<?= $prey->getData()['reference'] ?? '' ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <textarea id="content" name="content" class="form-control main"><?= $prey->getData()['content'] ?? '' ?></textarea>
                </div>
                <div class="col-12 mb-4">
                    <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>