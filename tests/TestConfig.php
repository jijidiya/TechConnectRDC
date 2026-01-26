<?php
declare(strict_types=1);

// Active l'affichage de toutes les erreurs PHP
// Très important en environnement de test
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Indique que l'application est en mode TEST
// Peut être utilisé plus tard pour charger
// des configs différentes (prod / dev / test)
define('APP_ENV', 'test');

//=============================================
//  SESSION
//=============================================

// Démarre la session si elle n'est pas déjà active
// Utile pour tester les parties liées à l'authentification
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//=============================================
//  CONFIGURATION BASE DES DONNÉES (TEST)
//=============================================

// Configuration spécifique à la base de données de test
// On ne touche jamais à la base de production ici
$config = [
        'host' => 'localhost',
        'db'   => 'techconnect_test',
        'user' => 'root',
        'pass' => ''
    ]
;

//=============================================
//  CHARGEMENT DES DEPENDANCES
//=============================================

// Chargement manuel des classes nécessaires aux tests
// (en attendant ou en complément de l'autoloader)
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Validator.php';

require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Client.php';
require_once __DIR__ . '/../app/Models/Supplier.php';
require_once __DIR__ . '/../app/Models/Message.php';
require_once __DIR__ . '/../app/Models/Product.php';

//=============================================
//  CONNEXION À LA BASE DE DONNÉES
//=============================================

// Création de la connexion PDO via la classe Database
// Toutes les opérations de test utiliseront cette connexion
$database = new Database($config);
$pdo = $database->getConnection();

//=============================================
//  ASSERTIONS SIMPLES POUR LES TESTS
//=============================================

// Vérifie qu'une condition est vraie
// Sinon, lève une exception et arrête le test
function assertTrue(bool $condition, string $message = ''): void
{
    if (!$condition) {
        throw new Exception("Assertion failed: " . $message);
    }
}

// Compare deux valeurs
// Utilisé pour vérifier que le résultat obtenu
// correspond exactement à ce qui est attendu
function assertEquals($expected, $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        throw new Exception(
            "Assertion failed: Expected " 
            . var_export($expected, true) 
            . ", got " 
            . var_export($actual, true) 
            . ". " . $message
        );
    }
}
