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
            return;
        }

        // ===============================
        // Récupération des données
        // ===============================
        $nom              = trim($_POST['nom'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $telephone        = trim($_POST['telephone'] ?? '');
        $password         = $_POST['password'] ?? '';
        $confirmPassword  = $_POST['confirm_password'] ?? '';
        $role             = $_POST['role'] ?? '';

        // ===============================
        // Vérifications de base
        // ===============================
        if (
            empty($nom) ||
            empty($email) ||
            empty($password) ||
            empty($confirmPassword) ||
            empty($role)
        ) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires.';
            return;
        }

        // ===============================
        // Validation email
        // ===============================
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Adresse e-mail invalide.';
            return;
        }

        // ===============================
        // Validation du rôle
        // ===============================
        $allowedRoles = ['client', 'fournisseur'];

        if (!in_array($role, $allowedRoles, true)) {
            $_SESSION['error'] = 'Type de compte invalide.';
            return;
        }

        // ===============================
        // Vérification mot de passe
        // ===============================
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères.';
            return;
        }

        // ===============================
        // Email déjà utilisé ?
        // ===============================
        if ($this->userModel->emailExists($email)) {
            $_SESSION['error'] = 'Cet email est déjà utilisé.';
            return;
        }

        // ===============================
        // Création utilisateur
        // ===============================
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->userModel->create([
            'nom'           => $nom,
            'email'         => $email,
            'telephone'     => $telephone,
            'password_hash' => $hashedPassword,
            'role'          => $role
        ]);

        if (!$userId) {
            $_SESSION['error'] = 'Erreur lors de la création du compte.';
            return;
        }

        // ===============================
        // Création profil fournisseur
        // ===============================
        if ($role === 'fournisseur') {
            $this->fournisseurModel->create([
                'user_id' => $userId
            ]);
        }

        // ===============================
        // Succès
        // ===============================
        $_SESSION['success'] = 'Compte créé avec succès. Vous pouvez vous connecter.';
        header('Location: /index.php?page=login');
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
