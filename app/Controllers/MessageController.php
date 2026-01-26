<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Models/Message.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/UserController.php';

/**
 * MessageController
 *
 * Gestion du chat client ↔ fournisseur
 */

class MessageController
{
    /**
     * @var Message
     */
    private Message $messageModel;

    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->messageModel = new Message($this->pdo);
    }

    /* =====================================================
       ENVOI MESSAGE
       ===================================================== */

    public function send(): void
    {
        if (!UserController::check()) {
            header('Location: login.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: messages.php');
            exit;
        }

        $senderId   = $_SESSION['user']['id'];
        $receiverId = $_POST['receiver_id'] ?? null;
        $body       = $_POST['message'] ?? '';

        /* ========= VALIDATION ========= */

        if (!Validator::id($receiverId)) {
            $_SESSION['error'] = "Destinataire invalide.";
            header('Location: messages.php');
            exit;
        }

        if (!Validator::message($body)) {
            $_SESSION['error'] = "Message invalide ou trop long.";
            header('Location: chat.php?user=' . (int)$receiverId);
            exit;
        }

        /* ========= ENVOI ========= */

        $sent = $this->messageModel->send(
            $senderId,
            (int)$receiverId,
            trim($body)
        );

        if (!$sent) {
            $_SESSION['error'] = "Erreur lors de l’envoi du message.";
        } else {
            $_SESSION['success'] = "Message envoyé.";
        }

        header('Location: chat.php?user=' . (int)$receiverId);
        exit;
    }

    /* =====================================================
       CONVERSATION
       ===================================================== */

    public function conversation(int $userId): array
    {
        if (!UserController::check()) {
            return [];
        }

        return $this->messageModel->getConversation(
            $_SESSION['user']['id'],
            $userId
        );
    }

    /* =====================================================
       INBOX
       ===================================================== */

    public function inbox(): array
    {
        if (!UserController::check()) {
            return [];
        }

        return $this->messageModel->getInbox(
            $_SESSION['user']['id']
        );
    }

    /* =====================================================
       MISE À JOUR MESSAGE
       ===================================================== */

    public function markAsRead(int $messageId): void
    {
        if (!UserController::check()) {
            return;
        }

        if (!Validator::id($messageId)) {
            return;
        }

        $this->messageModel->markAsRead($messageId);
    }
}
