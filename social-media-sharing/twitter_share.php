<?php

    include_once('include/config.php');
    require_once('class/twitter.php');

    $config = array(
        'consumerKey' => TWITTER_CONSUMER_KEY,
        'consumerSecret' => TWITTER_CONSUMER_SECRET,
        'oauthToken' => TWITTER_ACCESS_TOKEN,
        'oauthTokenSecret' => TWITTER_ACCESS_SECRET
    );

    $message              = "What to share"; // message to share max 140 characters.
    $link                 = "link to be share"; // sharing link
    $picture              = "picture url to be share";  // picture url to be share

    $twitter = new Twitter( $config );
    $post_id = $twitter->postStatusesUpdateWithMedia($picture, $link, $message);