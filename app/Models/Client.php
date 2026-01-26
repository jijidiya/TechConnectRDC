<?php

/**
 * Class Client
 *
 * Représente un client de la plateforme TechConnect RDC
 * un client peut :
 *  - peut se connecter 
 *  - peut acheter des produits
 *  - peut discuter avec des fournisseurs
 * 
 * Table concernée : users
 * (avec un champ role = 'client') 
 */

class Client
{
    /**
     * Instance PDO
     * 
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Constructeur
     * 
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* ======================================================
        RÉCUPÉRATION DES CLIENTS
       ======================================================*/

    /**
     * Récupérer un client par ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getById(int $id) : ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
            FROM users
            WHERE id = ? AND role = 'client'"
        );
        $stmt->execute([$id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        return $client ?: null;
    }

    /**
     * Récupérer un client par $email
     * 
     * @param string $email
     * @return array|null 
     */
    public function getByEmail(string $email) : ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
            FROM users
            WHERE email = ? AND role = 'client'"
        );
        $stmt->execute([$email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        return $client ?: null;
    }
    /* ======================================================
        INSCRIPTION CLIENT
       ======================================================*/

    /**
     * Créer un nouveau client 
     * 
     * @param array $data
     * @return array|null
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (nom, email, password, role)
            VALUES (?, ?, ?, 'client')"
        );

        return $stmt->execute([
            $data['nom'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }
    /* ======================================================
        COMMANDES CLIENT
       ======================================================*/

    /**
     * Récupérer les commandes d'un client
     * 
     * @param int $clientId
     * @return array
     */
    public function getOrders(int $clientId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
            FROM orders
            WHERE user_id = ?
            ORDER by created_at DESC"
        );

        $stmt->execute([$clientId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /* ======================================================
        MESSAGES (CHAT)
       ======================================================*/

    /**
     * Récupérer les conversations du client 
     * 
     * @param int $clientId
     * @return array
     */
    public function getConversations(int $clientId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT
                CASE
                    WHEN sender_id = ? THEN receiver_id
                    ELSE sender_id
                END AS other_user_id
            FROM messages
            WHERE sender_id = ? OR receiver_id = ?"
        );

        $stmt->execute([
            $clientId,
            $clientId,
            $clientId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}