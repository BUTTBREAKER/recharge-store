<?php

namespace RECHARGE\models;

use flight\Container;
use PDO;

class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = Container::getInstance()->get(PDO::class);
    }
}
