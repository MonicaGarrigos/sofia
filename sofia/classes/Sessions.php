<?php
// You should require_once the conexion on each file that contains a class and their methods
require_once("connection.php");

// Class that contains the Methods

Class Sessions {

// The methods below are just examples of SELECT, INSERT and UPDATE
// These methods are samples with PDO structure, If you follow it you dont have to worry about SQL inyection.
  static public function createSession($sessionId){

    $sql =  "INSERT INTO `Sessions` (session_id) VALUES (:session_id)";
    $stmt = Connection::connect()->prepare($sql);
    $stmt->bindParam(":session_id",$sessionId, PDO::PARAM_STR);
    return $stmt->execute();
    $stmt->close();
    $stmt =  null;
  }

}








?>
