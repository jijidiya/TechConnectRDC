<?php

require_once __DIR__ . '/../TestConfig.php';

/**
 * MessageFlowTest
 *
 * Test d’intégration du module de messagerie.
 *
 * Ce test vérifie :
 * - l’envoi de messages dans les deux sens
 * - la récupération complète d’une conversation
 *
 * L’ordre des messages n’est PAS testé volontairement.
 */
class MessageFlowTest
{
    private PDO $pdo;
    private Message $messageModel;

    private int $clientId;
    private int $supplierId;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->messageModel = new Message($this->pdo);
    }

    /**
     * Prépare deux utilisateurs de test en base :
     * - un client
     * - un fournisseur
     *
     * Les emails sont uniques grâce à uniqid()
     * Les mots de passe sont hashés comme en production.
     *
     * Cette méthode stocke les IDs générés dans :
     * - $this->clientId
     * - $this->supplierId
     */
    private function prepareUsers(): void
    {
        // Préparation de l'insertion du client
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (role, nom, email, password_hash)
            VALUES ('client', 'Client Test', ?, ?)"
        );

        // Exécution avec email unique et mot de passe hashé
        $stmt->execute([
            'client_' . uniqid() . '@test.com',
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        // Récupération de l'ID du client créé
        $this->clientId = (int) $this->pdo->lastInsertId();

        // Préparation de l'insertion du fournisseur
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (role, nom, email, password_hash)
            VALUES ('fournisseur', 'Fournisseur Test', ?, ?)"
        );

        // Exécution avec email unique et mot de passe hashé
        $stmt->execute([
            'supplier_' . uniqid() . '@test.com',
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        // Récupération de l'ID du fournisseur créé
        $this->supplierId = (int) $this->pdo->lastInsertId();

        // Vérification que les deux utilisateurs ont bien été créés
        assertTrue(
            $this->clientId > 0 && $this->supplierId > 0,
            'Utilisateurs créés'
        );
    }

    /**
     * Envoie deux messages de test :
     * - client -> fournisseur
     * - fournisseur -> client
     *
     * Cette méthode teste la méthode send() du modèle Message.
     */
    private function sendMessages(): void
    {
        // Message du client vers le fournisseur
        assertTrue(
            $this->messageModel->send(
                $this->clientId,
                $this->supplierId,
                'Bonjour, ceci est un message de test.'
            ) === true,
            'Message client -> fournisseur envoyé'
        );

        // Message du fournisseur vers le client
        assertTrue(
            $this->messageModel->send(
                $this->supplierId,
                $this->clientId,
                'Bonjour, message bien reçu.'
            ) === true,
            'Message fournisseur -> client envoyé'
        );
    }

    /**
     * Teste la récupération d'une conversation entre deux utilisateurs.
     *
     * Vérifie :
     * - que la conversation est un tableau
     * - qu'elle contient exactement 2 messages
     * - que les deux sens (client->fournisseur et inverse) existent
     * - que les messages sont non lus par défaut
     */
    private function testGetConversation(): void
    {
        // Récupération de la conversation
        $conversation = $this->messageModel->getConversation(
            $this->clientId,
            $this->supplierId
        );

        // Assertions de base
        assertTrue(is_array($conversation), 'Conversation retournée');
        assertTrue(count($conversation) === 2, 'Deux messages dans la conversation');

        $hasClientToSupplier = false;
        $hasSupplierToClient = false;

        // Analyse de chaque message
        foreach ($conversation as $message) {
            $senderId   = (int) $message['sender_id'];
            $receiverId = (int) $message['receiver_id'];

            // Détection du message client -> fournisseur
            if ($senderId === $this->clientId && $receiverId === $this->supplierId) {
                $hasClientToSupplier = true;
            }

            // Détection du message fournisseur -> client
            if ($senderId === $this->supplierId && $receiverId === $this->clientId) {
                $hasSupplierToClient = true;
            }

            // Vérification que les messages sont non lus par défaut
            assertTrue(
                (int) $message['is_read'] === 0,
                'Message non lu par défaut'
            );
        }

        // Vérification finale des deux sens de communication
        assertTrue($hasClientToSupplier, 'Message client → fournisseur présent');
        assertTrue($hasSupplierToClient, 'Message fournisseur → client présent');
    }

    /**
     * Point d'entrée du test.
     *
     * Le test est encapsulé dans une transaction SQL :
     * - beginTransaction()
     * - tous les tests
     * - rollBack() à la fin
     *
     * => Aucun effet réel sur la base de données.
     */
    public function run(): void
    {
        // Démarrage d'une transaction pour isoler les tests
        $this->pdo->beginTransaction();

        try {
            $this->prepareUsers();
            echo "prepareUsers OK\n";

            $this->sendMessages();
            echo "testSendMessages OK\n";

            $this->testGetConversation();
            echo "testGetConversation OK\n";

            // Annulation de toutes les insertions (base propre)
            $this->pdo->rollBack();

        } catch (Throwable $e) {
            // En cas d'erreur : rollback aussi
            $this->pdo->rollBack();
            throw $e;
        }
    }

}

/* ======================================================
   Exécution
   ====================================================== */

$test = new MessageFlowTest($pdo);
$test->run();
