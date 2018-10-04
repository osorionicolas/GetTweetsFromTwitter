<?php

require_once "Autentificacion.php";
require_once "UserApi.php";

class MWparaAutentificar
{
 /**
   * @api {any} /MWparaAutenticar/  Login User
   * @apiVersion 0.1.0
   * @apiName Login
   * @apiGroup MIDDLEWARE
   * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
   *
   * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
 	* @apiParam {ResponseInterface} response El objeto RESPONSE.
 	* @apiParam {Callable} next  The next middleware callable.
   *
   * @apiExample Como usarlo:
   *    ->add(\MWparaAutentificar::class . ':loginUser')
   */
	public function loginUser($request, $response, $next) {
		$responseObject= new stdclass();
		$responseObject->response="";
		$token = $_REQUEST['token'];
		if($token)
		{
			$responseObject->validToken=true; 
			try 
			{
				Autentificacion::verifyToken($token);
				$responseObject->validToken=true;      
			}
			catch (Exception $e) {      
				$responseObject->excepcion=$e->getMessage();
				$responseObject->validToken=false; 
			}

			if($responseObject->validToken)
			{
				$response = $next($request, $response);
			}
			else
			{
				$responseObject->response="Token no válido";
				$responseObject->elToken=$token;
			}  
		}
		else
		{
			$responseObject->response="No posee token";
		}
		if($responseObject->response!="")
		{
			$nueva=$response->withJson($responseObject, 401);
			return $nueva;
		}
			return $response;	
	}	



	function loginToken($request, $response, $next){
		$responseObject= new stdclass();
		$responseObject->response="";
		$arrayConToken = $_REQUEST['token'];
		if(!$arrayConToken)
		{
			$data = UserApi::verifyAccount($request, $response, $next);
			if($data)
			{
				$token= Autentificacion::createToken($data);
				$payload=Autentificacion::data($token);
				$responseObject->response = "Bienvenido " . $data['username'] . " tu token es: " . $token;
				$nueva=$response->withJson($responseObject, 200);

			}
			else
			{
				$responseObject->response = "Usuario no encontrado";
				$nueva=$response->withJson($responseObject, 200);
			}
		}
		else{
			$responseObject->response =  "Ya posee un token";
			$nueva=$response->withJson($responseObject, 200);
		}
		return $nueva;
	}


}