<?php

require_once 'clases/Tweet.php';
require_once 'PHPUnit/Autoload.php';

class UnitTest extends \PHPUnit_Framework_TestCase
{

    public function testGetTenTweets() 
    {
        $tweet = new Tweet();
        $tweet->account = 'Eli_piojo';
        $this->assertCount(10, $tweet->getTweets());

    }


    public function testGetTweetsFromNonExistingAccount()
    {
        $tweet = new Tweet();
        $tweet->account = 'aaeeiioouubbddjj';
        $error = $tweet->getTweets();
        $this->assertSame('Sorry, that page does not exist.', $error);
    }

}

?>