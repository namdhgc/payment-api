<?php

namespace Src\Controllers;

use Src\Constants\Constants;
use Src\Constants\Messages;

class BaseController
{
    /**
     * @OA\Info(title="Payment API", version="1.0.0")
     */
    protected function successResponse(): array
    {
        $response['status_code_header'] = Messages::STATUS_CODE_HEADER_SUCCESS;
        $response['body'] = "true";

        return $response;
    }

    protected function notFoundResponse(): array
    {
        $response['status_code_header'] = Messages::STATUS_CODE_HEADER_NOT_FOUND;
        $response['body'] = null;

        return $response;
    }

    protected function unprocessableEntityResponse(string $message): array
    {
        $response['status_code_header'] = Messages::STATUS_CODE_HEADER_UNPROCESSABLE_ENTITY;
        $response['body'] = json_encode([
            'error' => $message
        ]);
        return $response;
    }
}
