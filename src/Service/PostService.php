<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
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
     * Retourne les posts d'un auteur, du plus récent au plus ancien
     *
     * @return Post[]
     */
    public function getPostsByAuthor(User $author): array
    {
        return $this->repo->findBy(['author' => $author], ['createdAt' => 'DESC']);
    }

    public function createPost(string $title, string $content, User $author): Post
    {
        $post = new Post();
        $post->setTitle($title)
            ->setContent($content)
            ->setAuthor($author)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    public function updatePost(Post $post): void
    {
        $this->em->flush();
    }

    public function deletePost(Post $post): void
    {
        $this->em->remove($post);
        $this->em->flush();
    }
}

