<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{

    /**
     * @Route("/test/{id}", name="test")
     */
    public function test(EntityManagerInterface $em, PostRepository $repository, CategoryRepository $categoryRepositoy, string $id)
    {

        $category = $categoryRepositoy->find($id);

        if (!$category) {

            throw $this->createNotFoundException('tapez une bonne category idiot');
        }

        return $this->render(
            'category.html.twig',
            ['category' => $category]
        );
    }


    /**
     * @Route("/", name="home") 
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/hello/{name?World}", name="hello")
     */
    public function hello(string $name,  PDO $db): Response
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

        return $this->render('hello.html.twig', [
            'prenom' => $name,
            'prenoms' => $prenoms,
            'formateur' => $formateur,
            'eleves' => $eleves,
            'articles' => $data
        ]);
    }
}
