<?php

namespace App\Custom;

class MultiplyByTwo
{
    public function handle($content, \Closure $next)
    {
        return  $next($content * 2);
    }
}
