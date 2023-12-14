<?php

namespace App\Controller\Admin\Comment;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/admin/commentaire', name: 'admin.comment.index',methods:['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments=$commentRepository->findAll();
        return $this->render('pages/admin/comment/index.html.twig',[
            "comments"=>$comments
        ]);
    }
    #[Route('/admin/commenatires/{id}/activer', name:'admin.comment.activated', methods:['PUT'])]
    public function activated(Comment $comment,Request $request, EntityManagerInterface $em):Response

    {
        if($this->isCsrfTokenValid('comment_activated_'.$comment->getId(), $request->request->get('csrf_token')))
        {
            if (!$comment->isIsActivated())
            {
                $comment->setIsActivated(true);

                
                $this->addFlash('success','Le commentaire a été activer ');
            }
            else
            {

                $comment->setIsActivated(false);

                $this->addFlash('delete','Le commentaire a été désactiver');
                
            }
            
            $em->persist($comment);

            $em->flush();


        }
        
        return $this->redirectToRoute('admin.comment.index');

    }
}
