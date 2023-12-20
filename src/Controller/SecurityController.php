<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use OpenApi\Attributes as OA;
class SecurityController extends AbstractController
{
    #[Route('/createClient', name: 'api_login', methods: ['POST'])]
    #[OA\RequestBody(
        description: "Request body for login",
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties:[
                new OA\Property(type:'string', property:'username'),
                new OA\Property(type:'string', property:'password'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Return access token',
    )]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        $response = [];
         if (null === $user) {
             $response['status'] = Response::HTTP_UNAUTHORIZED;
             $response['error'] = 'missing credentials';
         }
        $response['status'] = Response::HTTP_OK;
        $response['data'] = [
            'user'  => $user->getUserIdentifier(),
            'token' => $user->getToken(),
        ];

        return $this->json($response, $response['status']);
    }
}
