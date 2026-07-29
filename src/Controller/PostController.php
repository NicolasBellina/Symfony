<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    #[Route('/post/new', name: 'post_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $author */
            $author = $this->getUser();
            // Use service to create and persist the post
            $this->postService->createPost($post->getTitle(), $post->getContent(), $author);

            $this->addFlash('success', 'Post publié');

            return $this->redirectToRoute('home');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}/edit', name: 'post_edit', methods: ['GET', 'POST'])]
    public function edit(Post $post, Request $request): Response
    {
        $this->denyUnlessOwner($post);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->updatePost($post);

            $this->addFlash('success', 'Post modifié');

            return $this->redirectToRoute('home');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}/delete', name: 'post_delete', methods: ['POST'])]
    public function delete(Post $post, Request $request): Response
    {
        $this->denyUnlessOwner($post);

        if ($this->isCsrfTokenValid('delete_post_' . $post->getId(), $request->request->get('_token'))) {
            $this->postService->deletePost($post);
            $this->addFlash('success', 'Post supprimé');
        }

        return $this->redirectToRoute('home');
    }

    private function denyUnlessOwner(Post $post): void
    {
        if ($post->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas modifier ce post.");
        }
    }
}

