<?php

declare(strict_types=1);

namespace Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProductRepository $productRepository;

    /**
     * SMOKE TEST
     */
    public function test_Edit_ShouldPass_WhenNoErrorOccurred(): void
    {
        $productId = '64931C5A-2ACE-4504-97F2-21F6CB5C0F76';
        $product = new Product(uuid: $productId, name: 'test product', description: 'test description', price: '10.00');
        $this->productRepository->save(entity: $product, flush: true);

        $url = sprintf('/product/%s/edit', $productId);
        $this->client->request('POST', $url);

        $response = $this->client->getResponse();
        self::assertEquals(204, $response->getStatusCode());

        $this->productRepository->remove(entity: $product, flush: true);
    }

    /**
     * INTEGRATION TEST
     */
    public function test_Edit_ShouldEditProductName_WhenNewNameIsProvided(): void
    {
        $productId = '64931C5A-2ACE-4504-97F2-21F6CB5C0F76';
        $product = new Product(uuid: $productId, name: 'test product', description: 'test description', price: '10.00');
        $this->productRepository->save(entity: $product, flush: true);

        $newName = 'new name';
        $url = sprintf('/product/%s/edit', $productId);
        $this->client->request('POST', $url, [
            'form_params' => [
                'name' => $newName,
            ],
        ]);

        $editedProduct = $this->productRepository->find($productId);

        self::assertNotEquals($product, $editedProduct);
        self::assertEquals($newName, $editedProduct->getName());

        $this->productRepository->remove(entity: $product, flush: true);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->productRepository = $this->getContainer()->get(ProductRepository::class);
    }
}
