<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
        'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
        ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
        'taha.hussein@gmail.com', 'nb_books' => 300), 
        );
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/showAuthor.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('author/show/{name}', name: 'show_author')]
    public function show($name)
    {
        return $this->render("author/show.html.twig", ['name'=>($name)]);

    } 
    #[Route('/author/list', name:'list')] 
    public function list():Response
    {
     return $this-> render("author/list.html.twig", ['authors' => $this->authors]);  
    }
    #[Route('/author/details/{id}', name: 'author_details')]
    public function author_details($id):Response
    {
        return $this->render("author/details.html.twig",['id'=>$id, 'authors'=>$this->authors]);
    }
    
    
    #[Route('/author/read', name:'read')]
    public function read(AuthorRepository $authorRepo):Response
    {
       
        $author=$authorRepo->findAll(); 
        return $this->render ("author/read.html.twig",['author'=>$author]);
    }


    #[Route('/author/add', name: 'add')]  
    public function add(ManagerRegistry $doctrine, Request $request) : Response
    {
        $em=$doctrine->getManager();
        $author=new Author();

        $form=$this->createForm(AuthorType::class, $author);

        $form -> handleRequest($request);
        
        if ($form->isSubmitted())
        {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('read');
        } else {
            return $this->renderForm("author/create.html.twig", ["form" => $form]);
        }
        //$author->setUsername('test');
        //$author->setEmail('test@gmail.com');
        
    }
    #[Route('/Author/delete/{id}', name:"delete" )]
     public function delete (ManagerRegistry $doctrine , $id , AuthorRepository $authorRepo  )   
     {
        $em=$doctrine->getManager();
        $authordel =  $authorRepo ->find($id);
        $em->remove($authordel);
        $em->flush();
        return  $this->redirectToRoute("read");


     }
           /********************************************Modifier********************************************************* */

           #[ Route("/author/edit/{id}", name:"author_edit")]
           public function edit(ManagerRegistry $doctrine, Request $request, $id, AuthorRepository $authorRepo): Response
           {
               $em = $doctrine->getManager();
               $author = $authorRepo->find($id);
        
               if (!$author) {
                   throw $this->createNotFoundException('Author not found');
               }
        
               $form = $this->createForm(AuthorType::class, $author);
        
               $form->handleRequest($request);
        
               if ($form->isSubmitted() && $form->isValid()) {
                   // If the form is submitted and valid, update the author in the database
                   $em->flush();
        
                   return $this->redirectToRoute('read'); // Redirect to the author list after updating
               }
        
               return $this->render('author/edit.html.twig', [
                   'form' => $form->createView(),
               ]);
           }

}

