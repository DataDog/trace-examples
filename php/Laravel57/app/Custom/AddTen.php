<?php

namespace App\Custom;

class AddTen
{
    public function handle($content, \Closure $next)
    {
        return  $next($content + 10);
    }
}
