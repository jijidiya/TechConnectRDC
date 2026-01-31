<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Upload.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Supplier.php';
require_once __DIR__ . '/UserController.php';

/**
 * ProductController
 *
 * Gère toute la logique liée aux produits :
 *  - création (fournisseur)
 *  - modification
 *  - suppression
 *  - listing public
 *  - produit populaire
 *  - recherche
 */

class ProductController
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @var Product
     */
    private Product $productModel;

    /**
     * @var Supplier
     */
    private Supplier $supplierModel;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->productModel  = new Product($this->pdo);
        $this->supplierModel = new Supplier($this->pdo);
    }

    /* =====================================================
       AJOUT PRODUIT (FOURNISSEUR)
       ===================================================== */

    public function store(): void
    {
        if (!UserController::check() || !UserController::isRole('fournisseur')) {
            header('Location: /index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userId = $_SESSION['user']['id'];
        $supplier = $this->supplierModel->getByUserId($userId);

        if (!$supplier || $supplier['status'] !== 'active') {
            $_SESSION['error'] = "Compte fournisseur non actif.";
            return;
        }

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = (float) ($_POST['price'] ?? 0);
        $quantity    = (int) ($_POST['quantity'] ?? 0);
        $categoryId  = $_POST['category_id'] ?? null;

        if ($title === '' || $price <= 0) {
            $_SESSION['error'] = "Titre et prix requis.";
            return;
        }

        $slug = $this->productModel->generateSlug($title);

        // Upload image (1 image pour l’instant)
        $upload = new Upload();
        $images = null;

        if (!empty($_FILES['image']['name'])) {
            $imageName = $upload->image(
                $_FILES['image'],
                __DIR__ . '/../../uploads/products'
            );

            if ($imageName) {
                $images = json_encode([$imageName]);
            }
        }

        $created = $this->productModel->create([
            'fournisseur_id' => $supplier['id'],   
            'category_id'    => $categoryId,
            'title'          => $title,
            'slug'           => $slug,
            'description'    => $description,
            'price'          => $price,
            'quantity'       => $quantity,
            'images'         => $images,
            'status'         => 'draft'
        ]);

        if (!$created) {
            $_SESSION['error'] = "Erreur lors de l’ajout du produit.";
            return;
        }

        $_SESSION['success'] = "Produit ajouté avec succès.";
        header('Location: /index.php?page=supplier-dashboard');
        exit;
    }

    /* =====================================================
       MODIFICATION PRODUIT
       ===================================================== */

    public function update(int $id): void
    {
        if (!UserController::check() || !UserController::isRole('fournisseur')) {
            header('Location: /index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $product = $this->productModel->getById($id);
        if (!$product) {
            $_SESSION['error'] = "Produit introuvable.";
            return;
        }

        $supplier = $this->supplierModel->getByUserId($_SESSION['user']['id']);

        // Sécurité : le produit doit appartenir au fournisseur
        if ($product['fournisseur_id'] !== $supplier['id']) {
            http_response_code(403);
            exit('Accès refusé');
        }

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = (float) ($_POST['price'] ?? 0);
        $quantity    = (int) ($_POST['quantity'] ?? 0);
        $categoryId  = $_POST['category_id'] ?? null;

        $slug   = $this->productModel->generateSlug($title);
        $images = $product['images'];

        if (!empty($_FILES['image']['name'])) {
            $upload   = new Upload();
            $newImage = $upload->image(
                $_FILES['image'],
                __DIR__ . '/../../uploads/products'
            );

            if ($newImage) {
                $images = json_encode([$newImage]);
            }
        }

        $updated = $this->productModel->update($id, [
            'category_id' => $categoryId,
            'title'       => $title,
            'slug'        => $slug,
            'description' => $description,
            'price'       => $price,
            'quantity'    => $quantity,
            'images'      => $images,
            'status'      => $product['status']
        ]);

        if (!$updated) {
            $_SESSION['error'] = "Erreur lors de la mise à jour.";
            return;
        }

        $_SESSION['success'] = "Produit modifié avec succès.";
        header('Location: /index.php?page=supplier-dashboard');
        exit;
    }

    /* =====================================================
       SUPPRESSION PRODUIT
       ===================================================== */

    public function destroy(int $id): void
    {
        if (!UserController::check() || !UserController::isRole('fournisseur')) {
            header('Location: /index.php?page=login');
            exit;
        }

        $product  = $this->productModel->getById($id);
        $supplier = $this->supplierModel->getByUserId($_SESSION['user']['id']);

        if (!$product || $product['fournisseur_id'] !== $supplier['id']) {
            http_response_code(403);
            exit('Accès refusé');
        }

        $this->productModel->delete($id);

        $_SESSION['success'] = "Produit supprimé.";
        header('Location: /index.php?page=supplier-dashboard');
        exit;
    }

    /* =====================================================
       FRONT-END (LECTURE SEULE)
       ===================================================== */

    public function index(): array
    {
        return $this->productModel->getAll();
    }

    public function show(string $slug): ?array
    {
        return $this->productModel->getBySlug($slug);
    }

    public function popular(): array
    {
        return $this->productModel->getPopular();
    }

    public function search(): array
    {
        $query = trim($_GET['q'] ?? '');

        if ($query === '') {
            return [];
        }

        return $this->productModel->search($query);
    }
}
