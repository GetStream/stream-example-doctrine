<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Twig_Environment;
use Zend\Diactoros\Response\RedirectResponse;

class Login
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param UserRepository $userRepository
     * @param Twig_Environment $twig
     */
    public function __construct(UserRepository $userRepository, Twig_Environment $twig)
    {
        $this->userRepository = $userRepository;
        $this->twig = $twig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function show(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = [];

        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($session->get('flash.errors.login_failed', false)) {
            $data = ['errors' => ['login_failed' => true]];
        }

        $html = $this->twig->render('login.twig', $data);

        $response->getBody()->write($html);

        $session->remove('flash.errors.login_failed');

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return RedirectResponse
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $login = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];

        $filter = Criteria::create()
            ->orWhere(Criteria::expr()->eq('email', $login))
            ->orWhere(Criteria::expr()->eq('username', $login));

        /** @var User[] $result */
        $result = $this->userRepository->matching($filter)->getValues();

        if (empty($result) || !password_verify($password, $result[0]->passwordHash())) {
            $session->set('flash.errors.login_failed', true);

            return new RedirectResponse($request->getUri()->withPath('/login'));
        }

        $session->set('user_id', $result[0]->id());
        $session->set('flash.messages.login', true);

        return new RedirectResponse($request->getUri()->withPath('/'));
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return RedirectResponse
     */
    public function demo(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $filter = Criteria::create()
            ->where(Criteria::expr()->eq('username', 'demo'));

        /** @var User[] $result */
        $result = $this->userRepository->matching($filter)->getValues();

        $session->set('user_id', $result[0]->id());

        return new RedirectResponse($request->getUri()->withPath('/'));
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return RedirectResponse
     */
    public function logout(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $session->remove('user_id');

        return new RedirectResponse($request->getUri()->withPath('/login'));
    }
}
