<?php

namespace App\Http\Controllers;

use App\Users\Commands\Signup as SignupCommand;
use DomainException;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Ramsey\Uuid\UuidFactoryInterface;
use Twig_Environment;
use Zend\Diactoros\Response\RedirectResponse;

class Signup
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var UuidFactoryInterface
     */
    private $uuid;

    /**
     * @param Twig_Environment $twig
     * @param CommandBus $commandBus
     * @param UuidFactoryInterface $uuid
     */
    public function __construct(Twig_Environment $twig, CommandBus $commandBus, UuidFactoryInterface $uuid)
    {
        $this->twig = $twig;
        $this->commandBus = $commandBus;
        $this->uuid = $uuid;
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

        if ($session->get('flash.errors.signup_failed', false)) {
            $data = ['errors' => ['signup_failed' => true]];
        }

        $html = $this->twig->render('signup.twig', $data);

        $response->getBody()->write($html);

        $session->remove('flash.errors.signup_failed');

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return RedirectResponse
     */
    public function signup(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $email = $request->getParsedBody()['email'];
        $firstName = $request->getParsedBody()['first_name'];
        $lastName = $request->getParsedBody()['last_name'];
        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];

        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$validEmail || empty($username) || empty($firstName) || empty($lastName) || mb_strlen($password) < 6) {
            $session->set('flash.errors.signup_failed', true);

            return new RedirectResponse($request->getUri()->withPath('/signup'));
        }

        $id = $this->uuid->uuid4();

        try {
            $this->commandBus->handle(new SignupCommand($id, $email, $username, $firstName, $lastName, $password));
        } catch (DomainException $exception) {
            $session->set('flash.errors.signup_failed', true);

            return new RedirectResponse($request->getUri()->withPath('/signup'));
        }

        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $session->set('user_id', $id->toString());

        return new RedirectResponse($request->getUri()->withPath('/'));
    }
}
