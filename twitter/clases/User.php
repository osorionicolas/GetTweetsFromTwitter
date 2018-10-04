<?php

require_once 'DBAccess.php';

class User{

    public $id;
	public $username;
	public $password;

	/** 
	 * Función utilizada para hacer una llamada a la base de datos 
	 * y traer los datos del usuario que se está logueando
	 * return: un objeto usuario con el id y username del mismo
	*/
	public function getUser()
	{

	$dataAccessObject = DBAccess::accessObject(); 
	$query = $dataAccessObject->returnQuery("select id,username from users where username=? and password=?");

	$query->bindValue(1, $this->username, PDO::PARAM_STR);
	$query->bindValue(2, $this->password, PDO::PARAM_STR);
	$query->execute();

	$user= $query->fetchObject('user');
	return $user;
		
	}
}
     
     ?>