window.operateEvents = {
    "click .trash": function (e) {
        e.preventDefault();
        bootbox.confirm('Confermi l\'eliminazione del calendario', function(result) { 
            window.location = $(e.target).parent().attr("href");
        });
    }
}
