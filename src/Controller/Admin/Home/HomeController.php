<?php

namespace App\Controller\Admin\Home;

use App\Repository\CommentRepository;
use App\Repository\ContactRepository;
use App\Repository\RecipesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/admin/home', name: 'admin.home.index',methods:['GET'])]
    public function index(
        CommentRepository $commentRepository ,
        RecipesRepository $recipesRepository ,
        UserRepository $userRepository ,
        ContactRepository $contactRepository
        ): Response
    {

        $user= $userRepository->findAll();
        $comment=$commentRepository->findAll();
        $recipes=$recipesRepository->findAll();
        $contact=$contactRepository->findAll();
       
        return $this->render('pages/admin/home/index.html.twig',[
            "user"=>$user,
            "recipes"=>$recipes,
            "contact"=>$contact,
            "comment"=>$comment
        ]);
    }
}
