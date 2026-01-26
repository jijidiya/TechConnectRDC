<?php

require_once __DIR__ . '/../TestConfig.php';

/**
 * SupplierProductFlowTest
 *
 * Test d’intégration basé sur un parcours fonctionnel (flow).
 *
 * Scénario testé :
 * 1. un fournisseur est créé
 * 2. le fournisseur ajoute un produit
 * 3. on vérifie que le produit est bien lié à ce fournisseur
 *
 * Ce test implique plusieurs composants :
 *  - base de données
 *  - modèle User (rôle fournisseur)
 *  - modèle Product
 */
class SupplierProductFlowTest
{
    private PDO $pdo;
    private User $userModel;
    private Product $productModel;

    private int $supplierId;
    private int $productId;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        // Modèles utilisés dans ce flow
        $this->userModel    = new User($this->pdo);
        $this->productModel = new Product($this->pdo);
    }

    /**
     * Point d’entrée du test
     * Les étapes sont exécutées dans l’ordre du parcours réel
     */
    public function run(): void
    {
        $this->prepareSupplier();
        $this->addProduct();
        $this->checkSupplierProducts();
    }

    /**
     * Création d’un fournisseur de test
     *
     * On crée directement l’utilisateur en base :
     * - pas de controller
     * - pas de logique HTTP
     */
    private function prepareSupplier(): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (role, nom, email, password_hash)
             VALUES ('fournisseur', 'Fournisseur Test', ?, ?)"
        );

        $stmt->execute([
            'supplier_test_' . time() . '@test.com',
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        $this->supplierId = (int) $this->pdo->lastInsertId();

        assertTrue(
            $this->supplierId > 0,
            'Fournisseur de test créé'
        );
    }

    /**
     * Ajout d’un produit par le fournisseur
     *
     * Cette étape simule l’action principale du fournisseur :
     * proposer un produit sur la plateforme.
     */
    private function addProduct(): void
    {
        $created = $this->productModel->create([
            'nom'            => 'Produit Test Fournisseur',
            'description'    => 'Produit ajouté par un fournisseur (test)',
            'prix'           => 100,
            'fournisseur_id' => $this->supplierId
        ]);

        assertTrue(
            $created === true,
            'Produit créé par le fournisseur'
        );

        $this->productId = (int) $this->pdo->lastInsertId();
    }

    /**
     * Vérification des produits du fournisseur
     *
     * On vérifie que :
     * - la récupération des produits fonctionne
     * - le produit créé est bien associé au fournisseur
     */
    private function checkSupplierProducts(): void
    {
        // Récupération directe via requête simple
        // (le modèle peut aussi exposer une méthode dédiée)
        $stmt = $this->pdo->prepare(
            "SELECT * FROM products WHERE fournisseur_id = ?"
        );
        $stmt->execute([$this->supplierId]);

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        assertTrue(
            is_array($products) && count($products) >= 1,
            'Le fournisseur possède au moins un produit'
        );
    }
}

/* ======================================================
   Exécution du test
   ====================================================== */

$test = new SupplierProductFlowTest($pdo);
$test->run();
