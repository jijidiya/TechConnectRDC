<?php
require_once __DIR__ . '/Database.php';

/**
 * Classe User
 * Classe mere pour tous les utilisateurs(Client et Fournisseur)
 */

class User {
    protected $id;
    protected $nom;
    protected $email;
    protected $motDePasse;
    protected $telephone;
    protected $role; // 'client' ou 'fournisseur'
    
    protected $db //instance PDO

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * ====================================
     * Methodes : Enregistrement et login
     * ====================================
     */

    // Enregistrer un nouvel utilisateur 
    public function register($nom, $email, $motDePasse, $telephone, $role){
        $motDePasseHash = password_hash($motDePasse, PASSWORD_BCRYPT)

        // TO DO : continuer le code 
    }
}