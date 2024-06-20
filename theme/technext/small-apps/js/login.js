$("#show_register").click(function(e) {
    $("#show_register").hide();
    $("#login").hide();
    $("#show_login").show();
    $("#register").show();
    $("#recover").hide();
});
$("#show_login").click(function(e) {
    $("#show_register").show();
    $("#login").show();
    $("#show_login").hide();
    $("#register").hide();
    $("#recover").hide();
});
$("#show_recover").click(function(e) {
    $("#show_register").hide();
    $("#login").hide();
    $("#show_login").hide();
    $("#register").hide();
    $("#recover").show();
});