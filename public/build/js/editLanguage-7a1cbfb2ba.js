$(document).ready(function() {
    $(".hint").tooltip();
    $(".language-text-area").each(function() {
        auto_grow(this);
    });
    $(".language-text-area").keyup(function() {
        auto_grow(this);
    });
});

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight + 10) + "px";
}