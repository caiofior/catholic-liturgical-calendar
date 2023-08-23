window.operateEvents = {
    "click .trash": function (e) {
        e.preventDefault();
        bootbox.confirm('Confermi l\'eliminazione dela preghiera', function(result) { 
            window.location = $(e.target).parent().attr("href");
        });
    }
};
$(function(){
    $("a.add").click(function (e) {
            let url = $(this).attr("href");
            url += "?calendario="+$("select[name=calendario] option:selected").attr("value");
            url += "&giorno="+$("input[name=today]").val(); 
            $(this).attr("href",url);
    });
    $("input[name=today], select[name=calendario]").change(function (e) {
        
        let url = window.location.toString();
        if(url.indexOf('?')>0){
            url = url.substring(0, url.indexOf('?'));
        }    
        url += '?calendario='+$("select[name=calendario] option:selected").attr("value");
        url += '&giorno='+$("input[name=today]").val();
        window.location.href = url;
    });
});
