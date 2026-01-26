<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Upload.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Fournisseur.php';
require_once __DIR__ . '/UserController.php';

/**
 * ProductController
 *
 * Gestion des produits :
 *  - ajout produit (fournisseur)
 *  - modification
 *  - suppression
 *  - listing produits
 *  - détail produit
 *  - recherche
 *
 * Dépendances :
 *  - Database
 *  - Upload
 *  - Product
 *  - Fournisseur
 *  - UserController (session)
 */

/* =====================================================
   CONTROLLER
   ===================================================== */

class ProductController
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var Product
     */
    private $productModel;

    /**
     * @var Fournisseur
     */
    private $fournisseurModel;

    /**
     * Constructeur
     */
    public function __construct()
    {
        // Connexion DB
        $database = new Database();
        $this->pdo = $database->getConnection();

        // Models
        $this->productModel = new Product($this->pdo);
        $this->fournisseurModel = new Fournisseur($this->pdo);
    }

    /* =====================================================
       AJOUT PRODUIT
       ===================================================== */

    /**
     * Ajouter un produit (fournisseur uniquement)
     */
    public function store()
    {
        if (!UserController::check() || !UserController::isRole('fournisseur')) {
            header('Location: login.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userId = $_SESSION['user']['id'];
        $fournisseur = $this->fournisseurModel->getByUserId($userId);

        if (!$fournisseur || $fournisseur['statut'] !== 'actif') {
            $_SESSION['error'] = "Compte fournisseur non actif.";
            return;
        }

        // Données
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = (float) ($_POST['price'] ?? 0);
        $quantity    = (int) ($_POST['quantity'] ?? 0);
        $categoryId  = $_POST['category_id'] ?? null;

        if (empty($title) || $price <= 0) {
            $_SESSION['error'] = "Titre et prix requis.";
            return;
        }

        // Slug
        $slug = $this->productModel->generateSlug($title);

        // Upload image
        $upload = new Upload();
        $imageName = null;

        if (!empty($_FILES['image']['name'])) {
            $imageName = $upload->image(
                $_FILES['image'],
                __DIR__ . '/../../uploads/produits'
            );
        }

        $images = $imageName ? json_encode([$imageName]) : null;

        $created = $this->productModel->create([
            'fournisseur_id' => $fournisseur['id'],
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
        header('Location: dashboard-fournisseur.php');
        exit;
    }

    /* =====================================================
       MODIFICATION PRODUIT
       ===================================================== */

    /**
     * Modifier un produit
     */
    public function update(int $id)
    {
        if (!UserController::check() || !UserController::isRole('fournisseur')) {
            header('Location: login.php');
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

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = (float) ($_POST['price'] ?? 0);
        $quantity    = (int) ($_POST['quantity'] ?? 0);
        $categoryId  = $_POST['category_id'] ?? null;

        $slug = $this->productModel->generateSlug($title);

        // Upload nouvelle image (optionnel)
        $images = $product['images'];

        if (!empty($_FILES['image']['name'])) {
            $upload = new Upload();
            $newImage = $upload->image(
                $_FILES['image'],
                __DIR__ . '/../../uploads/produits'
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
            'status'      => $product['status'],
        ]);

        if (!$updated) {
            $_SESSION['error'] = "Erreur lors de la mise à jour.";
            return;
        }

        $_SESSION['success'] = "Produit modifié.";
        header('Location: dashboard-fournisseur.php');
        exit;
    }

    /* =====================================================
       SUPPRESSION PRODUIT
       ===================================================== */

    /**
     * Supprimer un produit
     */
    public function destroy(int $id)
    {
        if (!UserController::check() || !UserController::isRole('fournisseur')) {
            header('Location: login.php');
            exit;
        }

        $this->productModel->delete($id);

        $_SESSION['success'] = "Produit supprimé.";
        header('Location: dashboard-fournisseur.php');
        exit;
    }

    /* =====================================================
       FRONT-END
       ===================================================== */

    /**
     * Liste des produits publiés
     */
    public function index(): array
    {
        return $this->productModel->getAll();
    }

    /**
     * Détail produit
     */
    public function show(string $slug): ?array
    {
        return $this->productModel->getBySlug($slug);
    }

    /**
     * Recherche produit
     */
    public function search(): array
    {
        $query = trim($_GET['q'] ?? '');

        if (empty($query)) {
            return [];
        }

        return $this->productModel->search($query);
    }
}
