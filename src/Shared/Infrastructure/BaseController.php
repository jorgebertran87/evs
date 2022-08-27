<?php

namespace App\Shared\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    protected function badRequest(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
    }
}
