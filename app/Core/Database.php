<?php

/**
 * Classe Database
 * Gérer la connexion unique entre la base de donnéés (via PDO)
 */

class Database  {
    private $pdo;

    public function __construct($config) {
        $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset=utf8"
        try{
            $this->pdo = new PDO(
                $dsn,
                $config['user']
                $config['pass'] 
            );
            //Activer les erreurs PDO
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'objet PDO pour éxecuter les requetes
     */
    public function getPdo() {
        return $this->pdo;
    }

}

?>