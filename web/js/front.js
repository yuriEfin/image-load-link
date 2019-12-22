$(function () {
    "use strict";

    const loadContent = function (container) {
        $(container).load("/image/load");
    };
    const clear = function(){
        $("#uploadform-link").val('');
    }
    $(document).on("submit", "#js-upload", function (e) {
        e.preventDefault();
        $.ajax({
            url: "/image/upload",
            data: $(e.currentTarget).serializeArray(),
            type: "POST",
            success: function () {
                // @todo: check has result
                loadContent("#container-image");
            }
        });
        clear();
        return false;
    });
});