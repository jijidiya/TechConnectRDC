<?php

/**
 * Class Order
 *
 * Gère la logique liée aux commandes.
 *
 * Une commande :
 * - appartient à un client (users.id)
 * - contient un ou plusieurs produits (order_items)
 *
 * Tables concernées :
 * - orders
 * - order_items
 */
class Order
{
    /**
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

    /* =====================================================
       CREATE
       ===================================================== */

    /**
     * Créer une nouvelle commande
     *
     * @param int $clientId
     * @param float $total
     * @return int|false ID de la commande ou false
     */
    public function create(int $clientId, float $total)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO orders (client_id, total, statut, created_at)
             VALUES (?, ?, 'en_attente', NOW())"
        );

        $success = $stmt->execute([$clientId, $total]);

        return $success ? (int)$this->pdo->lastInsertId() : false;
    }

    /**
     * Ajouter un produit à une commande
     *
     * @param int $orderId
     * @param int $productId
     * @param int $quantity
     * @param float $price
     * @return bool
     */
    public function addItem(
        int $orderId,
        int $productId,
        int $quantity,
        float $price
    ): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO order_items (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $orderId,
            $productId,
            $quantity,
            $price
        ]);
    }

    /* =====================================================
       READ
       ===================================================== */

    /**
     * Récupérer une commande par ID
     */
    public function getById(int $orderId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM orders WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$orderId]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order ?: null;
    }

    /**
     * Récupérer les commandes d’un client
     */
    public function getByClient(int $clientId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM orders
             WHERE client_id = ?
             ORDER BY created_at DESC"
        );

        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les produits d’une commande
     */
    public function getItems(int $orderId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT oi.*, p.nom
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?"
        );

        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       UPDATE
       ===================================================== */

    /**
     * Mettre à jour le statut d’une commande
     *
     * @param int $orderId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE orders SET statut = ? WHERE id = ?"
        );

        return $stmt->execute([$status, $orderId]);
    }

    /* =====================================================
       DELETE
       ===================================================== */

    /**
     * Supprimer une commande
     *
     * Les items associés sont supprimés via ON DELETE CASCADE
     */
    public function delete(int $orderId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM orders WHERE id = ?"
        );

        return $stmt->execute([$orderId]);
    }
}
