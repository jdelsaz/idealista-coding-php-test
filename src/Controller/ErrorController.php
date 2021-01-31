<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ErrorController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            ['success' => false],
            Response::HTTP_NOT_FOUND
        );
    }
}
