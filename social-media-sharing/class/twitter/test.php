<?php
  
  include('autoload.php');
  use Abraham\TwitterOAuth;
  $twitter = new TwitterOAuth('3WE0Tm4dKBzWpyt43nEeoFAcu', 'x44jA11YBC16WZHruAc15JHU7UgaK2z9v2l3ANvkqhpVfuInn1', '884545507-Q1rju5XrMxxlRqBhi3hGidvcxJvBpNJhct10Qmdj', 'NrYAWorEIUcmDqiqdsLUpdmMGdv7rA0pdBn1ANQoAP8XP');
  
    function testPostStatusesUpdateWithMedia()
    {
        $twitter->setTimeouts(60, 30);
        // Image source https://www.flickr.com/photos/titrans/8548825587/
        $file_path = __DIR__ . '/kitten.jpg';
        $result = $twitter->upload('media/upload', array('media' => $file_path));
        $this->assertEquals(200, $twitter->getLastHttpCode());
        $this->assertObjectHasAttribute('media_id_string', $result);
        $parameters = array('status' => 'Hello World ' . time(), 'media_ids' => $result->media_id_string);
        $result = $twitter->post('statuses/update', $parameters);
        $this->assertEquals(200, $twitter->getLastHttpCode());
        if ($twitter->getLastHttpCode() == 200) {
            $result = $twitter->post('statuses/destroy/' . $result->id_str);
        }
        return $result;
    }
  
?>