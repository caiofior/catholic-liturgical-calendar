$("#show_register").click(function(e) {
    $("#show_register").hide();
    $("#login").hide();
    $("#show_login").show();
    $("#register").show();
});
$("#show_login").click(function(e) {
    $("#show_register").show();
    $("#login").show();
    $("#show_login").hide();
    $("#register").hide();
});
