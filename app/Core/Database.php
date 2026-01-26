<?php

class Database
{
    /**
     * Instance PDO utilisée pour toutes les requêtes SQL
     */
    private PDO $pdo;

    /**
     * Constructeur de la classe Database
     *
     * @param array|null $config
     *  - Si $config est fourni : il est utilisé directement
     *  - Sinon : la configuration est chargée depuis le fichier config.php
     */
    public function __construct(?array $config = null)
    {
        // Si aucune configuration n'est passée en argument,
        // on charge celle définie dans le fichier de configuration
        if ($config === null) {
            $config = require __DIR__ . '/../../includes/config.php';
        }

        // Construction du DSN (Data Source Name) pour PDO
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8',
            $config['host'],
            $config['db']
        );

        try {
            // Création de l'objet PDO (connexion à la base de données)
            $this->pdo = new PDO(
                $dsn,
                $config['user'],
                $config['pass'], 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    /**
     * Retourne l'instance PDO afin d'exécuter des requêtes SQL
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
