<?php

namespace App\Models;

use flight\Container;
use PDO;

class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Container::getInstance()->get(PDO::class);
    }
}
