<div class="row">
    <div class="col-12">
        <h1 class="text-white font-weight-bold mb-4">Profilo</h1>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="profilo" method="post">
            <div class="row">
                <div class="col-md-6 mb-2">
                <input name="first_name" class="form-control main" type="text" placeholder="Nome" value="<?=$profile->getData()['first_name']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input name="last_name" class="form-control main" type="text" placeholder="Cognome" value="<?=$profile->getData()['last_name']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input name="address" class="form-control main" type="text" placeholder="Indirizzo" value="<?=$profile->getData()['address']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input name="city" class="form-control main" type="text" placeholder="Città" value="<?=$profile->getData()['city']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input name="province" class="form-control main" type="text" placeholder="Provincia" value="<?=$profile->getData()['province']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input name="state" class="form-control main" type="text" placeholder="Nazione" value="<?=$profile->getData()['state']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input name="phone" class="form-control main" type="text" placeholder="Recapito telefonico" value="<?=$profile->getData()['phone']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input name="email" class="form-control main" type="text" placeholder="Mail" value="<?=$profile->getData()['email']??''?>"/>
                </div>
                <div class="col-12 mb-4">
                <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>