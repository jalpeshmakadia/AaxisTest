<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


#[Route("/api", "api_product_")]
class ProductController extends AbstractFOSRestController
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/product', name: 'list', methods: ['GET'])]
    public function listProduct(): Response
    {
        $products = $this->productRepository->findAll();
        return $this->handleView($this->view($products));
    }

    #[Route("/product/{id}", "get", methods: ["GET"])]
    public function getProduct(Request $request): Response
    {
        $product = $this->productRepository->find($request->get('id'));
        return $this->handleView($this->view($product));
    }

    #[Route("/product/update", "update", methods: ["PATCH", "PUT"])]
    public function updateProducts(Request $request): Response
    {
        $response = [];
        $requestProductData = json_decode($request->getContent(), true);

        foreach ($requestProductData as $requestProduct) {

            $product = $this->productRepository->findOneBy(['sku' => $requestProduct['sku']]);

            if (!empty($product)) {

                $form = $this->createForm(ProductType::class, $product);
                $form->submit($requestProduct);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->em->persist($product);
                    $this->em->flush();
                } else {
                    $response['error'][$requestProduct['sku']] = $form->getErrors();
                }

            } else {
                $response['error'][$requestProduct['sku']] = sprintf('Product not found with sku - %s', $requestProduct['sku']);
            }

        }

        return $this->handleView($this->view($response));
    }
}
