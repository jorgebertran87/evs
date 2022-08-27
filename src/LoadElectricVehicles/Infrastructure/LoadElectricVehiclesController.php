<?php

namespace App\LoadElectricVehicles\Infrastructure;

use App\LoadElectricVehicles\Application\LoadElectricVehiclesCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\CheckContentType;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoadElectricVehiclesController extends BaseController
{
    #[Route('/evs', name: 'load_electric_vehicles', methods: ['PUT'])]
    public function loadElectricVehiclesAction(
        MessageBus $messageBus,
        CheckContentType $checkContentType,
        Request $request
    ): JsonResponse {
        $message = null;
        try {
            $checkContentType($request->getContentType());
            $electricVehiclesData = json_decode((string) $request->getContent());
            $loadElectricVehiclesCommand = new LoadElectricVehiclesCommand($electricVehiclesData);
            $message = $messageBus->handle($loadElectricVehiclesCommand);
        } catch (Exception) {
            return $this->badRequest();
        }

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
