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

        private function urlGetContents($url) {
            if (!function_exists('curl_init')) {
                die("CURL is not installed!");
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        }

        public function getFeed($feed_url) {
            $content = $this->urlGetContents($feed_url);
            try { 
                $xml = new SimpleXmlElement($content); 
            } catch (Exception $e) { 
                /* the data provided is not valid XML */
                echo "<div class=\"alert alert-error span7\">"
                . "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>"
                . "<strong>Error!</strong> Invalid feed URL."
                . "</div>";
                return false; 
            }

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

    if (isset($_GET['url']) || isset($_POST['url'])) {
        $RSS = new RSSFeed();
        $RSS->getFeed($_GET['url']);
    }
?>