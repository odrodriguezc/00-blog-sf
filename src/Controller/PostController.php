<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostController extends AbstractController
{
    /**
     * @Route("/blog", name="post_index")
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC'], 10);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/blog/{id}", name="post_show")
     */
    public function show(Post $post)
    {
        //$post = $postRepository->find($id);

        // if (!$post) {
        //     throw $this->createNotFoundException();
        // }

        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/admin/create", name="post_create")
     */
    public function create(FormFactoryInterface $factory, Request $request, EntityManagerInterface $em, UrlGeneratorInterface $generator)
    {

        //heritage 
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            //recuperer le form
            /** @var Post */
            $post = $form->getData();
            $post->setCreatedAt(new DateTime());

            //enregistrer
            $em->persist($post);
            $em->flush();

            //1.methode manuel
            // $response = new Response();
            // $response->setStatusCode(302);
            // $response->headers->set('Location', "/blog/{$post->getId()}");

            //2. methode heritage
            //creer l'url
            // $url = $generator->generate('post_show', [
            //     'id' => $post->getId()
            // ]);
            //$response = new RedirectResponse($url);

            //3. methode actuel
            return  $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/create.html.twig', [
            'postForm' => $form->createView()
        ]);
    }


    /**
     * @Route("admin/edit/{id}", name="post_edit")
     */
    public function edit(Post $post, $id, PostRepository $postRepository, Request $request, EntityManagerInterface $em)
    {


        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'postForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="post_delete")
     */
    public function delete(Post $post, EntityManagerInterface $em)
    {

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('post_index');
    }
}
