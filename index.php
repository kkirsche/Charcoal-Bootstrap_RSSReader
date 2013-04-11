<?php
    require_once "model/RSSFeed.php";
    $RSSFeed = new RSSFeed();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/?locale=en">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Charcoal</a>
                    <div class="nav-collapse collapse">
                        <!--<form class="navbar-form pull-right">
                            <input class="span2" type="text" placeholder="Email">
                            <input class="span2" type="password" placeholder="Password">
                            <button type="submit" class="btn">Sign in</button>
                        </form>-->
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="span4">
                    <ul class="nav nav-list">
                        <li class="active"><a href="#">Home</a></li>
                        <li>
                                <button class="btn" data-toggle="collapse" data-target="#subscribeToNewFeed">Subscribe <span class="caret"></span></button>

                                <div id="subscribeToNewFeed" class="collapse in">
                                    <form action="#" method="get">
                                        <input type="text" class="span3" />
                                    </form>
                                </div>
                        </li>
                        <li class="divider"></li>
                        <li class="nav-header">All Items</li>
                            <li><a href="#"><i class="icon-star"></i> Starred items</a></li>
                            <li><a href="#"><i class="icon-fire"></i> Trends</a></li>
                        <li class="divider"></li>
                        <li class="nav-header">Subscriptions</li>
                        <ul class="nav nav-list">
                            <li><a href="#" data-toggle="collapse" data-target="#Apple"><i class="icon-folder-close"></i> Apple</a></li>
                            <div class="collapse in" id="Apple">
                                <ul class="nav nav-list">
                                    <li><a href="http://cultofmac.com.feedsportal.com/c/33797/f/606249/index.rss" class="feedName"><img src="img/exampleFiles/cultOfMac.png" alt="Cult of Mac" /> Cult of Mac</a></li>
                                    <li><a href="#" class="feedName">Macworld</a></li>
                                    <li><a href="#" class="feedName">iPad.AppStorm</a></li>
                                    <li><a href="#" class="feedName">iPhone.AppStorm</a></li>
                                    <li><a href="#" class="feedName">TUAW</a></li>
                                    <li><a href="#" class="feedName">Mac.AppStorm</a></li>
                                    <li><a href="#" class="feedName">9to5Mac</a></li>
                                    <li><a href="#" class="feedName">MacRumors</a></li>
                                </ul>
                            </div>
                            <li><a href="#"><i class="icon-folder-close"></i> AskMen</a></li>
                            <li><a href="#"><i class="icon-folder-close"></i> Dance Music</a></li>
                            <li><a href="#"><i class="icon-folder-close"></i> Design &amp; Development</a></li>
                            <li><a href="#"><i class="icon-folder-close"></i> iOS Development</a></li>
                            <li><a href="#"><i class="icon-folder-close"></i> Leadership</a></li>
                            <li><a href="#"><i class="icon-folder-close"></i> Learning</a></li>
                            <li><a href="#"><i class="icon-folder-close"></i> Video Games</a></li>
                        </ul>
                    </ul>
                </div><!--/div.span3-->
                <div class="row">
                    <div class="span7" id="feedBody">
                        <?php
                            $RSSFeed->getFeed("http://feedproxy.google.com/nettuts");
                        ?>
                    </div>
                </div>
            </div><!--/div.row-->
            <hr />
            <footer>
                <p>&copy; Kevin Kirsche 2012&ndash;<?php echo date('Y'); ?> </p>
            </footer>

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(".collapse").collapse();
            $(".alert").alert();
        </script>
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
