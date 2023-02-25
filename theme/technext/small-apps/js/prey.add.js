tinymce.init({
    selector: 'textarea',
    language: 'it',
    language_url: '../tinymce-langs/langs/it.js',
    promotion: false,
    branding: false,
    browser_spellcheck: true,
    plugins: 'charmap'
    
});

$("input[name=today], select[name=calendario]").change(function (e) {
    let url = window.location.href;    
    url += '?calendario='+$("input[name=calendar_id]").val();
    url += '&giorno='+$("input[name=today]").val();
    window.location.href = url;
});