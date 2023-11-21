<?php

namespace App\Tests;

use App\Entity\Products;
use PHPUnit\Framework\TestCase;

class ProductNameTest extends TestCase
{
    public function testNameIsNotEmpty(): void
    {
        $name = 'oreillette';
        $product = new Products();
        $product->setName($name);
        $this->assertNotEmpty($product->getName());
    }
}