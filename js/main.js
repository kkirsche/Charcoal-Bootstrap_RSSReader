$(document).ready(function() {
    "use strict";

    //Update the feed
    $("a.feedName").click(function(e) {
        e.preventDefault();
        var feedURL;
        feedURL = $(this).attr("href");
        $.ajax('http://kirsches.us/Charcoal/model/RSSFeed.php', {
            data: {url: feedURL},
            beforeSend: function() {
                
            },
            cache: false,
            success: function(result) {
                $("#feedBody").html(result);
            },
            error: function(result) {
                $("#feedBody").hide();
            }
        });
    });
});