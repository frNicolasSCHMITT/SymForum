<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Entity\User;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

class CoreController extends AbstractController
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
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $data = $articleRepository->findBy(
            ['isPublished' => true],
            ['lastUpdateDate' => 'DESC']
        );

        $articles = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            8 // Nombre de résultats par page
        );

        return $this->render('core/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/index/{id}", name="show_article", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function show( Article $article, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // dd(($this->security->getUser()));
        // dd($article);
        $comments = $commentRepository->findBy(
            ['Article' => $article],
            ['creationDate' => 'ASC']
        );

        // $time = date('Y/m/d H:i:s');
        $time = new \DateTime();
        $comment = new Comment();
        $comment->setUser($this->security->getUser());
        $comment->setArticle($article);
        $comment->setCreationDate($time);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            // return $this->redirectToRoute('comment_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirect($request->getUri()); // Reload page after submit
        }

        return $this->renderForm('core/show.html.twig', [
            'comment' => $comment, 'form' => $form, 'article' => $article, 'comments' => $comments
        ]);

        // return $this->render('core/show.html.twig', ['article' => $article, 'comments' => $comments]);
    }

    /**
     * @Route("/index", name="article_index")
     */
    public function articleIndex(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        // $articles = $articleRepository->findAll();
        $data = $articleRepository->findBy(
            ['isPublished' => true],
            ['lastUpdateDate' => 'ASC']
        );

        $articles = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            8 // Nombre de résultats par page
        );

        return $this->render('core/article_index.html.twig', ['articles' => $articles]);
    }

}
