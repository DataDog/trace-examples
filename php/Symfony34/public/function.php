<?php

namespace Some\App;

use Thrift\ClassLoader\ThriftClassLoader;

function registerThriftNamespace()
{
    $GEN_DIR = __DIR__ . '/../../gen-php';
    $loader = new ThriftClassLoader();
    $loader->registerDefinition('shared', $GEN_DIR);
    $loader->registerDefinition('sample_thrift', $GEN_DIR);
    $loader->register();
}
    