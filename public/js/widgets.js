function copyCode() {
    $("#codesnippet").select();
    try {
        var successful = document.execCommand('copy');
        if (successful) {
            $('#copyButton').removeClass('btn-default');
            $('#copyButton').addClass('btn-success');
        } else {
            $('#copyButton').removeClass('btn-default');
            $('#copyButton').addClass('btn-danger');
        }
    } catch (err) {
        $('#copyButton').removeClass('btn-default');
        $('#copyButton').addClass('btn-danger');
    }
}