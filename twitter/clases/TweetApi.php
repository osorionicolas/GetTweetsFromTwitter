<?php

require_once 'Tweet.php';

use Firebase\JWT\JWT;

class TweetApi extends Tweet 
{
  /** 
	 * Función utilizada llamar a la clase Tweet y traer los tweets del usuario
	 * 
   * Parametros: account -> cuenta de usuario
   * 
	 * return: tweets del usuario con los siguientes atributos: created_at, full_text, 
   * in_replay(en caso de existir), id y usuario del reply
	*/
   public function getAll($request, $response, $args) {
    if(isset($_REQUEST['account']))
    {
      $tweet = new Tweet();
      $tweet->account = $_REQUEST['account'];
      $rawdata=$tweet->getTweets();
      $newresponse = $response->withJson($rawdata, 200);  
      return $newresponse;
    }
    else
    {
      $newresponse = $response->withJson("Account is not defined", 404);  
    }

    return $newresponse;
  }

   /** 
	 * Función utilizada llamar a la clase Tweet y traer un tweet del usuario
	 * 
   * Parametros: account -> cuenta de usuario
   *             id -> id del tweet que se quiere buscar
   * 
   * En caso de no setear un atributo account tiene seteada una cuenta por defecto.
   * 
	 * return: tweets del usuario con los siguientes atributos: created_at, full_text, 
   * in_replay(en caso de existir), id y usuario del reply
	*/
  public function getOne($request, $response, $args) {
    if(isset($_REQUEST['id']))
      {
        $responseObject= new stdclass();
        $tweet = new Tweet();
        $tweet->id = $_REQUEST['id'];
        if(isset($_REQUEST['account']))
        {  
          $tweet->account = $_REQUEST['account'];
        }
        else
        {
          $tweet->account = 'Eli_piojo';
        }
        $rawdata = $tweet->getTweets();

        $responseObject->Tweet = $rawdata;

        $newresponse = $response->withJson($responseObject, 200);
      }
      else
      {
        $newresponse = $response->withJson("ID is not defined", 404);  
      }
      return $newresponse;
    }


}

?>