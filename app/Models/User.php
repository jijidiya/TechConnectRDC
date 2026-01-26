<?php

/**
 * User Model
 *
 * Gère les utilisateurs du système :
 *  - client
 *  - fournisseur
 *  - admin
 *
 * Table : users
 * Champs principaux :
 *  id, role, nom, email, password_hash, telephone, created_at
 */

class User
{
    /**
     * Connexion PDO
     */
    private PDO $pdo;

    /**
     * Constructeur
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       CRÉATION
       ===================================================== */

    /**
     * Créer un nouvel utilisateur
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO users (role, nom, email, password_hash, telephone)
            VALUES (:role, :nom, :email, :password_hash, :telephone)
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':role'          => $data['role'],
            ':nom'           => $data['nom'],
            ':email'         => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':telephone'     => $data['telephone'] ?? null,
        ]);
    }

    /* =====================================================
       RECHERCHE
       ===================================================== */

    /**
     * Récupérer un utilisateur par email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /* =====================================================
       AUTHENTIFICATION
       ===================================================== */

    /**
     * Vérifier email + mot de passe
     */
    public function verify(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return null;
        }

        return password_verify($password, $user['password_hash'])
            ? $user
            : null;
    }

    /* =====================================================
       MISE À JOUR
       ===================================================== */

    /**
     * Mettre à jour les informations d’un utilisateur
     */
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE users SET
                nom = :nom,
                email = :email,
                telephone = :telephone
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nom'       => $data['nom'],
            ':email'     => $data['email'],
            ':telephone' => $data['telephone'],
            ':id'        => $id
        ]);
    }

    /* =====================================================
       SUPPRESSION
       ===================================================== */

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM users WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }
}
