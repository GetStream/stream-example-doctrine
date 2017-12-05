<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Cake\Chronos\Chronos;
use Doctrine\Common\Collections\Criteria;
use GetStream\Doctrine\EnrichInterface;
use GetStream\Doctrine\FeedManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig_Environment;

class Index
{
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
    private $enricher;

    /**
     * @param Twig_Environment $twig
     * @param FeedManagerInterface $feedManager
     * @param EnrichInterface $enricher
     */
    public function __construct(Twig_Environment $twig, FeedManagerInterface $feedManager, EnrichInterface $enricher)
    {
        $this->twig = $twig;
        $this->feedManager = $feedManager;
        $this->enricher = $enricher;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function home(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var User $user */
        $user = $request->getAttribute('user');
        $activities = $this->feedManager->getFeed('timeline', $user->id())->getActivities();
        $activities = $this->enricher->enrichActivities($activities['results']);

        foreach ($activities as $activity) {
            $activity['time'] = new Chronos($activity['time']);
        }

        $criteria = Criteria::create()->where(Criteria::expr()->eq('user', $user));

        $body = $this->twig->render('home.twig', [
            'posts' => $activities,
            'has_liked' => $criteria,
        ]);

        $response->getBody()->write($body);

        return $response;
    }
}
