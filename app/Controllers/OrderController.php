<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/UserController.php';

/**
 * OrderController
 *
 * Gère les actions liées aux commandes :
 *  - création d’une commande (client)
 *  - consultation des commandes client
 *  - consultation des commandes fournisseur
 *  - mise à jour du statut (fournisseur / admin)
 *
 * Le contrôleur ne contient pas de SQL.
 * Toute la logique d’accès aux données est dans Order.php.
 */
class OrderController
{
    private PDO $pdo;
    private Order $orderModel;
    private Product $productModel;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->orderModel   = new Order($this->pdo);
        $this->productModel = new Product($this->pdo);
    }

    /* ======================================================
       CRÉATION COMMANDE (CLIENT)
       ====================================================== */

    /**
     * Créer une commande à partir d’un panier simple
     *
     * Structure attendue :
     * $_POST['items'] = [
     *   ['product_id' => 1, 'quantity' => 2],
     *   ['product_id' => 3, 'quantity' => 1]
     * ]
     */
    public function create(): void
    {
        if (!UserController::check() || $_SESSION['user']['role'] !== 'client') {
            http_response_code(403);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $items = $_POST['items'] ?? [];

        if (empty($items)) {
            $_SESSION['error'] = 'Panier vide.';
            return;
        }

        $total = 0;

        // Calcul du total à partir des produits
        foreach ($items as $item) {
            if (
                !Validator::id($item['product_id'] ?? null) ||
                !Validator::id($item['quantity'] ?? null)
            ) {
                continue;
            }

            $product = $this->productModel->getById(
                (int) $item['product_id']
            );

            if (!$product) {
                continue;
            }

            $total += $product['prix'] * (int) $item['quantity'];
        }

        if ($total <= 0) {
            $_SESSION['error'] = 'Commande invalide.';
            return;
        }

        // Création de la commande
        $orderId = $this->orderModel->create(
            $_SESSION['user']['id'],
            $total
        );

        if (!$orderId) {
            $_SESSION['error'] = 'Erreur lors de la création de la commande.';
            return;
        }

        // Ajout des produits à la commande
        foreach ($items as $item) {
            $product = $this->productModel->getById(
                (int) $item['product_id']
            );

            if ($product) {
                $this->orderModel->addItem(
                    $orderId,
                    $product['id'],
                    (int) $item['quantity'],
                    $product['prix']
                );
            }
        }

        $_SESSION['success'] = 'Commande créée avec succès.';
    }

    /* ======================================================
       COMMANDES CLIENT
       ====================================================== */

    /**
     * Récupérer les commandes du client connecté
     */
    public function myOrders(): array
    {
        if (!UserController::check() || $_SESSION['user']['role'] !== 'client') {
            return [];
        }

        return $this->orderModel->getByClient(
            $_SESSION['user']['id']
        );
    }

    /* ======================================================
       COMMANDES FOURNISSEUR
       ====================================================== */

    /**
     * Récupérer les commandes liées aux produits du fournisseur
     *
     * Cette méthode s’appuie sur le modèle Supplier
     * (via jointures côté SQL).
     */
    public function supplierOrders(): array
    {
        if (!UserController::check() || $_SESSION['user']['role'] !== 'fournisseur') {
            return [];
        }

        // Cette logique est généralement déléguée au modèle Supplier
        // Ici, on suppose que Supplier::getOrders() existe
        $supplierModel = new Supplier($this->pdo);

        return $supplierModel->getOrders(
            $_SESSION['user']['id']
        );
    }

    /* ======================================================
       MISE À JOUR STATUT
       ====================================================== */

    /**
     * Mettre à jour le statut d’une commande
     * (fournisseur ou admin)
     */
    public function updateStatus(int $orderId, string $status): void
    {
        if (!UserController::check()) {
            return;
        }

        if (!Validator::id($orderId)) {
            return;
        }

        $allowedStatuses = ['en_attente', 'en_cours', 'livree', 'annulee'];

        if (!in_array($status, $allowedStatuses, true)) {
            return;
        }

        $this->orderModel->updateStatus($orderId, $status);
    }
}
