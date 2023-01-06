<div class="row align-items-center">
    <div class="col-md-12 order-2 order-md-1 text-center text-md-left">
        <h1 class="text-white font-weight-bold mb-4">Calendari</h1>
        <div class="card mt-5">
            <div class="card-body ">
                <p class="text-white mb-5">
                <div class="icon-container">
                    <a title="Aggiungi" href="<?= $this->get('settings')['baseUrl'] ?>/index.php/calendari/modifica">
                        <span class="ti-plus"></span>
                    </a>
                </div> 
                </p>
                <table
                    data-toggle="table"
                    data-locale="it"
                    data-url="calendari/list"
                    data-side-pagination="server"
                    data-pagination="true"
                    data-search="true">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="username">Profilo</th>
                            <th data-sortable="true" data-field="approved">Attivo</th>
                            <th data-sortable="true" data-field="name">Nome</th>
                            <th data-events="operateEvents" data-field="actions"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div
</div>