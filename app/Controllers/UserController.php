<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Fournisseur.php';

/**
 * UserController
 *
 * Gère :
 *  - inscription
 *  - connexion
 *  - déconnexion
 *  - session utilisateur
 */

class UserController
{
    private PDO $pdo;
    private User $userModel;
    private Fournisseur $fournisseurModel;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();

        $this->userModel        = new User($this->pdo);
        $this->fournisseurModel = new Fournisseur($this->pdo);
    }

    /* =====================================================
       INSCRIPTION
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
        $role     = $_POST['role'] ?? 'client';

        /* ===== VALIDATION ===== */

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
            $_SESSION['error'] = "Mot de passe trop court.";
            header('Location: register.php');
            exit;
        }

        if ($this->userModel->emailExists($email)) {
            $_SESSION['error'] = "Cet email existe déjà.";
            header('Location: register.php');
            exit;
        }

        /* ===== CRÉATION ===== */

        $created = $this->userModel->create([
            'nom'           => $nom,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $role
        ]);

        if (!$created) {
            $_SESSION['error'] = "Erreur lors de l’inscription.";
            header('Location: register.php');
            exit;
        }

        // Si fournisseur, créer le profil fournisseur
        if ($role === 'fournisseur') {
            $this->fournisseurModel->create([
                'user_id' => $this->pdo->lastInsertId()
            ]);
        }

        $_SESSION['success'] = "Compte créé avec succès.";
        header('Location: login.php');
        exit;
    }

    /* =====================================================
       CONNEXION
       ===================================================== */

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: login.php');
            exit;
        }

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!Validator::email($email) || !Validator::required($password)) {
            $_SESSION['error'] = "Identifiants invalides.";
            header('Location: login.php');
            exit;
        }

        $user = $this->userModel->verify($email, $password);

        if (!$user) {
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            header('Location: login.php');
            exit;
        }

        /* ===== SESSION ===== */

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'nom'   => $user['nom'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        /* ===== REDIRECTION ===== */

        if ($user['role'] === 'fournisseur') {
            header('Location: dashboard-fournisseur.php');
        } else {
            header('Location: index.php');
        }
        exit;
    }

    /* =====================================================
       DÉCONNEXION
       ===================================================== */

    public function logout(): void
    {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    /* =====================================================
       UTILITAIRES
       ===================================================== */

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function isRole(string $role): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === $role;
    }
}
