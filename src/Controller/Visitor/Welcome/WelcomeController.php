<?php
namespace App\Controller\Visitor\Welcome;

use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    
    #[Route('/recettes-salées/{id}/contenu', name: 'visitor.recipes.salty_content',methods:['GET'])]
    public function recipeSalty(Recipes $recipesSalty):Response
    {

        return $this->render('pages/visitor/recipes/salty.html.twig',[
            "recipeSalty"=>$recipesSalty
        ]);
    }

    #[Route('/recettes-sucrées/{id}/contenu', name: 'visitor.recipes.sweet_content',methods:['GET'])]
    public function recipeSweet(Recipes $recipesSweet):Response
    {

        return $this->render('pages/visitor/recipes/sweet.html.twig',[
            "recipeSweet"=>$recipesSweet
        ]);
    }

    #[Route('/recettes-bébé/{id}/contenu', name: 'visitor.recipes.baby_content',methods:['GET'])]
    public function recipeBaby(Recipes $recipesBaby):Response
    {

        return $this->render('pages/visitor/recipes/baby.html.twig',[
            "recipeBaby"=>$recipesBaby
        ]);
    }

    #[Route('/recettes-jus/{id}/contenu', name: 'visitor.recipes.jus_content',methods:['GET'])]
    public function recipeJus(Recipes $recipesJus):Response
    {

        return $this->render('pages/visitor/recipes/jus.html.twig',[
            "recipeJus"=>$recipesJus
        ]);
    }




}
