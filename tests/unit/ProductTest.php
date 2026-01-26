<?php
declare(strict_types=1);

require_once __DIR__ . '/../TestConfig.php';
require_once __DIR__ . '/../../app/Models/Product.php';

class ProductTest
{
    private PDO $pdo;
    private Product $productModel;

    private int $userId;
    private int $fournisseurId;
    private int $productId;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->productModel = new Product($this->pdo);
    }

    /* ======================================================
       SETUP GLOBAL : TRANSACTION
       ====================================================== */
    private function setUp(): void
    {
        $this->pdo->beginTransaction();

        // Création d’un utilisateur fournisseur
        $stmt = $this->pdo->prepare("
            INSERT INTO users (role, nom, email, password_hash)
            VALUES ('fournisseur', 'Fournisseur Test', ?, ?)
        ");

        $email = 'fournisseur_' . uniqid('', true) . '@test.com';
        $stmt->execute([
            $email,
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        $this->userId = (int) $this->pdo->lastInsertId();

        // Création du fournisseur lié à l’utilisateur
        $stmt = $this->pdo->prepare("
            INSERT INTO fournisseurs (user_id, description, statut)
            VALUES (?, 'Fournisseur test', 'actif')
        ");

        $stmt->execute([$this->userId]);
        $this->fournisseurId = (int) $this->pdo->lastInsertId();
    }

    /* ======================================================
       TEARDOWN : ANNULATION DES MODIFICATIONS
       ====================================================== */
    private function tearDown(): void
    {
        $this->pdo->rollBack();
    }

    /* ======================================================
       TESTS
       ====================================================== */

    private function testCreateProduct(): void
    {
        $title = 'Produit Test';

        $created = $this->productModel->create([
            'fournisseur_id' => $this->fournisseurId,
            'category_id'    => null,
            'title'          => $title,
            'slug'           => $this->productModel->generateSlug($title),
            'description'    => 'Description produit test',
            'price'          => 100,
            'quantity'       => 10,
            'status'         => 'published'
        ]);

        assertTrue($created === true, 'create crée un produit valide');

        $this->productId = (int) $this->pdo->lastInsertId();
    }

    private function testGetById(): void
    {
        $product = $this->productModel->getById($this->productId);

        assertTrue(
            is_array($product),
            'getById retourne un produit existant'
        );
    }

    private function testGetAll(): void
    {
        $products = $this->productModel->getAll();

        assertTrue(
            is_array($products),
            'getAll retourne une liste de produits'
        );
    }

    /* ======================================================
       RUNNER
       ====================================================== */
    public function run(): void
    {
        try {
            $this->setUp();

            $this->testCreateProduct();
            echo "testCreateProduct() OK\n";

            $this->testGetById();
            echo "testGetById() OK\n";

            $this->testGetAll();
            echo "testGetAll() OK\n";

        } finally {
            $this->tearDown();
        }
    }
}

$test = new ProductTest($pdo);
$test->run();
