<?php

/**
 * Classe Database
 * Gérer la connexion unique entre la base de donnéés (via PDO)
 * On utilise le design pattern Singleton
 */

class Database  {
    // Information de connexion

    private $host = "localhost";
    private $dbname = "techconnect_rdc";
    private $username =  "root";
    private $password = "";

    // Instance unique de connexion
    private static $instance = null;
    private $conneecyion;

    //construccteur privée pour empécher l'instanciation directe
    private function __construct() {
        try{
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password 
            );
            //Activer les erreurs PDO
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Méthode statique : retourne l' unique instance de connexion
     */
    public static function getInstance() {
        if (self::$instance === null){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Retourne l'objet PDO pour éxecuter les requetes
     */
    public function getConnection() {
        return $this->connection;
    }

}

?>