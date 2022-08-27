<?php

namespace App\LocateGroup\Infrastructure;

use App\LocateGroup\Application\LocateGroupQuery;
use App\LocateGroup\Domain\GroupIsWaitingToBeAssignedException;
use App\LocateGroup\Domain\GroupNotFoundException;
use App\Shared\Application\MessageBus;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\CheckContentType;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocateGroupController extends BaseController
{
    #[Route('/locate', name: 'locate_group', methods: ['POST'])]
    public function LocateGroupAction(MessageBus $messageBus, Request $request, CheckContentType $checkContentType): JsonResponse
    {
        try {
            $checkContentType($request->getContentType());
            $idData = json_decode((string) $request->getContent());
            $locateGroupQuery = new LocateGroupQuery($idData);

            return new JsonResponse($messageBus->handle($locateGroupQuery), Response::HTTP_OK);
        } catch (GroupNotFoundException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        } catch (GroupIsWaitingToBeAssignedException) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (Exception) {
            return $this->badRequest();
        }
    }
}
