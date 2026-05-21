<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/articles', name: 'app_post_index')]
    public function index(Request $request, PostRepository $repo, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repo->findPublishedQuery(),
            $request->query->getInt('page', 1),
            9
        );
            

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
