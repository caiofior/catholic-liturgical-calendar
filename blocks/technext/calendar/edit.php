<div class="row">
    <div class="col-12">
        <div class="icon-container">
            <h1 class="text-white font-weight-bold mb-4">Calendario
                <?php if ($calendar->getData()['id'] > 0) : ?>
                    <a title="Aggiungi preghiera" href="<?= $this->get('settings')['baseUrl'] ?>/index.php/preghiere/modifica/?calendario=<?=$calendar->getData()['id']?>">
                        <span class="ti-plus"></span>
                    </a>
                <?php endif; ?>
        </div>
        </h1>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="profilo" method="post">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <input name="id" type="hidden" value="<?= $calendar->getData()['id'] ?? '' ?>"/>
                    <input name="name" class="form-control main" type="text" placeholder="Nome" value="<?= $calendar->getData()['name'] ?? '' ?>"/>
                </div>
                <div class="col-md-12 mb-2">
                    <textarea id="description" name="description" class="form-control main"><?= $calendar->getData()['description'] ?? '' ?></textarea>
                </div>
                <?php if ($role->getDescription() == 'administrator') : ?>
                    <div class="col-md-12 mb-2" >
                        <p class="text-white">
                            <span class="absolute">
                                Predefinito
                            </span>
                            <input style="" name="default" class="form-control modal-content" type="checkbox" <?= ((is_object ($option) && $option->getValue() == $calendar->getData()['id']) ? 'checked' : '') ?> value="1"/>
                        </p>
                    </div>
                    <div class="col-md-12 mb-2" >
                        <p class="text-white">
                            <span class="absolute">
                                Approvato
                            </span>
                            <input style="" name="approved" class="form-control modal-content" type="checkbox" <?= (($calendar->getData()['approved'] ?? false) ? 'checked' : '') ?> value="1"/>
                        </p>
                    </div>
                    <div class="col-md-12 mb-2" >
                        <p class="text-white">
                            <span class="absolute">
                                Pubblico
                            </span>
                            <input style="" name="public" class="form-control modal-content" type="checkbox" <?= (($calendar->getData()['public'] ?? false) ? 'checked' : '') ?> value="1"/>
                        </p>
                    </div>
                <?php endif; ?>
                <div class="col-md-12 mb-2" >
                    <p class="text-white">
                        <span class="absolute">
                            Anno liturgico
                        </span>
                        <input style="" name="lithurgicYear" class="form-control modal-content" type="checkbox" <?= (($calendar->getData()['lithurgicYear'] ?? false) ? 'checked' : '') ?> value="1"/>
                    </p>
                </div>
                <div class="col-md-12 mb-2">
                    <p class="text-white">
                        <span class="absolute">
                            Calendario liturgico
                        </span>
                    </p>
                    <input name="lithurgicEve" class="form-control modal-content" type="checkbox" <?= (($calendar->getData()['lithurgicEve'] ?? false) ? 'checked' : '') ?> value="1"/>
                </div>
                <div class="col-md-12 mb-2">
                    <p for="lithurgicYear" class="text-white">
                        <span class="absolute">
                            Salterio
                        </span>
                    </p>
                    <input name="salther" class="form-control modal-content" type="checkbox" <?= (($calendar->getData()['salther'] ?? false) ? 'checked' : '') ?> value="1"/>
                </div>
                <div class="col-12 mb-4">
                    <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>