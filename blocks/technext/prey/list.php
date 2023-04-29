<div class="row align-items-center">
    <div class="col-md-12 order-2 order-md-1 text-center text-md-left">
        <h1 class="text-white font-weight-bold mb-4">Preghiere</h1>
        <div class="card mt-5">
            <div class="card-body ">
                <p class="text-white mb-5">
                <div class="icon-container">
                    <a class="add" title="Aggiungi" href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere/modifica/">
                        <span class="ti-plus"></span>
                    </a>
                </div> 
                </p>
                <p>
                    <a href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere?calendario=<?=$calendar->getData()['id']??null?>&giorno=<?= $previousDay->format('Y-m-d');?>" titile="<?= $previousDay->format('d/m/Y'); ?>">
                    <span class="icon-container">
                        <span class="ti-angle-left"></span>
                    </span>
                    </a>
                    <span>
                        <input name="today" value="<?= $today->format('Y-m-d'); ?>"/>                        
                        <?= $dateFormatter->format($today); ?>
                    </span>
                    <a href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere?calendario=<?=$calendar->getData()['id']??null?>&giorno=<?= $nextDay->format('Y-m-d');?>" title="<?= $nextDay->format('d/m/Y'); ?>">
                    <span class="icon-container">
                        <span class="ti-angle-right"></span>
                    </span>
                    </a>
                    <select name="calendario">
                    <?php
                    $calendarId = (int)($request->getQueryParams()['calendario'] ?? 0);
                    if ($calendarId == 0) {
                        /** @var \Caiofior\Core\model\Option $option */
                        $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
                        if (!is_object($option)) {
                            $option = new \Caiofior\Core\model\Option();
                            $option->setOption('default_calendar');
                        }
                        $calendarId = $option->getValue();
                    }
                    $calendarColl = $entityManager->getRepository('\Caiofior\CatholicLiturgical\model\CalendarProperties')->findAll();
                    /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendarItem */
                    foreach ($calendarColl as $calendarItem) :
                        $selected = '';
                        if($calendarItem->getData()['id'] == $calendarId) {
                            $selected = ' selected ';
                        }
                        ?>
                        <option <?=$selected?> value="<?=$calendarItem->getData()['id'];?>"><?=$calendarItem->getData()['name'];?></option>
                    <?php endforeach; ?>
                    </select>
                </p>
                <table
                    data-toggle="table"
                    data-locale="it"
                    data-url="preghiere/list?calendario=<?=$calendar->getData()['id']?>&giorno=<?=$today->format('Y-m-d');?>"
                    data-side-pagination="server"
                    data-pagination="true"
                    data-search="true">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="title">Titolo</th>
                            <th data-sortable="true" data-field="reference">Riferimento</th>
                            <th data-sortable="true" data-field="sort">Ordine</th>
                            <th data-sortable="true" data-field="hour">Ora</th>
                            <th data-sortable="true" data-field="special_fest">Festività</th>
                            <th data-events="operateEvents" data-field="actions"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div
</div>