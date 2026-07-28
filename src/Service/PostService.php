<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    private PostRepository $repo;
    private EntityManagerInterface $em;

    public function __construct(PostRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Retourne tous les posts, ordonnés par date
     *
     * @return Post[]
     */
    public function getAllPosts(): array
    {
        return $this->repo->findBy([], ['createdAt' => 'DESC']);
    }

    public function createPost(string $title, string $content): Post
    {
        $post = new Post();
        $post->setTitle($title)
            ->setContent($content)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }
}

