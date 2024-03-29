<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/', name: 'blog_index')]
    public function index(): Response
    {
        return $this->render('hello/index.html.twig');
    }

    #[Route('app_hello_manytimes')]
    public function world(string $name): Response
    {
        return $this->render('hello/world.html.twig', ['name' => $name]);
    }

    #[Route('/hello/{name}/{times}', name: 'app_hello_manytimes', requirements: ['times' => '\d+'])]
    public function manyTimes(string $name, int $times = 3): Response
    {
        if (!(0 !== $times && $times <= 10)) {
            $times = 3;

            return $this->redirectToRoute('app_hello_manytimes', ['name' => $name, 'times' => $times]);
        }

        return $this->render('hello/many_times.html.twig', ['name' => $name, 'times' => $times]);
    }
}
