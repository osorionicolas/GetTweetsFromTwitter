<?php

require_once 'vendor/j7mbo/twitter-api-php/TwitterAPIExchange.php';

class Tweet
{
	public $id;
	public $account;
	public $creationDate;
	public $text;
	public $inReplyToID;
	public $inReplyToName;


	/** 
	 * Función utilizada para llamar a la API de twitter y traer los últimos (10 en este caso) tweets del usuario.
	 * 
	 * return: un nodeset con los tweets del usuario
	*/
	function getTweets(){
		ini_set('display_errors', 1);

		$settings = array(
			'oauth_access_token' => "",
			'oauth_access_token_secret' => "",
			'consumer_key' => "",
			'consumer_secret' => ""
		);

		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name='.$this->account.'&count=10&tweet_mode=extended';        
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		$nodesetFromJSON =  json_decode($twitter->setGetfield($getfield)
					->buildOauth($url, $requestMethod)
					->performRequest());

		if($this->id != null)
		{
			$rawdata = Tweet::getSingleTweet($nodesetFromJSON, $this->id);
		}
		else{
			$rawdata = Tweet::getArrayTweets($nodesetFromJSON);
		}

		return $rawdata;
	}
	

	/** 
	 * Función utilizada para llamar a la API de twitter y traer 
	 * los últimos (10 en este caso) tweets del usuario.
	 * 
	 * return: un nodeset con los atributos especificos solicitados
	*/
    function getArrayTweets($nodesetFromJSON){
	
		if(!isset($nodesetFromJSON->errors))
		{
			$rawdata = array();
			$num_items = count($nodesetFromJSON);
			for($i=0; $i<$num_items; $i++){
	
				$userTweets = $nodesetFromJSON[$i];
	
				$creationDate = $userTweets->created_at;
				$text = $userTweets->full_text;
				
				$rawdata[$i]["created_at"]=$creationDate;
				$rawdata[$i]["text"]=$text;
				
				if(isset($userTweets->in_reply_to_user_id) && isset($userTweets->in_reply_to_screen_name))
				{
					$inReplyToID = $userTweets->in_reply_to_user_id;
					$inReplyToName = $userTweets->in_reply_to_screen_name;
					$inReplyTo = array('id'=>$inReplyToID, 'name'=>$inReplyToName);
					$rawdata[$i]["in_reply"]=$inReplyTo;
	
				}
			}
			return $rawdata;
		}
		else
		{
			return $nodesetFromJSON->errors[0]->message;
		}

    }


	/** 
	 * Función utilizada para llamar a la API de twitter y traer 
	 * el tweet seleccionado del usuario (de los últimos 10 en este caso).
	 * 
	 * return: un nodeset con los atributos especificos solicitados
	*/
	function getSingleTweet($nodesetFromJSON, $id){
        $rawdata = array();

		$userTweets = $nodesetFromJSON[$id];
	
		$creationDate = $userTweets->created_at;
		$text = $userTweets->full_text;
		
		$rawdata["created_at"]=$creationDate;
		$rawdata["text"]=$text;
		
		if(isset($userTweets->in_reply_to_user_id) && isset($userTweets->in_reply_to_screen_name))
		{
			$inReplyToID = $userTweets->in_reply_to_user_id;
			$inReplyToName = $userTweets->in_reply_to_screen_name;
			$inReplyTo = array('id'=>$inReplyToID, 'name'=>$inReplyToName);
			$rawdata["in_reply"]=$inReplyTo;

		}

        return $rawdata;
    }
}
