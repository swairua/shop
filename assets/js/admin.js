$(document).ready(function() {
    $('#sidebarToggle').click(function(e) {
        e.preventDefault();
        $('#sidebar').toggleClass('show');
    });
    $('select').each(function() {
        if (!$(this).hasClass('no-select2')) {
            $(this).select2({ theme: 'bootstrap-5', width: '100%' });
        }
    });
    $('.alert-dismissible .btn-close').click(function() {
        $(this).closest('.alert').fadeOut();
    });
});
