<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserAuthController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security )
    {
        $this->security = $security;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, CommentRepository $commentRepository, ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {

             // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
                
            $loggedUser = $this->security->getUser();

            $articles = $articleRepository->findBy(
                ['User' => $loggedUser],
                ['lastUpdateDate' => 'ASC']
            );

            $comments = $commentRepository->findBy(
                ['User' => $loggedUser],
                ['creationDate' => 'ASC']
            );  

            $users = $userRepository->findBy(
                ['email' => $loggedUser->getUserIdentifier()]
            );

            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,  'articles' => $articles, 'comments' => $comments, 'users' => $users]);
        
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
