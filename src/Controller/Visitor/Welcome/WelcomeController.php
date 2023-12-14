<?php
namespace App\Controller\Visitor\Welcome;

use App\Entity\Comment;
use App\Entity\Recipes;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class WelcomeController extends AbstractController
{
    #[Route('/', name: 'visitor.welcome.index',methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/welcome/index.html.twig');
    }


    #[Route('/recettes-salées', name: 'visitor.recipes.salty',methods:['GET'])]
    public function salty(RecipesRepository $recipesRepository):Response
    {

        $recipesSalty=$recipesRepository->findBy(['isPublished'=>true,'category'=>'recettes salées']);
        
        return $this->render('pages/visitor/welcome/salty.html.twig',[
            'recipesSalty'=>$recipesSalty
        ]);
    }

    #[Route('/recettes-sucrées', name: 'visitor.recipes.sweet',methods:['GET'])]
    public function sweet(RecipesRepository $recipesRepository):Response
    {
        
        $recipesSweet=$recipesRepository->findBy(["isPublished"=>true,"category"=>'recettes sucrées']);

        return $this->render('pages/visitor/welcome/sweet.html.twig',[
            "recipesSweet"=>$recipesSweet
        ]);
    }


    #[Route('/recettes-bébé', name: 'visitor.recipes.baby',methods:['GET'])]
    public function baby(RecipesRepository $recipesRepository):Response
    {
        $recipesBaby=$recipesRepository->findBy(["isPublished"=>true,"category"=>'recettes bébé']);
        
        return $this->render('pages/visitor/welcome/baby.html.twig',[
            'recipesBaby'=>$recipesBaby
        ]);
    
    }
    #[Route('/recettes-jus-nature', name: 'visitor.recipes.jus',methods:['GET'])]
    public function jus(RecipesRepository $recipesRepository):Response
    {
        
        $recipesJus=$recipesRepository->findBy(["isPublished"=>true,"category"=>'jus naturel']);

        return $this->render('pages/visitor/welcome/jus.html.twig',[
            "recipesJus"=>$recipesJus
        ]);
    }
    
    #[Route('/recettes-salées/{id}/contenu', name: 'visitor.recipes.salty_content',methods:['GET','POST'])]
    public function recipeSalty(Recipes $recipesSalty,Request $request,EntityManagerInterface $em):Response
    {
        

        $comment= new Comment();

        $commentForm=$this->createForm(CommentFormType::class,$comment);

        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setRecipes($recipesSalty);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success','le commentaire a été envoyer');

            return $this->redirectToRoute('visitor.recipes.salty_content',[
                'id'=>$recipesSalty->getId(),
                
            ]);
        }

        return $this->render('pages/visitor/recipes/salty.html.twig',[
            "recipeSalty"=>$recipesSalty,
            "commentForm"=>$commentForm->createView(),
            
        ]);
    }

    #[Route('/recettes-sucrées/{id}/contenu', name: 'visitor.recipes.sweet_content',methods:['GET','POST'])]
    public function recipeSweet(Recipes $recipesSweet,Request $request,EntityManagerInterface $em):Response
    {
        $comment=new Comment();

        $commentForm=$this->createForm(CommentFormType::class,$comment);
        
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setRecipes($recipesSweet);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success','le commentaire a été envoyer');

            return $this->redirectToRoute('visitor.recipes.sweet_content',[
                'id'=>$recipesSweet->getId(),
                
            ]);
        }



        return $this->render('pages/visitor/recipes/sweet.html.twig',[
            "recipeSweet"=>$recipesSweet,
            "commentForm"=>$commentForm->createView(),
        ]);
    }

    #[Route('/recettes-bébé/{id}/contenu', name: 'visitor.recipes.baby_content',methods:['GET','POST'])]
    public function recipeBaby(Recipes $recipesBaby,Request $request,EntityManagerInterface $em):Response
    {
        $comment= new Comment();

        $commentForm=$this->createForm(CommentFormType::class,$comment);

        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setRecipes($recipesBaby);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success','le commentaire a été envoyer');

            return $this->redirectToRoute('visitor.recipes.baby_content',[
                'id'=>$recipesBaby->getId(),
                
                
            ]);
        }

        return $this->render('pages/visitor/recipes/baby.html.twig',[
            "recipeBaby"=>$recipesBaby,
            "commentForm"=>$commentForm->createView(),
        ]);
    }

    #[Route('/recettes-jus/{id}/contenu', name: 'visitor.recipes.jus_content',methods:['GET','POST'])]
    public function recipeJus(Recipes $recipesJus,Request $request ,EntityManagerInterface $em):Response
    {
        $comment= new Comment();

        $commentForm=$this->createForm(CommentFormType::class,$comment);

        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setRecipes($recipesJus);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success','le commentaire a été envoyer');

            return $this->redirectToRoute('visitor.recipes.jus_content',[
                'id'=>$recipesJus->getId(),
                
            ]);
        }

        return $this->render('pages/visitor/recipes/jus.html.twig',[
            "recipeJus"=>$recipesJus,
            "commentForm"=>$commentForm->createView()
        ]);
    }




}
