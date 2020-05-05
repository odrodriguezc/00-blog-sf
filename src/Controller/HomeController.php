<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

class HomeController
{
    /**
     * @Route("/test", name="test")
     */
    public function test(EntityManagerInterface $em, PostRepository $repository)
    {
        $post = $repository->find(1);

        $post->setTitle('New title');
        $em->flush();
        dd($post);
    }


    /**
     * @Route("/", name="home") 
     */
    public function index(Environment $twig): Response
    {
        $html = $twig->render('home.html.twig');
        return new Response($html);
    }

    /**
     * @Route("/hello/{name?World}", name="hello")
     */
    public function hello(string $name, Environment $twig, PDO $db): Response
    {
        $db = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '12345', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $data = $db->query("SELECT * FROM post")->fetchAll(PDO::FETCH_ASSOC);


        $prenoms = ['Maria', 'Jose', 'Jesus', 'Pedro'];
        $formateur = ['prenom' => 'Vs', 'nom' => 'Code'];

        $eleves = [
            ['prenom' => 'Intel', 'nom' => 'Inside', 'age' => 44],
            ['prenom' => 'Hexa', 'nom' => 'Spray', 'age' => 44],
            ['prenom' => 'Nvidia', 'nom' => 'RTX', 'age' => 44]
        ];

        $html = $twig->render('hello.html.twig', [
            'prenom' => $name,
            'prenoms' => $prenoms,
            'formateur' => $formateur,
            'eleves' => $eleves,
            'articles' => $data
        ]);
        return new Response($html);
    }
}
