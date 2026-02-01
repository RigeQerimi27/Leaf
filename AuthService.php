<?php
declare(strict_types=1);

require_once __DIR__ . '/UserRepository.php';

final class AuthService
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
       
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->users = $users;
    }

   
    public function login(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);
        if (!$user) {
            return false; 
        }

        
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        return true;
    }

    
    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

   
    public function role(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

   
    public function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

   
    public function email(): ?string
    {
        return $_SESSION['user_email'] ?? null;
    }

    
    public function logout(): void
    {
        
        $_SESSION = [];

       
        session_destroy();
    }

   
    public function requireAdmin(): void
    {
        if (!$this->check() || $this->role() !== 'admin') {
            header("Location: signin.php");
            exit;
        }
    }
}

