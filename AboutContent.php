<?php
declare(strict_types=1);


class AboutContent
{
    private mysqli $connection;

    public function __construct(mysqli $connection) 
    {
        $this->connection = $connection;
    }

    
    public function latest(): ?array
    {
        $result = $this->connection->query(
            'SELECT * FROM about_content ORDER BY id DESC LIMIT 1'
        );

        if (!$result) {
            return null;
        }

        $row = $result->fetch_assoc();
        return $row ?: null;
    }

    
    private function latestId(): ?int
    {
        $result = $this->connection->query(
            'SELECT id FROM about_content ORDER BY id DESC LIMIT 1'
        );

        if (!$result) {
            return null;
        }

        $row = $result->fetch_assoc();
        return $row ? (int)$row['id'] : null;
    }

    
    public function save(array $fields): bool
    {
       
        $allowed = [
            'about_subtitle',
            'mission_text',
            'why_subtitle',
            'feature1_title','feature1_text',
            'feature2_title','feature2_text',
            'feature3_title','feature3_text',
            'feature4_title','feature4_text',
        ];

        
        $clean = [];
        foreach ($fields as $k => $v) {
            if (in_array($k, $allowed, true)) {
                $clean[$k] = $v;
            }
        }

        if (empty($clean)) {
            return false;
        }

        $id = $this->latestId();

        
        if ($id === null) {
            $cols = implode(',', array_keys($clean));
            $placeholders = implode(',', array_fill(0, count($clean), '?'));

            $stmt = $this->connection->prepare(
                "INSERT INTO about_content ($cols) VALUES ($placeholders)"
            );
            if (!$stmt) return false;

            $types = str_repeat('s', count($clean));
            $values = array_values($clean);

            $stmt->bind_param($types, ...$values);
            return $stmt->execute();
        }

       
        $sets = [];
        foreach (array_keys($clean) as $col) {
            $sets[] = "$col = ?";
        }

        $sql = "UPDATE about_content SET " . implode(', ', $sets) . " WHERE id = ?";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) return false;

        $types = str_repeat('s', count($clean)) . 'i';
        $values = array_values($clean);
        $values[] = $id;

        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }
}
