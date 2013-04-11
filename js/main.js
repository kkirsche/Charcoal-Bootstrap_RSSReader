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
                $("#feedBody").html("<div class=\"alert alert-info span7\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button><strong>Loading Feed&hellip;</strong>");
            },
            cache: false,
            dataType: 'jsonp',
            success: function(result) {
                $("#feedBody").html(result);
            },
            error: function(result) {
                $("#feedBody").hide();
            }
        });
    });
});