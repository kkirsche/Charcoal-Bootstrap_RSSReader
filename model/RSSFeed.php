<?php
    //Hanlde all actions related to RSS Feeds
    require_once('Database.php');
    class RSSFeed {

        private function rssToTime($rss_time) {
                $day = substr($rss_time, 5, 2);
                $month = substr($rss_time, 8, 3);
                $month = date('m', strtotime("$month 1 2011"));
                $year = substr($rss_time, 12, 4);
                $hour = substr($rss_time, 17, 2);
                $min = substr($rss_time, 20, 2);
                $second = substr($rss_time, 23, 2);
                $timezone = substr($rss_time, 26);

                $timestamp = mktime($hour, $min, $second, $month, $day, $year);

                date_default_timezone_set('America/New_York');

                if(is_numeric($timezone)) {
                    $hours_mod = $mins_mod = 0;
                    $modifier = substr($timezone, 0, 1);
                    $hours_mod = (int) substr($timezone, 1, 2);
                    $mins_mod = (int) substr($timezone, 3, 2);
                    $hour_label = $hours_mod>1 ? 'hours' : 'hour';
                    $strtotimearg = $modifier.$hours_mod.' '.$hour_label;
                    if($mins_mod) {
                        $mins_label = $mins_mod>1 ? 'minutes' : 'minute';
                        $strtotimearg .= ' '.$mins_mod.' '.$mins_label;
                    }
                    $timestamp = strtotime($strtotimearg, $timestamp);
                }

                return date("D, F d, Y", $timestamp);
        }//end rssToTime($rss_time)

        public function getFeed($feed_url) {
            $content = file_get_contents($feed_url);
            $xml = new SimpleXmlElement($content);

            foreach($xml->channel->item as $entry) {
                $output = "";
                if (strlen($entry->description) > 1500) {
                    $entry->shortDescription = substr($entry->description, 0, strpos($entry->description," ",1500)) . "&hellip;" . "<br /><br />" . "<a href=\"$entry->link\" title=\"Read More\" class=\"btn btn-info\">Read More</a>";
                } else {
                    $entry->shortDescription = $entry->description . "<br /><br />" . "<a href=\"$entry->link\" title=\"Read More\" class=\"btn btn-info\">Read More</a>";
                }

                //send it to the browser
                    $output .= "<article>";
                        $output .= "<h3><a href=\"$entry->link\" title=\"$entry->title\" class=\"articleTitle\">" . $entry->title . "</a></h3>";
                            if ($entry->category) {
                                $output .= "<p><strong>Category:</strong> " . $entry->category . " &bull; <strong>Date:</strong> " . $this->rssToTime($entry->pubDate) . "</p>";
                            }
                        $output .= $entry->shortDescription;
                    $output .= "</article><hr />";
                echo $output;
            }//end foreach($xml->channel->item as $entry)
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
    }//end class RSSFeed

    if (isset($_GET['url']) || isset($_POST['url'])) {
        $RSS = new RSSFeed();
        $RSS->getFeed($_GET['url']);
    }
?>