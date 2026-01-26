<?php

require_once __DIR__ . '/../TestConfig.php';

/**
 * ClientTest
 *
 * Tests unitaires du modèle Client.
 * Objectif :
 *  - vérifier l’accès aux données client
 *  - garantir que les méthodes principales retournent
 *    des résultats cohérents
 *
 * Ces tests supposent l’existence d’au moins
 * un utilisateur (users dans notre base de données) 
 * avec le rôle 'client' en base de test.
 */
class ClientTest
{
    private PDO $pdo;
    private Client $clientModel;
    private int $clientId;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->clientModel = new Client($this->pdo);
    }

    

    /**
     * Prépare un client de test pour les tests
     * (création minimale dans la table users)
     */
    private function prepareClient(): void
    {
        $email = 'client_test_' . time() . '@test.com';

        $stmt = $this->pdo->prepare(
            "INSERT INTO users (role, nom, email, password_hash)
             VALUES ('client', 'Client Test', ?, ?)"
        );

        $stmt->execute([
            $email,
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        $this->clientId = (int) $this->pdo->lastInsertId();

        assertTrue(
            $this->clientId > 0,
            'Client de test créé'
        );
    }

    /**
     * Vérifie la récupération d’un client par ID
     */
    private function testFindById(): void
    {
        $client = $this->clientModel->getById($this->clientId);

        assertTrue(
            is_array($client),
            'getById retourne un client existant'
        );
    }

    /**
     * Vérifie la récupération des conversations du client
     * Même sans messages, la méthode doit retourner au 
     * moins un tableau vide
     */
    private function testGetConversations(): void
    {
        $conversations = $this->clientModel->getConversations($this->clientId);

        assertTrue(
            is_array($conversations),
            'getConversations retourne un tableau'
        );
    }
    /**
     * Point d’entrée des tests
     */
    public function run(): void
    {
        $this->prepareClient();
        echo "prepareClient() OK\n";

        $this->testFindById();
        echo "testFindById() OK\n";

        $this->testGetConversations();
        echo "testGetConversations() OK\n";
    }
}

/* ======================================================
   Exécution du test
   ====================================================== */

$test = new ClientTest($pdo);
$test->run();
