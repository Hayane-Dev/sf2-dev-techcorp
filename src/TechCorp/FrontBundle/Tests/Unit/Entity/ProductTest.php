<?php

namespace TechCorp\FrontBundle\Tests\Unit\Entity;

use TechCorp\FrontBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductTest extends WebTestCase
{
    public function testcomputeTVAFoodProduct()
    {
        $product = new Product('Un produit', Product::FOOD_PRODUCT, 20);

        $this->assertSame(1.12, $product->computeTVA());
    }

    public function testcomputeTVAOtherProduct()
    {
        $product = new Product('Un autre produit', 'Un autre type de prooduit', 20);

        $this->assertSame(3.92, $product->computeTVA());
    }

    public function testNegativePriceComputeTVA()
    {
        $product = new Product('Un produit', Product::FOOD_PRODUCT, -20);

        $this->expectException('LogicException'); // Full Qualified Class Name (FQCN)

        $product->computeTVA();
    }

    /**
     * @dataProvider pricesForFoodProduct
     */
    public function testcomputeTVAFoodProductWithDataProvider($price, $expectedTva)
    {
        $product = new Product('Un produit', Product::FOOD_PRODUCT, $price);

        $this->assertSame($expectedTva, $product->computeTVA());
    }

    public function pricesForFoodProduct()
    {
        return [
            [0, 0.0],
            [20, 1.1],
            [100, 5.5]
        ];
    }
}

// On peut utiliser la commande bin/phpunit -c app --filter testNegativePriceComputeTVA pour ne lancer qu'un seul test

