<?php
declare(strict_types=1);

class SkintypePage
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    
    public function latest(): array
    {
        $result = $this->conn->query(
            "SELECT * FROM skintype_page ORDER BY id DESC LIMIT 1"
        );

        if (!$result) {
            return [];
        }

        $row = $result->fetch_assoc();
        return $row ?: [];
    }

   
    public function update(int $id, array $data): void
    {
        $sql = "
            UPDATE skintype_page SET
                hero_pill = ?,
                hero_title = ?,
                hero_subtext = ?,

                q1_text = ?,
                q1_a_text = ?, q1_a_type = ?,
                q1_b_text = ?, q1_b_type = ?,
                q1_c_text = ?, q1_c_type = ?,
                q1_d_text = ?, q1_d_type = ?,

                q2_text = ?,
                q2_a_text = ?, q2_a_type = ?,
                q2_b_text = ?, q2_b_type = ?,
                q2_c_text = ?, q2_c_type = ?,
                q2_d_text = ?, q2_d_type = ?,

                q3_text = ?,
                q3_a_text = ?, q3_a_type = ?,
                q3_b_text = ?, q3_b_type = ?,
                q3_c_text = ?, q3_c_type = ?,
                q3_d_text = ?, q3_d_type = ?

            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->conn->error);
        }

        
        $get = function (string $key) use ($data): string {
            return trim((string)($data[$key] ?? ''));
        };

       
        $hero_pill    = $get('hero_pill');
        $hero_title   = $get('hero_title');
        $hero_subtext = $get('hero_subtext');

        $q1_text  = $get('q1_text');
        $q1_a_text = $get('q1_a_text'); $q1_a_type = $get('q1_a_type');
        $q1_b_text = $get('q1_b_text'); $q1_b_type = $get('q1_b_type');
        $q1_c_text = $get('q1_c_text'); $q1_c_type = $get('q1_c_type');
        $q1_d_text = $get('q1_d_text'); $q1_d_type = $get('q1_d_type');

        $q2_text  = $get('q2_text');
        $q2_a_text = $get('q2_a_text'); $q2_a_type = $get('q2_a_type');
        $q2_b_text = $get('q2_b_text'); $q2_b_type = $get('q2_b_type');
        $q2_c_text = $get('q2_c_text'); $q2_c_type = $get('q2_c_type');
        $q2_d_text = $get('q2_d_text'); $q2_d_type = $get('q2_d_type');

        $q3_text  = $get('q3_text');
        $q3_a_text = $get('q3_a_text'); $q3_a_type = $get('q3_a_type');
        $q3_b_text = $get('q3_b_text'); $q3_b_type = $get('q3_b_type');
        $q3_c_text = $get('q3_c_text'); $q3_c_type = $get('q3_c_type');
        $q3_d_text = $get('q3_d_text'); $q3_d_type = $get('q3_d_type');

        $types = str_repeat('s', 30) . 'i';

        $stmt->bind_param(
            $types,
            $hero_pill,
            $hero_title,
            $hero_subtext,

            $q1_text,
            $q1_a_text, $q1_a_type,
            $q1_b_text, $q1_b_type,
            $q1_c_text, $q1_c_type,
            $q1_d_text, $q1_d_type,

            $q2_text,
            $q2_a_text, $q2_a_type,
            $q2_b_text, $q2_b_type,
            $q2_c_text, $q2_c_type,
            $q2_d_text, $q2_d_type,

            $q3_text,
            $q3_a_text, $q3_a_type,
            $q3_b_text, $q3_b_type,
            $q3_c_text, $q3_c_type,
            $q3_d_text, $q3_d_type,

            $id
        );

        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException("Execute failed: " . $this->conn->error);
        }

        $stmt->close();
    }
}
