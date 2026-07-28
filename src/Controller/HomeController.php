<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PostService;

class HomeController extends AbstractController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        $posts = $this->postService->getAllPosts();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}

