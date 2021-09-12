<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController
{
    #[Route('/', name: 'api_root')]
    public function indexAction(): Response
    {
        return new JsonResponse(['data' => 'no-no']);
    }
}
