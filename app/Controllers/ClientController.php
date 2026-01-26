<?php

require_once __DIR__ . '/../Core/Database.php' ;
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/../Models/Message.php';
require_once __DIR__ . '/UserController.php';

/**
 * ClientController
 * 
 * Gère les actions liées au client connecté : 
 *  - profil
 *  - commandes 
 *  - messages
 */

class ClientController
{
    private PDO $pdo;
    private Client $clientModel;
    private Message $messageModel;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->clientModel = new Client($this->pdo);
        $this->messageModel = new Message($this->pdo);
    }

    /* ======================================================
        PROFIL DU CLIENT
       ======================================================*/

    /**
     * Afficher le profil du client connecté
     * 
     * @return array|null
     */
    public function profile(): ?array
    {
        if (!UserController::check()) {
            header('Location : login.php');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        return $this->clientModel->getById($userId);
    }

    /*  ===============================================
        COMMANDES CLIENT
        ===============================================*/


    /**
     * Récupérer les commandes du client connecté
     * 
     * @return array
     */
    public function orders(): array
    {
        if (!UserController::check()){
            return [];
        }
        
        return $this->clientModel->getOrders(
            $_SESSION['user']['id']
        );
    }

    /*  ===============================================
        MESSAGES / CHAT
        ===============================================*/

    /**
     * Liste des conversations du client 
     * 
     * @return array
     */
    public function conversations(): array
    {
        if (!UserController::check()){
            return [];
        }

        return $this->clientModel->getConversations(
            $_SESSION['user']['id']
        );
    }

    /**
     * Conversation avec un utilisateur donné
     * 
     * @param int $otherUserId
     * @return array
     */
    public function conversation(int $otherUserId): array
    {
        if (!UserController::check()){
            return [];
        }

         if (!Validator::id($otherUserId)) {
            return [];
        }
        return $this->messageModel->getConversation(
            $_SESSION['user']['id'],
            $otherUserId
        );
    }
}
