<?php
    //Hanlde all actions related to RSS Feeds
    require_once('Database.php');
    require_once("model/simplepie/autoloader.php");
    function shorten($string, $length)
    {
        // By default, an ellipsis will be appended to the end of the text.
        $suffix = '&hellip;';
     
        // Convert 'smart' punctuation to 'dumb' punctuation, strip the HTML tags,
        // and convert all tabs and line-break characters to single spaces.
        $short_desc = trim(str_replace(array("\r","\n", "\t"), ' ', strip_tags($string)));
     
        // Cut the string to the requested length, and strip any extraneous spaces 
        // from the beginning and end.
        $desc = trim(substr($short_desc, 0, $length));
     
        // Find out what the last displayed character is in the shortened string
        $lastchar = substr($desc, -1, 1);
     
        // If the last character is a period, an exclamation point, or a question 
        // mark, clear out the appended text.
        if ($lastchar == '.' || $lastchar == '!' || $lastchar == '?') $suffix='';
     
        // Append the text.
        $desc .= $suffix;
     
        // Send the new description back to the page.
        return $desc;
    }
    class RSSFeed {

        public function getFeed($feed_url) {
            $feed = new SimplePie($feed_url);
            $feed->init();
            $feed->handle_content_type();
            foreach ($feed->get_items() as $item) {
                $output = "<article>"
                . "<h3><a href=\"" . $item->get_permalink() . "\" title=\"" . $item->get_title() . "\" class=\"articleTitle\">" . $item->get_title() . "</a></h3><p>";

                if ($category = $item->get_category()) {
                    $output .= $category->get_label() . " ";
                }

                $output .= $item->get_date();


                $output .= "</p><p>";

                $output .= shorten($item->get_description(), 600) . "<br /><br />" . "<a href=\"" . $item->get_permalink() . "\" title=\"Read More\" class=\"btn btn-info\">Read More</a>";

                $output .= "</p>";

                echo $output;
            }//end foreach($feed->get_items() as $item)
        }//end getFeed($feed_url)

        public function importRSSFeeds($xmlFile, $DB) {
            $xml = simplexml_load_file($xmlFile);
            foreach($xml as $feed) {
                foreach($feed->outline as $thisFeed) {
                    if($thisFeed->outline['type'] == "rss") {
                            $DB->addFeedToDatabase($thisFeed['text'], $thisFeed['title'], "folder", "", "");
                        foreach($thisFeed->outline as $feeds) {
                            $DB->addFeedToDatabase($feeds['text'], $feeds['title'], $feeds['type'], $feeds['xmlUrl'], $feeds['htmlUrl']);
                        }
                        echo "<br /><br />";
                    }
                }
            }
        } //end importRSSFeeds($xmlFile)

        public function getFeedList() {
            $lastType = "";
            $DB = new Database();
            $result = $DB->returnFeedList();
            foreach($result as $individualFeed) {
                    if($individualFeed['type'] == "folder") {
                        if ($lastType == "rss") {
                            echo "</ul></div>";
                        }
                        echo "<li><a href=\"#\" data-toggle=\"collapse\" data-target=\"#" . str_replace(" ", "", $individualFeed['title']) ."\"><i class=\"icon-folder-close\"></i>" . $individualFeed['title'] . "</a></li>";
                        echo "<div class=\"collapse in\" id=\"" . str_replace(" ", "", $individualFeed['title']) . "\">";
                        echo "<ul class=\"nav nav-list\">";
                    } else if($individualFeed['type'] == "rss") {
                        echo "<li><a href=\"" . $individualFeed['xmlUrl'] . "\" class=\"feedName\">" . $individualFeed['title'] . "</a></li>";
                    }
                    $lastType = $individualFeed['type'];
                }
            $DB->closeDatabaseConnection();
        }
    }//end class RSSFeed

    if (isset($_GET['url'])) {
        $RSS = new RSSFeed();
        $RSS->getFeed($_GET['url']);
    } else if(isset($_POST['url'])) {
        $RSS = new RSSFeed();
        $RSS->getFeed($_POST['url']);
    }
?>