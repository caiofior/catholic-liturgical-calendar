window.operateEvents = {
    "click .trash": function (e) {
        e.preventDefault();
        bootbox.confirm('Confermi l\'eliminazione dela preghiera', function(result) { 
            window.location = $(e.target).parent().attr("href");
        });
    }
}
