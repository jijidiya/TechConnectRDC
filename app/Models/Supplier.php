<?php

/**
 * Class Fournisseur
 *
 * Gère toute la logique liée aux fournisseurs (vendeurs B2B).
 * Un fournisseur est lié à un utilisateur via users.id.
 *
 * Table concernée : `fournisseurs`
 */
class Supplier
{
    /**
     * @var PDO Connexion PDO
     */
    private $pdo;

    /**
     * Constructeur
     *
     * @param PDO $pdo Instance PDO injectée
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       CREATE
       ===================================================== */

    /**
     * Créer un profil fournisseur
     * (appelé après l’inscription d’un user avec role=fournisseur)
     *
     * @param array $data
     *  - user_id
     *  - description (optionnel)
     *  - logo (optionnel)
     *
     * @return bool
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO fournisseurs (user_id, description, logo, statut)
            VALUES (:user_id, :description, :logo, :statut)
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':user_id'     => $data['user_id'],
            ':description' => $data['description'] ?? null,
            ':logo'        => $data['logo'] ?? null,
            ':statut'      => $data['statut'] ?? 'en_attente',
        ]);
    }

    /* =====================================================
       READ
       ===================================================== */

    /**
     * Récupérer un fournisseur par ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM fournisseurs WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);

        $fournisseur = $stmt->fetch();
        return $fournisseur ?: null;
    }

    /**
     * Récupérer un fournisseur via l’ID utilisateur
     * (très utilisé après login)
     */
    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM fournisseurs WHERE user_id = ? LIMIT 1"
        );
        $stmt->execute([$userId]);

        $fournisseur = $stmt->fetch();
        return $fournisseur ?: null;
    }

    /**
     * Récupérer tous les fournisseurs (admin / listing)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT f.*, u.nom, u.email
             FROM fournisseurs f
             JOIN users u ON f.user_id = u.id
             ORDER BY f.id DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * Récupérer uniquement les fournisseurs actifs
     */
    public function getActive(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT f.*, u.nom, u.email
             FROM fournisseurs f
             JOIN users u ON f.user_id = u.id
             WHERE f.statut = 'actif'
             ORDER BY f.id DESC"
        );

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les produits d’un fournisseur
     *
     * @param int $userId ID utilisateur (role fournisseur)
     * @return array
     */
    public function getProducts(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*
            FROM products p
            JOIN fournisseurs f ON p.fournisseur_id = f.id
            WHERE f.user_id = ?
            ORDER BY p.id DESC"
        );

        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les commandes liées aux produits du fournisseur
     *
     * @param int $userId
     * @return array
     */
    public function getOrders(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT o.*
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            JOIN fournisseurs f ON p.fournisseur_id = f.id
            WHERE f.user_id = ?
            GROUP BY o.id
            ORDER BY o.created_at DESC"
        );

        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les conversations du fournisseur
     *
     * @param int $userId
     * @return array
     */
    public function getConversations(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT u.id, u.nom, u.email
            FROM messages m
            JOIN users u 
            ON (u.id = m.sender_id OR u.id = m.receiver_id)
            WHERE (m.sender_id = ? OR m.receiver_id = ?)
            AND u.id != ?
            ORDER BY u.nom"
        );

        $stmt->execute([$userId, $userId, $userId]);
        return $stmt->fetchAll();
    }




    /* =====================================================
       UPDATE
       ===================================================== */

    /**
     * Mettre à jour un fournisseur
     *
     * @param int $id
     * @param array $data
     *  - description
     *  - logo
     *  - statut
     *
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE fournisseurs SET
                description = :description,
                logo = :logo,
                statut = :statut
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':description' => $data['description'] ?? null,
            ':logo'        => $data['logo'] ?? null,
            ':statut'      => $data['statut'] ?? 'en_attente',
            ':id'          => $id,
        ]);
    }

    /**
     * Activer un fournisseur (admin)
     */
    public function activate(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE fournisseurs SET statut = 'actif' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    /**
     * Désactiver un fournisseur
     */
    public function deactivate(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE fournisseurs SET statut = 'inactif' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    /* =====================================================
       DELETE
       ===================================================== */

    /**
     * Supprimer un fournisseur
     * (le user associé sera supprimé via ON DELETE CASCADE)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM fournisseurs WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
