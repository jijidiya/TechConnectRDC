<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Models/Fournisseur.php';
require_once __DIR__ . '/../Models/Message.php';
require_once __DIR__ . '/UserController.php';

/**
 * FournisseurController
 *
 * Gère les actions liées au fournisseur connecté :
 *  - profil
 *  - produits
 *  - commandes
 *  - messages
 */

class SupplierController
{
    private PDO $pdo;
    private Supplier $supplierModel;
    private Message $messageModel;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->supplierModel = new Supplier($this->pdo);
        $this->messageModel     = new Message($this->pdo);
    }

    /* ======================================================
       VÉRIFICATION FOURNISSEUR
       ====================================================== */

    private function checkFournisseur(): bool
    {
        if (!UserController::check()) {
            header('Location: login.php');
            exit;
        }

        if ($_SESSION['user']['role'] !== 'fournisseur') {
            http_response_code(403);
            echo "Accès refusé.";
            exit;
        }

        return true;
    }

    /* ======================================================
       PROFIL FOURNISSEUR
       ====================================================== */

    public function profile(): ?array
    {
        $this->checkFournisseur();

        return $this->supplierModel->getByUserId(
            $_SESSION['user']['id']
        );
    }

    /* ======================================================
       PRODUITS FOURNISSEUR
       ====================================================== */

    public function products(): array
    {
        $this->checkFournisseur();

        return $this->supplierModel->getProducts(
            $_SESSION['user']['id']
        );
    }

    /* ======================================================
       COMMANDES FOURNISSEUR
       ====================================================== */

    public function orders(): array
    {
        $this->checkFournisseur();

        return $this->supplierModel->getOrders(
            $_SESSION['user']['id']
        );
    }

    /* ======================================================
       MESSAGES / CHAT
       ====================================================== */

    public function conversations(): array
    {
        $this->checkFournisseur();

        return $this->supplierModel->getConversations(
            $_SESSION['user']['id']
        );
    }

    public function conversation(int $otherUserId): array
    {
        $this->checkFournisseur();

        if (!Validator::id($otherUserId)) {
            return [];
        }

        return $this->messageModel->getConversation(
            $_SESSION['user']['id'],
            $otherUserId
        );
    }
}
