<?php

declare(strict_types=1);

$page = $_GET['page'] ?? 'home';

switch ($page) {

    /* =====================
       PAGES PUBLIQUES
       ===================== */

    case 'home':
        require __DIR__ . '/../app/Views/home.php';
        break;

    case 'products':
        require_once __DIR__ . '/../app/Controllers/ProductController.php';

        $controller = new ProductController();

        // ICI on charge les produits
        $products = $controller->index();

        // PUIS on affiche la vue
        require __DIR__ . '/../app/Views/catalog/index.php';
        
        break;

    case 'product':
        require_once __DIR__ . '/../app/Controllers/ProductController.php';


        $controller = new ProductController();
        $product = $controller->showById((int) $_GET['id']);



        require __DIR__ . '/../app/Views/catalog/product.php';
        break;
 

    case 'fournisseurs':
        require __DIR__ . '/../app/Views/catalog/supplier.php';
        break;

    case 'supplier-products':
        require __DIR__ . '/../app/Views/catalog/supplier.php';
        break;

    case 'cart':
        require __DIR__ . '/../app/Views/cart/cart.php';
        break;

    /* =====================
       AUTHENTIFICATION
       ===================== */

    case 'login':
        require __DIR__ . '/../app/Views/auth/login.php';
        break;

    case 'register':
        require __DIR__ . '/../app/Views/auth/register.php';
        break;

    case 'logout':
        require __DIR__ . '/../app/Controllers/UserController.php';
        (new UserController())->logout();
        break;

    /* =====================
       ESPACE CLIENT
       ===================== */

    case 'client-dashboard':
        require __DIR__ . '/../app/Views/client/dashboard.php';
        break;

    case 'client-orders':
        require __DIR__ . '/../app/Views/client/orders.php';
        break;

    case 'client-messages':
        require __DIR__ . '/../app/Views/client/messages.php';
        break;

    /* =====================
       ESPACE FOURNISSEUR
       ===================== */

    case 'supplier-dashboard':
        require __DIR__ . '/../app/Views/supplier/dashboard.php';
        break;

    case 'supplier-products-manage':
        require __DIR__ . '/../app/Views/supplier/products.php';
        break;

    case 'supplier-messages':
        require __DIR__ . '/../app/Views/supplier/messages.php';
        break;

    /* =====================
       PAGES STATIQUES
       ===================== */

    case 'about':
        require __DIR__ . '/../app/Views/static/about.php';
        break;
        break;

    case 'how-it-works':
        require __DIR__ . '/../app/Views/static/how-it-works.php';
        break;

    case 'contact':
        require __DIR__ . '/../app/Views/static/contact.php';
        break;

    case 'faq':
        require __DIR__ . '/../app/Views/static/qa.php';
        break;

    case 'terms':
        require __DIR__ . '/../app/Views/static/terms.php';
        break;

    case 'privacy':
        require __DIR__ . '/../app/Views/static/privacy.php';
        break;

    

    default:
        http_response_code(404);
        require __DIR__ . '/../app/Views/home.php';
        break;
}
