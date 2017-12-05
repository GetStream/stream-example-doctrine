<?php

namespace App\Http\Controllers;

use App\Models\UserRepository;
use Cake\Chronos\Chronos;
use Doctrine\Common\Collections\Criteria;
use GetStream\Doctrine\EnrichInterface;
use GetStream\Doctrine\FeedManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig_Environment;

class Profile
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var FeedManagerInterface
     */
    private $feedManager;

    /**
     * @var EnrichInterface
     */
    private $enrich;

    /**
     * @param UserRepository $repository
     * @param Twig_Environment $twig
     * @param FeedManagerInterface $feedManager
     * @param EnrichInterface $enrich
     */
    public function __construct(
        UserRepository $repository,
        Twig_Environment $twig,
        FeedManagerInterface $feedManager,
        EnrichInterface $enrich
    ) {
        $this->repository = $repository;
        $this->twig = $twig;
        $this->feedManager = $feedManager;
        $this->enrich = $enrich;
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
        list(,$username) = explode('/', $request->getUri()->getPath());

        $profile = $this->repository->findByUsername($username);

        $activities = $this->feedManager->getUserFeed($profile->id())->getActivities()['results'];

        $activities = $this->enrich->enrichActivities($activities);

        foreach ($activities as $activity) {
            $activity['time'] = new Chronos($activity['time']);
        }

        $criteria = Criteria::create()->where(Criteria::expr()->eq('user', $request->getAttribute('user')));

        $html = $this->twig->render('profile.twig', [
            'profile' => $profile,
            'activities' => $activities,
            'has_liked' => $criteria,
        ]);

        $response->getBody()->write($html);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $userId = $request->getAttribute('user')->id();

        $criteria = Criteria::create()->where(Criteria::expr()->neq('id', $userId));
        $results = $this->repository->matching($criteria);

        $html = $this->twig->render('profiles.twig', [
            'users' => $results,
        ]);

        $response->getBody()->write($html);

        return $response;
    }
}
