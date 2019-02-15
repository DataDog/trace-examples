<?php

namespace AppBundle\ThriftService;

use shared\SharedStruct;

class CalculatorHandler implements \sample_thrift\CalculatorIf
{
    /**
     * @param int $num1
     * @param int $num2
     * @return int
     */
    public function add($num1, $num2)
    {
        return $num1 + $num2;
    }

    /**
     * @param int $key
     * @return \shared\SharedStruct
     */
    public function getStruct($key)
    {
        return new SharedStruct();
    }
}
