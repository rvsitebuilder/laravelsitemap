$('#sitemapSetup').on('click', function() {
    statusAnimate('wait');
    $('#sitemapSetup').attr('disabled', true); // disabled button

    $.ajax({
        type: 'GET',
        url: '/admin/laravelsitemap/laravelsitemap/generate',
        data: {
            type: 'generate',
        },
        success: function(data) {
            if (data.status === 'success') {
                statusAnimate('success');
                $('#spanSuccessMsg').html(data.msg);
                $('#spanLastCreatedFile').html(data.last_created);
            } else {
                statusAnimate('error');
                alert(data.status);
            }
            $('#sitemapSetup').attr('disabled', false); // enable button
        },
        error: function(xhr, ajaxOptions, thrownError) {
            statusAnimate('error');
            alert('Have some error from backend\n' + thrownError);
            $('#sitemapSetup').attr('disabled', false); // enable button
        },
    });
});

function statusAnimate(stat) {
    $el_wait = $('#sitemapsetupWait');
    $el_success = $('#sitemapsetupSuccess');
    $el_error = $('#sitemapsetupError');

    $el_wait.addClass('uk-hidden');
    $el_success.addClass('uk-hidden');
    $el_error.addClass('uk-hidden');

    switch (stat) {
        case 'wait':
            $el_wait.removeClass('uk-hidden');
            break;
        case 'success':
            $el_success.removeClass('uk-hidden');
            break;
        case 'error':
            $el_error.removeClass('uk-hidden');
            break;
        default:
            $el_wait.addClass('uk-hidden');
            $el_success.addClass('uk-hidden');
            $el_error.addClass('uk-hidden');
            break;
    }
}
