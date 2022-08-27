<?php

namespace App\DropOffGroup\Infrastructure;

use App\DropOffGroup\Application\DropOffGroupCommand;
use App\DropOffGroup\Domain\GroupIsNotLoadedException;
use App\Shared\Application\MessageBus;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\CheckContentType;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DropOffGroupController extends BaseController
{
    #[Route('/dropoff', name: 'dropoff_group', methods: ['POST'])]
    public function dropOffGroupAction(
        MessageBus $messageBus,
        Request $request,
        CheckContentType $checkContentType
    ): JsonResponse {
        $message = null;

        try {
            $checkContentType($request->getContentType());
            $idData = json_decode((string) $request->getContent());
            $dropOffGroupCommand = new DropOffGroupCommand($idData);
            $message = $messageBus->handle($dropOffGroupCommand);
        } catch (GroupIsNotLoadedException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        } catch (Exception) {
            return $this->badRequest();
        }

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
