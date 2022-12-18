<div class="row">
    <div class="col-12">
        <h1 class="text-white font-weight-bold mb-4">Password</h1>
    </div>
    <div class="col-12">
        <?= $message; ?>
        <form id="profilo" method="post">
            <div class="row">
                <div class="col-md-6 mb-2">
                <input type="password" name="password" class="form-control main" type="text" placeholder="Password"/>
                </div>
                <div class="col-md-6 mb-2">
                <input type="password" name="ripeti_password" class="form-control main" type="text" placeholder="Ripeti password"/>
                </div>
                <div class="col-12 mb-4">
                <input type="submit" class="btn btn-main-md" name="salva" value="Salva"/>
                </div>
            </div>
        </form>
    </div>
</div>