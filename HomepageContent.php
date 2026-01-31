<?php
declare(strict_types=1);

class HomepageContent
{
    private mysqli $connection;

   
    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    
    public function latest(): ?array
    {
        $result = $this->connection->query(
            'SELECT * FROM homepage_content ORDER BY id DESC LIMIT 1'
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
            'SELECT id FROM homepage_content ORDER BY id DESC LIMIT 1'
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
            'hero_title','hero_sub',
            'skin_title','skin_text','skin_btn_text','skin_btn_link',
            'skin_info_title','skin_info_text',
            'top_picks_title','top_picks_sub',
            'card1_title','card1_text','card1_link',
            'card2_title','card2_text','card2_link',
            'card3_title','card3_text','card3_link'
        ];

        
        $clean = [];
        foreach ($fields as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $clean[$key] = $value;
            }
        }

       
        if (empty($clean)) {
            return false;
        }

        $id = $this->latestId();

        
        if ($id === null) {
            $columns = implode(',', array_keys($clean));
            $placeholders = implode(',', array_fill(0, count($clean), '?'));

            $stmt = $this->connection->prepare(
                "INSERT INTO homepage_content ($columns) VALUES ($placeholders)"
            );

            $types = str_repeat('s', count($clean));
            $values = array_values($clean);

            $stmt->bind_param($types, ...$values);
            return $stmt->execute();
        }

        
        $sets = [];
        foreach (array_keys($clean) as $column) {
            $sets[] = "$column = ?";
        }

        $sql = "UPDATE homepage_content SET " . implode(', ', $sets) . " WHERE id = ?";

        $stmt = $this->connection->prepare($sql);

        $types = str_repeat('s', count($clean)) . 'i';
        $values = array_values($clean);
        $values[] = $id;

        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }
}

