<?php

/**
 * Class Product
 *
 * Gère toute la logique liée aux produits :
 *  - création
 *  - mise à jour
 *  - suppression
 *  - récupération (liste, détail, catégorie)
 *  - recherche
 *
 * Table concernée : `produits`
 */
class Product
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * Constructeur
     *
     * @param PDO $pdo Connexion PDO injectée
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       CREATE
       ===================================================== */

    /**
     * Ajouter un produit
     *
     * @param array $data
     *  - fournisseur_id
     *  - category_id (nullable)
     *  - title
     *  - slug
     *  - description
     *  - price
     *  - quantity
     *  - images (JSON string)
     *
     * @return bool
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO produits (
                fournisseur_id, category_id, title, slug,
                description, price, quantity, images, status
            ) VALUES (
                :fournisseur_id, :category_id, :title, :slug,
                :description, :price, :quantity, :images, :status
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':fournisseur_id' => $data['fournisseur_id'],
            ':category_id'    => $data['category_id'] ?? null,
            ':title'          => $data['title'],
            ':slug'           => $data['slug'],
            ':description'    => $data['description'] ?? null,
            ':price'          => $data['price'],
            ':quantity'       => $data['quantity'] ?? 0,
            ':images'         => $data['images'] ?? null,
            ':status'         => $data['status'] ?? 'draft',
        ]);
    }

    /* =====================================================
       READ
       ===================================================== */

    /**
     * Récupérer un produit par ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM produits WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);

        $product = $stmt->fetch();
        return $product ?: null;
    }

    /**
     * Récupérer un produit par slug
     */
    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM produits WHERE slug = ? AND status = 'published' LIMIT 1"
        );
        $stmt->execute([$slug]);

        $product = $stmt->fetch();
        return $product ?: null;
    }

    /**
     * Récupérer tous les produits publiés
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM produits WHERE status = 'published' ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Produits d’un fournisseur
     */
    public function getBySupplier(int $supplierId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, title, description, price, images
            FROM produits
            WHERE fournisseur_id = :fid
            AND status = 'published'
            ORDER BY created_at DESC"
        );

        $stmt->execute([':fid' => $supplierId]);

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$p) {
            $p['images'] = $p['images']
                ? json_decode($p['images'], true)
                : [];
        }

        return $products;
    }


    /**
     * Produits par catégorie
     */
    public function getByCategory(int $categoryId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM produits 
             WHERE category_id = ? AND status = 'published'
             ORDER BY created_at DESC"
        );
        $stmt->execute([$categoryId]);

        return $stmt->fetchAll();
    }

    /* =====================================================
       UPDATE
       ===================================================== */

    /**
     * Mettre à jour un produit
     */
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE produits SET
                category_id = :category_id,
                title = :title,
                slug = :slug,
                description = :description,
                price = :price,
                quantity = :quantity,
                images = :images,
                status = :status
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':category_id' => $data['category_id'] ?? null,
            ':title'       => $data['title'],
            ':slug'        => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':price'       => $data['price'],
            ':quantity'    => $data['quantity'],
            ':images'      => $data['images'] ?? null,
            ':status'      => $data['status'],
            ':id'          => $id,
        ]);
    }

    /* =====================================================
       DELETE
       ===================================================== */

    /**
     * Supprimer un produit
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM produits WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    /* =====================================================
       SEARCH
       ===================================================== */

    /**
     * Recherche de produits par mot-clé
     */
    public function search(string $query): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM produits
             WHERE status = 'published'
             AND (title LIKE :q OR description LIKE :q)
             ORDER BY created_at DESC"
        );

        $stmt->execute([
            ':q' => '%' . $query . '%'
        ]);

        return $stmt->fetchAll();
    }

    /* =====================================================
       UTILITAIRES
       ===================================================== */

    /**
     * Génère un slug à partir d’un titre
     */
    public function generateSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Renvoie les produits populaires en JSON
     */
    public function getPopular(int $limit = 6): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, title, description, price, images
             FROM produits
             WHERE status = 'published'
             ORDER BY created_at DESC
             LIMIT :limit"
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Décodage images JSON
        foreach ($products as &$p) {
            $p['images'] = $p['images']
                ? json_decode($p['images'], true)
                : [];
        }

        return $products;
    }
}
