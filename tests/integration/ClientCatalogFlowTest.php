<?php

require_once __DIR__ . '/../TestConfig.php';

/**
 * ClientCatalogFlowTest
 *
 * Test d’intégration : accès au catalogue côté client
 *
 * Règles métier testées :
 * 1. un client existe
 * 2. un fournisseur existe
 * 3. un produit publié est visible dans le catalogue
 * 4. un produit draft n’est PAS visible
 *
 * Ce test ne couvre PAS :
 * - panier
 * - commande
 * - paiement
 */
class ClientCatalogFlowTest
{
    private PDO $pdo;
    private Product $productModel;

    private int $clientId;
    private int $supplierUserId;
    private int $supplierId;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->productModel = new Product($this->pdo);
    }

    

    /**
     * Création d’un client de test
     */
    private function prepareClient(): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (role, nom, email, password_hash)
             VALUES ('client', 'Client Test', ?, ?)"
        );

        $stmt->execute([
            'client_' . uniqid() . '@test.com',
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        $this->clientId = (int) $this->pdo->lastInsertId();

        assertTrue($this->clientId > 0, 'Client créé');
    }

    /**
     * Création d’un fournisseur de test
     * (user + table fournisseurs)
     */
    private function prepareSupplier(): void
    {
        // utilisateur fournisseur
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (role, nom, email, password_hash)
             VALUES ('fournisseur', 'Fournisseur Test', ?, ?)"
        );

        $stmt->execute([
            'supplier_' . uniqid() . '@test.com',
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        $this->supplierUserId = (int) $this->pdo->lastInsertId();

        // entrée fournisseur
        $stmt = $this->pdo->prepare(
            "INSERT INTO fournisseurs (user_id, description, statut)
             VALUES (?, 'Fournisseur pour tests', 'actif')"
        );

        $stmt->execute([$this->supplierUserId]);

        $this->supplierId = (int) $this->pdo->lastInsertId();

        assertTrue($this->supplierId > 0, 'Fournisseur créé');
    }

    /**
     * Création des produits de test
     * - 1 publié (visible)
     * - 1 draft (invisible)
     */
    private function prepareProducts(): void
    {
        // Produit publié
        $published = $this->productModel->create(
            $this->fakeProduct([
                'title'  => 'Produit Catalogue Test',
                'status' => 'published'
            ])
        );

        if ($published !== true) {
            throw new RuntimeException('Échec création produit publié');
        }

        // Produit draft
        $draft = $this->productModel->create(
            $this->fakeProduct([
                'title'  => 'Produit Draft',
                'status' => 'draft'
            ])
        );

        if ($draft !== true) {
            throw new RuntimeException('Échec création produit draft');
        }
    }

    /**
     * Vérification du catalogue
     */
    private function checkCatalogAccess(): void
    {
        $products = $this->productModel->getAll();

        assertTrue(is_array($products), 'Le catalogue retourne un tableau');
        assertTrue(count($products) === 1, 'Seuls les produits publiés sont visibles');

        $product = $products[0];

        assertTrue($product['title'] === 'Produit Catalogue Test', 'Bon produit retourné');
        assertTrue($product['status'] === 'published', 'Produit publié');
        assertTrue((float)$product['price'] === 10.00, 'Prix correct');
    }

    /**
     * Générateur de produit de test
     */
    private function fakeProduct(array $overrides = []): array
    {
        $title = $overrides['title'] ?? 'Produit ' . uniqid();

        return array_merge([
            'fournisseur_id' => $this->supplierId,
            'category_id'    => null,
            'title'          => $title,
            'slug'           => $this->productModel->generateSlug($title),
            'description'    => 'Produit de test',
            'price'          => 10,
            'quantity'       => 5,
            'status'         => 'published'
        ], $overrides);
    }

    /**
     * Point d’entrée du test
     */
    public function run(): void
    {
        $this->pdo->beginTransaction();

        try {
            $this->prepareClient();
            echo "prepareClient OK\n";

            $this->prepareSupplier();
            echo "prepareSupplier OK\n";

            $this->prepareProducts();
            echo "prepareProducts OK\n";

            $this->checkCatalogAccess();
            echo "checkCatalogAccess OK\n";

            // Nettoyage automatique
            $this->pdo->rollBack();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}

/* ======================================================
   Exécution du test
   ====================================================== */

$test = new ClientCatalogFlowTest($pdo);
$test->run();
