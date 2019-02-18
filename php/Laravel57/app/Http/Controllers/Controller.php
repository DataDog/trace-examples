<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function pdoAction()
    {
        $pdo = new \PDO('mysql:host=mysql;dbname=test', 'test', 'test');
        $stmt = $pdo->prepare("SELECT * from information_schema.tables LIMIT 1");
        $stmt->execute();
        $res = $stmt->fetch();
        print_r($res);
        return new Response();
    }
}
