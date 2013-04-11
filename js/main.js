$(document).ready(function() {
    "use strict";
    function hideResponseDivs() {
        var whatToHide = ["#error", "#loading"], i;
        for (i = 0; i < 2; i++) {
            $(whatToHide[i]).show();
        }
    }

    hideResponseDivs();

    //Update the feed
    $("a.feedName").click(function(e) {
        e.preventDefault();
        var feedURL;
        feedURL = $(this).attr("href");
        $.ajax('model/RSSFeed.php', {
            data: {url: feedURL},
            beforeSend: function() {
                $("#feedBody").hide();
                $("#loading").show();
            },
            cache: false,
            success: function(result) {
                $("#feedBody").html(result);
                $("#feedBody").show();
            },
            error: function(result) {
                $("#feedBody").hide();
            },
            complete: function() {
                $("#loading").hide();
                $("#feedBody").show();
            }
        });
    });
});