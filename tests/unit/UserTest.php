<?php

require_once __DIR__ . '/../TestConfig.php';

class UserTest 
{
    private PDO $pdo;
    private User $userModel;


    private string $testEmail;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new User($this->pdo);
    }


    /**
     * Test : recherche un utilisateur inexistant par email
     */
    private function testFindByEmailNotFound(): void
    {
        $email = 'not_found'. time() . '@test.com';
        assertTrue(
            $this->userModel->findByEmail($email) === null,
            'findByEmail retourne null pour un email inexistant'
        );
    } 

    /**
     * Test : création utilisateur
     */
    private function testCreateUser(): void
    {
        $this->testEmail = 'user'. time() . '@test.com';

        $created = $this->userModel->create([
            'role'  => 'client',
            'nom'   => 'Utilisateur Test',
            'email' => $this->testEmail,
            'password_hash' => password_hash('TestPassword123', PASSWORD_DEFAULT),
            'telephone' => '0123456789'
        ]);

        assertTrue(
            $created === true,
            'create retourne true après création réussie'
        );
    }

    /**
     * Test : wmailExists après création
     */
    private function testEmailExists():void
    {
        assertTrue(
            $this->userModel->emailExists($this->testEmail) === true,
            'emailExists retourne true après creation'
        );
    }
    /**
     * Test : authentification valide
     */
    private function testVerifyValidPassword(): void
    {
        $result = $this->userModel->verify($this->testEmail, 'TestPassword123');
    
        assertTrue(
            $result !== null,
            'verify accepte un mot de passe valide'
        );
    }
    /**
     * Test : authentification invalide
     */
    private function testVerifyInvalidPassword(): void
    {
        $result = $this->userModel->verify($this->testEmail, 'WrongPassword');

        assertTrue(
            $result === null,
            'verify retourne null pour un mot de passe invalide'
        );
    }

    /**
     * Lance l'ensemble des tests
     */
    public function run(): void
    {
        $this->testFindByEmailNotFound();
        echo "testFindByEmailNotFound OK\n";

        $this->testCreateUser();
        echo "testCreateUser OK\n";
        
        $this->testEmailExists();
        echo "testEmailExists OK\n";
        
        $this->testVerifyValidPassword();
        echo "testVerifyValidPassword OK\n";
        
        $this->testVerifyInvalidPassword();
        echo "testVerifyInvalidPassword OK\n";
        
    }

}

$test = new UserTest($pdo);
$test->run();