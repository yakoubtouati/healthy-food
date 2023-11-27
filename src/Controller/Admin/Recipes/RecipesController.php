<?php

namespace App\Controller\Admin\Recipes;


use DateTimeImmutable;
use App\Entity\Recipes;
use App\Form\RecipesFormType;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipesController extends AbstractController
{
    #[Route('/admin/recettes', name: 'admin.recipes.index',methods:['GET'])]
    public function index( RecipesRepository $recipesRepository): Response
    {
        $recipes=$recipesRepository->findAll();

        return $this->render('pages/admin/recipes/index.html.twig',[
            "recipes"=>$recipes
        ]);
    }

    #[Route('/admin/recettes/create' ,name:'admin.recipes.create',methods:['GET','POST'])]
    public function create(Request $request,EntityManagerInterface $em):Response
    {
        $recipes=new Recipes();

        $formRecipes=$this->createForm(RecipesFormType::class,$recipes);

        $formRecipes->handleRequest($request);

        if ($formRecipes->isSubmitted() && $formRecipes->isValid())
        {
            $adminPublished=$this->getUser();

            $recipes->setUser($adminPublished);

            $em->persist($recipes);

            $em->flush();

            $this->addFlash('success','La recette "'.$recipes->getTitle().'"  a été ajoutée avec success');

            return $this->redirectToRoute('admin.recipes.index');

        }

        return $this->render('pages/admin/recipes/create.html.twig',[
            "formRecipes"=>$formRecipes->createView()
        ]);
    }

    #[Route('/admin/recettes/{id}/publier', name:'admin.recipes.published', methods:['PUT'])]
    public function published(Recipes $recipes,Request $request, EntityManagerInterface $em):Response

    {
        if($this->isCsrfTokenValid('recipe_published_'.$recipes->getId(), $request->request->get('csrf_token')))
        {
            if (!$recipes->isIsPublished())
            {
                $recipes->setIsPublished(true);

                $recipes->setPublishedAt(new DateTimeImmutable());
                
                $this->addFlash('success','La recette "'.$recipes->getTitle() .'" a été publiée ');
            }
            else
            {

                $recipes->setIsPublished(false);

                $this->addFlash('delete','La recette "'.$recipes->getTitle() .'" a été retirée');
                
            }
            
            $em->persist($recipes);

            $em->flush();


        }
        
        return $this->redirectToRoute('admin.recipes.index');

    }

    #[Route('/admin/recettes/{id}/contenu', name:'admin.recipes.content', methods:['GET'])]
    public function content(Recipes $recipes):Response
    {
      
        return $this->render('pages/admin/recipes/content.html.twig',[
            'content'=>$recipes
        ]);
    }

    #[Route('/admin/recettes/{id}/edit', name:'admin.recipes.edit', methods:['GET','PUT'])]
    public function edit(Recipes $recipes,Request $request,EntityManagerInterface $em):Response
    {

        $formRecipes=$this->createForm(RecipesFormType::class,$recipes,[
            "method"=>'PUT'
        ]);

        $formRecipes->handleRequest($request);

        if ($formRecipes->isSubmitted() && $formRecipes->isValid())
        {
            $adminPublished=$this->getUser();

            $recipes->setUser($adminPublished);

            $em->persist($recipes);

            $em->flush();

            $this->addFlash('success','la recette "'.$recipes->getTitle() .'" a été modifiée avec success');

            return $this->redirectToRoute('admin.recipes.index');

        }

        return $this->render('pages/admin/recipes/edit.html.twig',[
            "formRecipes"=>$formRecipes->createView(),
            "recipes"=>$recipes
        ]);

        
    }

    #[Route('/admin/recettes/{id}/delete', name:'admin.recipes.delete', methods:['GET','DELETE'])]
    public function delete(Recipes $recipes,Request $request, EntityManagerInterface $em):Response
    {
        if ($this->isCsrfTokenValid('recipes_delete_'.$recipes->getId(),$request->request->get('csrf_token')))
        {
            $em->remove($recipes);
            $em->flush();
            $this->addFlash('delete','La recette "'.$recipes->getTitle().'" a été supprimer');

        }
        return $this->redirectToRoute('admin.recipes.index');

    }

}
