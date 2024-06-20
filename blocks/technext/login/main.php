<div class="row">
    <div class="col-12">
        <h1 class="text-white font-weight-bold mb-4">Accesso</h1>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="login" method="post">
            <div class="row">
                <div class="col-md-6 mb-2">
                <input name="username" class="form-control main" type="text" placeholder="Nome utente" required value="<?=$request->getParsedBody()['username']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input type="password" class="form-control main" name="password" placeholder="Password" required/>
                </div>
                <div class="col-12 mb-4">
                <input type="submit" class="btn btn-main-md" name="login" value="Accedi"/>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12">
        <a id="show_register" class="btn btn-rounded-icon" href="#">Vuoi registrarti?</a>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="recover" style="display: none;" method="post">
            <div class="row">
                <div class="col-md-6 mb-2">
                <input name="username" class="form-control main" type="text" placeholder="Nome utente" required value="<?=$request->getParsedBody()['username']??''?>"/>
                </div>
                <div class="col-12 mb-4">
                <input type="submit" class="btn btn-main-md" name="recover" value="Recupera password"/>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12">
        <a id="show_recover" class="btn btn-rounded-icon" href="#">Recupera password</a>
    </div>
    <div class="col-12">
        <form id="register" style="display: none;" method="post">
            <div class="row">
                <div class="col-12">
                <input name="username" class="form-control main" type="text" placeholder="Nome utente" required value="<?=$request->getParsedBody()['username']??''?>"/>
                </div>
                <div class="col-md-6 mb-2">
                <input type="password" class="form-control main" placeholder="Password" required name="password"/><br>
                </div>
                <div class="col-md-6 mb-2">
                <input type="password" class="form-control main" placeholder="Ripeti password" required name="ripeti_password"/><br>
                </div>
                <div class="col-12 mb-4">
                <input type="submit" class="btn btn-main-md" name="register" value="Registrati"/>
                </div>
            </div>
        </form>
        </div>
    <div class="col-12">
        <a id="show_login" style="display: none;" class="btn btn-rounded-icon" href="#">Accedi</a>
    </div>    
    </div>
</div>