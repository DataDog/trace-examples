<?php

namespace App\Http\Controllers;

use App\Custom\AddTen;
use App\Custom\MultiplyByTwo;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    public function pdoAction()
    {
        $pipes = [
            new AddTen(),
            new MultiplyByTwo(),
        ];

        /** @var Pipeline $pipeline */
        $pipeline = App::make(Pipeline::class);

        print_r("Here\n");
        $pipeline
            ->send(10)
            ->through($pipes)
            ->then(function ($result) {
                echo "Final result of (10 + 10) * 2 is $result\n";
            });

        $pdo = new \PDO('mysql:host=mysql;dbname=test', 'test', 'test');
        $stmt = $pdo->prepare("SELECT * from information_schema.tables LIMIT 1");
        $stmt->execute();
        $res = $stmt->fetch();
        print_r($res);
        return new Response();
    }
}
