<?php

namespace App\Infrastructure\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CallableWrapperDelegate implements DelegateInterface
{
    /**
     * @var callable
     */
    private $next;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param callable $next
     * @param ResponseInterface $response
     */
    public function __construct(callable $next, ResponseInterface $response)
    {
        $this->next = $next;
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function process(RequestInterface $request)
    {
        return call_user_func_array($this->next, [$request, $this->response]);
    }
}
