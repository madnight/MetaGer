$(document).ready(function() {
    $(".hint").tooltip();
    $("textarea").each(function() {
        auto_grow(this);
    });
    $("textarea").keyup(function() {
        auto_grow(this);
    });
});

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight + 10) + "px";
}