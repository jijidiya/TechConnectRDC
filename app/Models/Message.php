<?php

/**
 * Message Model (Chat)
 *
 * Représente la table `messages` pour un système de chat
 *
 * Responsabilités :
 *  - Envoyer un message
 *  - Récupérer une conversation entre deux utilisateurs
 *  - Lister les messages reçus (inbox)
 *  - Marquer un message comme lu
 *
 * 
 */

class Message
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

    /* =====================================================
       ENVOI MESSAGE (CHAT)
       ===================================================== */

    /**
     * Envoyer un message de chat
     *
     * @param int    $senderId
     * @param int    $receiverId
     * @param string $body
     *
     * @return bool
     */
    public function send(
        int $senderId,
        int $receiverId,
        string $body
    ): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO messages (sender_id, receiver_id, body)
             VALUES (?, ?, ?)"
        );

        return $stmt->execute([
            $senderId,
            $receiverId,
            $body
        ]);
    }

    /* =====================================================
       CONVERSATION ENTRE DEUX UTILISATEURS
       ===================================================== */

    /**
     * Récupérer une conversation (ordre chronologique)
     *
     * @param int $userA
     * @param int $userB
     *
     * @return array
     */
    public function getConversation(int $userA, int $userB): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM messages
             WHERE 
                (sender_id = ? AND receiver_id = ?)
             OR (sender_id = ? AND receiver_id = ?)
             ORDER BY created_at ASC"
        );

        $stmt->execute([
            $userA,
            $userB,
            $userB,
            $userA
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       INBOX UTILISATEUR
       ===================================================== */

    /**
     * Récupérer les messages reçus par un utilisateur
     *
     * @param int $userId
     *
     * @return array
     */
    public function getInbox(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT 
                m.*,
                u.nom AS sender_name
             FROM messages m
             INNER JOIN users u ON m.sender_id = u.id
             WHERE m.receiver_id = ?
             ORDER BY m.created_at DESC"
        );

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       MISE À JOUR ÉTAT MESSAGE
       ===================================================== */

    /**
     * Marquer un message comme lu
     *
     * @param int $messageId
     *
     * @return void
     */
    public function markAsRead(int $messageId): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE messages
             SET is_read = 1
             WHERE id = ?"
        );

        $stmt->execute([$messageId]);
    }
}
