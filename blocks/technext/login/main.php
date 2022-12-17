<div class="row align-items-center">
    <div class="col-md-6 order-2 order-md-1 text-center text-md-left">
        <h1 class="text-white font-weight-bold mb-4"><?= $this->get('settings')['siteName'] ?? '' ?></h1>
        <?= $message; ?>
        <form method="post">
            <div class="mb-5">
                <label class="text-white" for="username">Utente</label>
                <input name="username" value="<?=$request->getParsedBody()['username']??''?>"/><br>
                <label class="text-white" for="password">Password</label>
                <input type="password" name="password"/><br>
                <input type="submit" name="login" value="Accedi"/>
            </div>
        </form>
        
        <form method="post">
            <div class="mb-5">
                <label class="text-white" for="username">Utente</label>
                <input name="username" value="<?=$request->getParsedBody()['username']??''?>"/><br>
                <label class="text-white" for="password">Password</label>
                <input type="password" name="password"/><br>
                <label class="text-white" for="ripeti_password">Ripeti Password</label>
                <input type="password" name="ripeti_password"/><br>
                <input type="submit" name="register" value="Registrati"/>
            </div>
        </form>

    </div>
</div>