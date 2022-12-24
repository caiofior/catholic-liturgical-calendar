<div class="row">
    <div class="col-12">
        <h1 class="text-white font-weight-bold mb-4">Calendario</h1>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="profilo" method="post">
            <div class="row">
                <div class="col-md-12 mb-2">
                <input name="name" class="form-control main" type="text" placeholder="Nome" value="<?=$calendar->getData()['name']??''?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <textarea id="description" name="description" class="form-control main"><?=$calendar->getData()['description']??''?></textarea>
                </div>
                <div class="col-12 mb-4">
                <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>