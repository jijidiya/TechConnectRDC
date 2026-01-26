<?php

require_once __DIR__ . '/../TestConfig.php';

/**
 * ValidatorTest
 *
 * Tests unitaires de la classe Validator.
 * Objectif :
 *  - vérifier que les règles de validation fonctionnent correctement
 *  - garantir un comportement cohérent dans tous les contrôleurs
 */
class ValidatorTest
{

    /**
     * Vérifie qu’un email bien formé est accepté
     */
    private function testValidEmail(): void
    {
        assertTrue(
            Validator::email('test@example.com') === true,
            'email valide accepté'
        );
    }

    /**
     * Vérifie qu’un email mal formé est refusé
     */
    private function testInvalidEmail(): void
    {
        assertTrue(
            Validator::email('email_invalide') === false,
            'email invalide refusé'
        );
    }

    /**
     * Vérifie la règle minimale sur le mot de passe
     * (longueur minimale)
     */
    private function testPasswordValidation(): void
    {
        assertTrue(
            Validator::password('123456') === true,
            'mot de passe valide accepté'
        );

        assertTrue(
            Validator::password('123') === false,
            'mot de passe trop court refusé'
        );
    }

    /**
     * Vérifie la validation des messages de chat
     * - message non vide
     * - longueur correcte
     */
    private function testMessageValidation(): void
    {
        assertTrue(
            Validator::message('Bonjour, ceci est un message valide.') === true,
            'message valide accepté'
        );

        assertTrue(
            Validator::message('') === false,
            'message vide refusé'
        );
    }

    /**
     * Vérifie la validation des identifiants numériques
     * Utilisé pour les IDs utilisateur, produit, message, etc.
     */
    private function testIdValidation(): void
    {
        assertTrue(
            Validator::id(1) === true,
            'id valide accepté'
        );

        assertTrue(
            Validator::id(0) === false,
            'id nul refusé'
        );

        assertTrue(
            Validator::id(-5) === false,
            'id négatif refusé'
        );
    }


    /**
     * Point d’entrée des tests
     * Chaque méthode teste une règle précise.
     */
    public function run(): void
    {
        $this->testValidEmail();
        echo "testValidEmail() OK\n";

        $this->testInvalidEmail();
        echo "testInvalidEmail() OK\n";

        $this->testPasswordValidation();
        echo "testPasswordValidation() OK\n";

        $this->testMessageValidation();
        echo "testMessageValidation() OK\n";

        $this->testIdValidation();
        echo "testIdValidation() OK\n";
    }
}

/* ======================================================
   Exécution du test
   ====================================================== */

$test = new ValidatorTest();
$test->run();
