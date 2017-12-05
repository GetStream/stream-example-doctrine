<?php

namespace App\Http;

use Exception;
use League\Route\Http\Exception\HttpExceptionInterface;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExceptionStrategy extends ApplicationStrategy
{
    /**
     * {@inheritdoc}
     */
    public function getNotFoundDecorator(NotFoundException $exception)
    {
        return $this->getExceptionDecorator($exception);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception)
    {
        return $this->getExceptionDecorator($exception);
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionDecorator(Exception $exception)
    {
        return function (ServerRequestInterface $request, ResponseInterface $response) use ($exception) {
            if (!$exception instanceof HttpExceptionInterface) {
                throw $exception;
            }

            $response = $this->getResponseWithExceptionData(
                $exception,
                $response->withStatus(500, $exception->getMessage())
            );

            $response->getBody()->write($exception->getMessage());

            return $response;
        };
    }

    /**
     * @param HttpExceptionInterface $exception
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    private function getResponseWithExceptionData(HttpExceptionInterface $exception, ResponseInterface $response)
    {
        foreach ($exception->getHeaders() as $header => $value) {
            $response = $response->withAddedHeader($header, $value);
        }

        return $response->withStatus($exception->getStatusCode());
    }
}
