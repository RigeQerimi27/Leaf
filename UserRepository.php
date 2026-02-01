<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

final class UserRepository
{
    private mysqli $db;

    public function __construct(Database $database)
    {
        
        $this->db = $database->getConnection();
    }

   
    public function create(string $fullName, string $email, string $password, string $role = 'user'): int
    {
       
        $fullName = trim($fullName);
        $email = trim(strtolower($email));

        
        $hash = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $this->db->prepare("
            INSERT INTO users (full_name, email, password_hash, role)
            VALUES (?, ?, ?, ?)
        ");
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->db->error);
        }

       
        $stmt->bind_param("ssss", $fullName, $email, $hash, $role);

        if (!$stmt->execute()) {
           
            throw new RuntimeException("Create user failed: " . $stmt->error);
        }

        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }

   
    public function findByEmail(string $email): ?array
    {
        $email = trim(strtolower($email));

        $stmt = $this->db->prepare("
            SELECT id, full_name, email, password_hash, role
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc() ?: null;

        $stmt->close();
        return $user;
    }

   
    public function all(): array
    {
        $result = $this->db->query("
            SELECT id, full_name, email, role, created_at
            FROM users
            ORDER BY id DESC
        ");

        if (!$result) {
            throw new RuntimeException("Query failed: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, full_name, email, role
            FROM users
            WHERE id = ?
            LIMIT 1
        ");
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc() ?: null;

        $stmt->close();
        return $user;
    }

   
    public function update(int $id, string $fullName, string $email, string $role): void
    {
        $fullName = trim($fullName);
        $email = trim(strtolower($email));

        $stmt = $this->db->prepare("
            UPDATE users
            SET full_name = ?, email = ?, role = ?
            WHERE id = ?
        ");
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("sssi", $fullName, $email, $role, $id);

        if (!$stmt->execute()) {
            throw new RuntimeException("Update failed: " . $stmt->error);
        }

        $stmt->close();
    }

    
    public function updatePassword(int $id, string $newPassword): void
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
            UPDATE users
            SET password_hash = ?
            WHERE id = ?
        ");
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("si", $hash, $id);

        if (!$stmt->execute()) {
            throw new RuntimeException("Password update failed: " . $stmt->error);
        }

        $stmt->close();
    }

   
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new RuntimeException("Delete failed: " . $stmt->error);
        }

        $stmt->close();
    }
}
