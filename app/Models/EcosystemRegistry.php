<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class EcosystemRegistry
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function bySection(string $section): array
    {
        return $this->database->query(
            'SELECT
                table_name AS `table`,
                group_name AS `group`,
                label,
                sort_order AS `order`
             FROM he_sinh_thai_dong
             WHERE section = :section
             ORDER BY sort_order ASC, id ASC',
            ['section' => $section]
        );
    }
}
