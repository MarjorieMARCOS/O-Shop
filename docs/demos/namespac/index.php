<?php

require_once __DIR__ . '/HelloWorld.php';
require_once __DIR__ . '/lib/HelloWorld.php';

use MySuperModule\HelloWorld as ModuleHelloWorld;
use Oshop\HelloWorld as OshopHelloWorld;

$myHello = new MySuperModule\ModuleHelloWorld();

$myHello->sayHello();

$myHello2 = new OshopHelloWorld();
$myHello2->sayHello();
