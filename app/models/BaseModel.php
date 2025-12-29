<?php

namespace RECHARGE\models;

use Flight;

class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = Flight::db();
    }
}
