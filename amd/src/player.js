define(["jquery"], function($) {
    return {
        resize : function(identifier) {
            var lastHeight = $(`#ottflix-iframe-${identifier}`).height();

            var newHeight = $(window).height() - 100;
            if (newHeight < 300) newHeight = 300;
            if (lastHeight > lastHeight) newHeight = lastHeight;
            if (newHeight < lastHeight) {
                $(`#ottflix-iframe-${identifier}`).css({
                    height : newHeight + "px",
                    width  : "100%",
                });
            } else {
                var newVidth = (newHeight / 9) * 16;
                if (newVidth > $(window).width()) {
                    var newHeight2 = ($(window).width() / 16) * 9;
                    $(`#ottflix-iframe-${identifier}`).css({
                        height : newHeight2 + "px",
                        width  : "100%",
                    });
                } else {
                    $(`#ottflix-iframe-${identifier}`).css({
                        height : newHeight + "px",
                        width  : newVidth + "px",
                    });
                }
            }
        }
    };
});