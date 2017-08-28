<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/28/2017
 * Time: 5:18 PM
 */

namespace Models;

require_once dirname(__FILE__) . '/../DB/DB.php';

use DB\DB;

class Model
{

    protected $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }
}