<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

#[Route('/book/read', name:'read')]
public function read ( BookRepository $bookRepo):Response
{
   $book=$bookRepo->findAll();
   return $this->render('book/read.html.twig', ['book'=>$book]);
}

#[Route('/book/add', name: 'add')]
public function add(ManagerRegistry $doctrine, Request $request ): Response{
    $em=$doctrine->getManager();
    $book=new Book();

    $form=$this->createForm(BookType::class, $book);

    $form -> handleRequest($request);
    
    if ($form->isSubmitted())
    {
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('read');
    } else {
        return $this->renderForm("book/create.html.twig", ["form" => $form]);
    }
}
}