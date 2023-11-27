<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'admin.user.index', methods:['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $user=$userRepository->findAll();

        return $this->render('pages/admin/user/index.html.twig',[
            'users'=>$user
        ]);
    }
    #[Route('/admin/user/{id}/edit', name: 'admin.user.edit',methods:['GET','PUT'])]
    public function edit(User $user,Request $request,EntityManagerInterface $em):Response
    {

        $rolesForm=$this->createForm(UserFormType::class,$user,[
            "method"=>"PUT"
        ]);

        $rolesForm->handleRequest($request);

        if($rolesForm->isSubmitted() && $rolesForm->isValid())
        {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Les roles de '. $user->getFirstName().' '. $user->getlastName() .' ont été modifier');
            return $this->redirectToRoute('admin.user.index');

        }

        
        return $this->render('pages/admin/user/edit.html.twig',[
            "rolesForm"=>$rolesForm->createView(),
            "user"=>$user
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'admin.user.delete',methods:['DELETE'])]
    public function delete(User $user,Request $request,EntityManagerInterface $em):Response
    {
        if ($this->isCsrfTokenValid('delete_role_'.$user->getId(),$request->request->get('csrf_token')))
        {

            $recipes=$user->getRecipes();
            
            foreach ($recipes as $recipe) {
                
                $recipe->setUser(null);
            
            }

            $em->remove($user);

            $em->flush();

            $this->addFlash('delete','le compte a été supprimer avec success');

            return $this->redirectToRoute('admin.user.index');

        }
    }

}
