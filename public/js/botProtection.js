$(document).ready(function() {
    botProtection();
});

function botProtection() {
    if ($("meta[name=pqr]").length > 0) {
        var link = atob($("meta[name=pqr]").attr("content"));
        var hash = $("meta[name=pq]").attr("content");
        document.location.href = link + "&bot=" + hash;
    }
}