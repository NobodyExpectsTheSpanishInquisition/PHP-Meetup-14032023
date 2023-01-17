<?php

namespace App\Controller;

use App\Entity\Product;
use App\Notification\NotificationService;
use App\Notification\ProductEditedNotification;
use App\Notification\ProductHasDiscountNotification;
use App\Repository\DiscountRepository;
use App\Repository\ProductRepository;
use App\View\ProductView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        $views = array_map(
            static fn(Product $product): ProductView => new ProductView(
                $product->getId(),
                $product->getName(),
                $product->getDescription(),
                $product->getPrice()
            ),
            $products
        );

        return new JsonResponse($views);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $product = new Product(
            $request->get('uuid'),
            $request->get('name'),
            $request->get('description'),
            $request->get('price'),
        );

        $productRepository->save($product, true);

        return new JsonResponse(null, 204);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return new JsonResponse(
            new ProductView(
                $product->getId(),
                $product->getName(),
                $product->getDescription(),
                $product->getPrice()
            )
        );
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Product $product,
        ProductRepository $productRepository,
        DiscountRepository $discountRepository,
        NotificationService $notificationService
    ): Response {
        $productId = $product->getId();

        $product->setName($request->get('name'));
        $product->setPrice($request->get('price'));
        $product->setDescription($request->get('description'));

        if (null !== $request->get('discountId')) {
            $discountId = $request->get('discountId');
            $discount = $discountRepository->find($discountId);

            $product->setDiscount($discount);

            $notificationService->send(new ProductHasDiscountNotification($productId, $discountId));
        }

        $productRepository->save($product, true);
        $notificationService->send(new ProductEditedNotification($productId));

        return new JsonResponse(null, 204);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $productRepository->remove($product, true);

        return new JsonResponse();
    }
}
