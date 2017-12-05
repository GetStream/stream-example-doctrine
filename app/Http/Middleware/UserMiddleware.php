<?php

namespace App\Http\Middleware;

use App\Models\UserRepository;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;

class UserMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if (!$session->has('user_id')) {
            return $delegate->process($request);
        }

        $user = $this->userRepository->find($session->get('user_id'));

        if ($user) {
            $request = $request->withAttribute('user', $user);
        } else {
            $session->remove('user_id');
        }

        return $delegate->process($request);
    }
}
