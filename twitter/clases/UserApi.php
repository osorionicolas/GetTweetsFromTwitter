<?php
require_once 'User.php';

class UserApi extends User
{
    /** 
	 * Función utilizada para enviar los datos necesarios al objeto User
	 * return: un objeto array con los datos del usuario
	*/
    public function verifyAccount($request, $response, $args) {
     	
        $responseObject= new stdclass();
        
        $requestArray = $request->getParsedBody();
        $username= $requestArray['username'];
        $password= $requestArray['password'];
        
        $user = new User();
        $user->username=$username;
        $user->password=$password;

        if($user->getUser())
        {
            $user = $user->getUser();
            $responseObject = array('id' => "$user->id", 'username' => "$user->username",'password' => "$user->password"); 
            return $responseObject;
        }
        return $responseObject = array();
    }
}

?>