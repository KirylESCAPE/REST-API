<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    protected int $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function response(array $data, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    public function respondWithErrors(array $errors, array $headers = []): JsonResponse
    {
        $data = [
            'status' => $this->getStatusCode(),
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    public function respondWithSuccess(string $success, array $headers = []): JsonResponse
    {
        $data = [
            'status' => $this->getStatusCode(),
            'success' => $success,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    public function respondUnauthorized(array $error, array $headers = []): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithErrors($error, $headers);
    }

    public function respondValidationError($message = 'Validation errors'): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    public function respondNotFound(array $error, array $headers = []): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithErrors($error, $headers);
    }

    public function respondCreated(array $data, array $headers): JsonResponse
    {
        return $this->setStatusCode(201)->response($data, $headers);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}