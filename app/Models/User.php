<?php

/**
 * Classe User
 * 
 * Gère toute la logique utilisateur : inscription, connexion, recherche
 * Utilise PDO via Database.php
 * 
 * Représente les utilisateurs du système :
 *  - clients
 *  - fournisseurs
 *  - admin
 * 
 * Champs typique de la table users :  
 * id, role, nom,  email, password_hash, telephone, adresse,  entreprise, created_at
 */

class User {
    /**
     * @var PDO Connexion à la base des données
     */
    private $pdo;

    /**
     * Constructeur
     * 
     * @param PDO $pdo une instance PDO injectée depuis Database::getPdo() 
     */
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }




    /**
     * Valide une adresse email
     * 
     * @param string $email
     * @return bool True si valide 
     */
    public function isValidEmail(string $email) : bool{
        return filter_var($email, FILTER_VALIDE_EMAIL) !== false;
    }

    /**
     * Crée un nouvel utilisateur (inscription)
     * 
     * @param bool True si l'insertion a réussi
     */
    public function creatte(array $data) : bool {
        $sql = "
            INSERT INTO users (role, nom, email, password_hash, telephone)
            VALUES (:role, :nom, :email, :password_hash, :telephone)
        ";

        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':role'             => $data['role'],
            ':nom'              => $data['nom'],
            ':email'            => $data['email'],
            ':password_hash'    => $data['password_hash'],
            ':telephone'        => $data['telephone'] ?? null,
        ]);
    }



    /**
     * Récupère un utilisateur par son email
     * 
     * @param string email
     * @return array|null tableau utilisateur ou null si introuvable
     */
    public function findByEmail(string $email){
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = this->pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Récupère un utilisqteur par ID 
     * 
     * @param int $id
     *  @return array|null tableau utilisateur ou null si introuvable
     */
    public function findById(int $id): ? array{
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $stmt = this->pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();
        return $user ?: null;
    }


    /**
     * Vérifie un email + mot de passe 
     * 
     * @param string $email
     * @param string $password
     * @return array| l'utilisateur si OK, false sinon
     */
    public function verify(string $email, string $password){
        $user = this->findByEmail($email);

        if (!$user){
            return false;
        }

        if (password_verify($password, $user['password_hash'])){
            return $user;
        }
        
        return false,
    }

    /**
     * Vérifie si un email existe déjà pour éviter les doublons à l'inscription
     * 
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool{
         $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
         $stmt = $this->pdo->prepare($sql);
         $stmt->execute([$email]);

         return $stmt->fetch() ? true : false;
    }

    /**
     * Met à  jour un utilisateur(nom, email, téléphone, etc...)
     * 
     * @param int $id
     * @param array data
     * @return bool
     */
    public function udapte(int $id, array $data):  bool {
        $sql = "
            UPDATE users SET
                nom = :nom,
                email = :email,
                telephone = :telephone
            WHERE id = :id
        ";
        $stmt = $this-pdo->prepare($sql);

        return $stmt->execute([
            ':nom'              => $data['nom'],
            ':email'            => $data['email'],
            ':telephone'        => $data['telephone'],
            'id'                => $id
        ]);
    }

    /**
     * Supprime un utilisateur selon son ID
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
