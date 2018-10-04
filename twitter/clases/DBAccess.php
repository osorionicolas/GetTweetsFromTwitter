<?php
class DBAccess
{
    private static $dataAccessObject;
    private $objetoPDO;
 
    private function __construct()
    {
        try { 
            $this->objetoPDO = new PDO('mysql:host=localhost;dbname=twitter;charset=utf8', 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
            } 
        catch (PDOException $e) { 
            print "Error!: " . $e->getMessage(); 
            die();
        }
    }
 
    public function returnQuery($sql)
    { 
        return $this->objetoPDO->prepare($sql); 
    }

    public static function accessObject()
    { 
        if (!isset(self::$dataAccessObject)) {          
            self::$dataAccessObject = new DBAccess(); 
        } 
        return self::$dataAccessObject;        
    }
 
 
     // Evita que el objeto se pueda clonar
    public function __clone()
    { 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }
}
?>