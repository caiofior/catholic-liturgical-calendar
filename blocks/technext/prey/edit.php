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
                <?php 
                                $calendar = new \Caiofior\CatholicLiturgical\Calendar('today');
                                $today = $calendar->getDateTime();
                                echo $today->getTime();
                                echo $today->getWeekTimeNumber();
                                echo $today->getWeekPsalterNumber();
                                echo $calendar->getLithurgicYear();
                                ?></div>
                <div class="col-12 mb-4">
                    <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>