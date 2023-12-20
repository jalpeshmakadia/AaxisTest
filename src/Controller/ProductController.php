<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/api", "api_product_", format: 'json')]
#[IsGranted("IS_AUTHENTICATED")]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/product', name: 'list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the list of products',
        content: new OA\JsonContent( type: 'array', items: new OA\Items(ref: new Model(type: Product::class)))
    )]
    public function listProduct(): JsonResponse
    {
        $response = [];
        $products = $this->productRepository->findAll();
        $response['status'] = Response::HTTP_OK;
        $response['data'] = $products;
        return $this->json($response, $response['status']);
    }

    #[Route("/product/{id}", "get", requirements: ['id' => '\d+'], methods: ["GET"])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Product::class)
    )]
    public function getProduct(Request $request): Response
    {
        $response = [];
        $id = $request->get('id');
        $product = $this->productRepository->find($request->get('id'));
        
        if (!empty($product)) {
            $response['status'] = Response::HTTP_OK;
            $response['data'] = $product;
            return $this->handleView($this->view($response));
        }
        
        $response['status'] = Response::HTTP_NOT_FOUND;
        $response['error'] = sprintf('Product with id %d not found.', $id);

        return $this->json($response, $response['status']);
    }

    #[Route("/product/update", "update", methods: ["PUT"])]
    #[OA\RequestBody(
        description: "Request body for product details",
        required: true,
        content: new OA\JsonContent( type: 'array', items: new OA\Items(ref: new Model(type: Product::class)))
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the updated product and product with error sku',
    )]
    #[OA\Response(
        response: 400,
        description: 'Returns if any error',
    )]
    public function updateProducts(Request $request, ValidatorInterface $validator): Response
    {
        $response = [];
        $requestProductData = json_decode($request->getContent(), true);
    
        try {
            
            foreach ($requestProductData as $requestProduct) {
        
                $product = $this->productRepository->findOneBy(['sku' => $requestProduct['sku']]);
        
                if (!empty($product)) {
            
                    $product->setSku($requestProduct['sku']);
                    $product->setProductName($requestProduct['productName']);
                    $product->setDescription($requestProduct['description']);
            
                    $validationStatus = $validator->validate($product);
                    if (count($validationStatus) > 0) {
                        
                        foreach ($validationStatus as $error) {
                            $response['data']['error'][$requestProduct['sku']][$error->getPropertyPath()]= $error->getMessage();
                        }
                        
                    } else {
                        
                        $this->em->persist($product);
                        $this->em->flush();
                        $response['data']['success'][$requestProduct['sku']] = sprintf('Product with sku %s has been updated successful.', $requestProduct['sku']);
                    
                    }
            
                } else {
                    $response['data']['error'][$requestProduct['sku']] = sprintf('Product not found with sku - %s', $requestProduct['sku']);
                }
                
                $response['status'] = Response::HTTP_OK;
            }
            
        } catch (\Exception $e) {
            
            $response['status'] = Response::HTTP_BAD_REQUEST;
            $response['error'] = $e->getMessage();
            
        }

        return $this->json($response, $response['status']);
    }
}
