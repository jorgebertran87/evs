<?php

namespace App\Shared\Infrastructure;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends BaseController
{
    #[Route('/status', name: 'status', methods: ['GET'])]
    public function statusAction(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_OK);
    }
}
