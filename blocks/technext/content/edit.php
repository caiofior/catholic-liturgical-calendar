<div class="row">
    <div class="col-12">
            <h1 class="text-white font-weight-bold mb-4">Contenuto</h1>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="content" method="post">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <input name="id" type="hidden" value="<?= $content->getData()['id'] ?? '' ?>"/>
                    <input name="title" class="form-control main" type="text" placeholder="Titolo" value="<?= $content->getData()['name'] ?? '' ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <input name="code" class="form-control main" type="text" placeholder="Codice" value="<?= $content->getData()['code'] ?? '' ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <textarea id="content" name="content" class="form-control main"><?= $content->getData()['content'] ?? '' ?></textarea>
                </div>
                <div class="col-12 mb-4">
                    <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>