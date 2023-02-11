window.operateEvents = {
    "click .trash": function (e) {
        e.preventDefault();
        bootbox.confirm('Confermi l\'eliminazione dela preghiera', function(result) { 
            window.location = $(e.target).parent().attr("href");
        });
    }
};
$("a.add").click(function (e) {
        let url = $(this).attr("href")+"?calendario="+$("select[name=calendario] option:selected").attr("value"); 
        $(this).attr("href",url);
});
