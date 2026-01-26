<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Models/User.php';

/**
 * AuthController
 *
 * Gestion de l’authentification :
 *  - login
 *  - logout
 *  - register
 */

class AuthController
{
    private PDO $pdo;
    private User $userModel;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->userModel = new User($this->pdo);
    }

    /* =====================================================
       LOGIN
       ===================================================== */

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: login.php');
            exit;
        }

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        /* ========= VALIDATION ========= */

        if (!Validator::email($email)) {
            $_SESSION['error'] = "Email invalide.";
            header('Location: login.php');
            exit;
        }

        if (!Validator::required($password)) {
            $_SESSION['error'] = "Mot de passe requis.";
            header('Location: login.php');
            exit;
        }

        /* ========= AUTHENTIFICATION ========= */

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Identifiants incorrects.";
            header('Location: login.php');
            exit;
        }

        /* ========= SESSION ========= */

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'nom'   => $user['nom'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        header('Location: index.php');
        exit;
    }

    /* =====================================================
       REGISTER
       ===================================================== */

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: register.php');
            exit;
        }

        $nom      = $_POST['nom'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        /* ========= VALIDATION ========= */

        if (!Validator::name($nom)) {
            $_SESSION['error'] = "Nom invalide.";
            header('Location: register.php');
            exit;
        }

        if (!Validator::email($email)) {
            $_SESSION['error'] = "Email invalide.";
            header('Location: register.php');
            exit;
        }

        if (!Validator::password($password)) {
            $_SESSION['error'] = "Mot de passe trop court (min 6 caractères).";
            header('Location: register.php');
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = "Email déjà utilisé.";
            header('Location: register.php');
            exit;
        }

        /* ========= CRÉATION ========= */

        $created = $this->userModel->create([
            'nom'      => $nom,
            'email'    => $email,
            'password' => $password,
            'role'     => 'client'
        ]);

        if (!$created) {
            $_SESSION['error'] = "Erreur lors de l’inscription.";
            header('Location: register.php');
            exit;
        }

        $_SESSION['success'] = "Compte créé avec succès. Connectez-vous.";
        header('Location: login.php');
        exit;
    }

    /* =====================================================
       LOGOUT
       ===================================================== */

    public function logout(): void
    {
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
