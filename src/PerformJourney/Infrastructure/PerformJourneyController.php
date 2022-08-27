<?php

namespace App\PerformJourney\Infrastructure;

use App\PerformJourney\Application\PerformJourneyCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\CheckContentType;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PerformJourneyController extends BaseController
{
    #[Route('/journey', name: 'perform_journey', methods: ['POST'])]
    public function performJourneyAction(MessageBus $messageBus, Request $request, CheckContentType $checkContentType): JsonResponse
    {
        $message = null;

        try {
            $checkContentType($request->getContentType());
            $groupData = json_decode((string) $request->getContent());
            $performJourneyCommand = new PerformJourneyCommand($groupData);
            $message = $messageBus->handle($performJourneyCommand);
        } catch (Exception) {
            return $this->badRequest();
        }

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
