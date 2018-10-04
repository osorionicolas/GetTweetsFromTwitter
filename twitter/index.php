<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'vendor/autoload.php';
require_once 'clases/MWparaAutentificar.php';
//require_once 'clases/MWparaCORS.php';
require_once 'clases/TweetApi.php';
require_once 'unitTests/UnitTest.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$app->group('/', function() use ($app){

    $this->get('getTenTweets', \TweetApi::class . ':getAll'); 
    $this->get('getSingleTweet', \TweetApi::class . ':getOne')->add(\MWparaAutentificar::class . ':loginUser');
    $this->post('login', \MWparaAutentificar::class . ':loginToken');

    $app->group('test/', function(){

        $this->get('getTenTweets', \UnitTest::class . ':testGetTenTweets');
        $this->get('getTweetsFromNonExistingAccount', \UnitTest::class . ':testGetTweetsFromNonExistingAccount');
    
    });
});


$app->run();


?>