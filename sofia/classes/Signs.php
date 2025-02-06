<?php
// You should require_once the conexion on each file that contains a class and their methods
require_once("connection.php");

// Class that contains the Methods
class Signs {

     // ** Va a obtener todos los signos del zodiaco de la bbdd
     static public function getSigns() {
         $sql = "SELECT * FROM `zodiacsigns`";
         $stmt = Connection::connect()->prepare($sql);
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
         $stmt->closeCursor();
     }
 }
 

 


?>
