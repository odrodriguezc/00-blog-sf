<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\EventDispatcher\EventDispatcherInterface;

class HomeController
{

    /**
     * @Route("/", name="home") 
     */
    public function index(EventDispatcherInterface $dispatcher, Request $request): Response
    {

        return new Response("Hello World !");
    }
}
