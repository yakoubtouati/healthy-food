<?php

namespace App\Controller\User\Home;

use App\Form\ProfileFormType;
use App\Form\EditPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    #[Route('/profile/home', name: 'user.home.index',methods:['GET'])]
    public function index(): Response
    {
       
        return $this->render('pages/user/home/index.html.twig');
    }

    #[Route('/profile/edit', name: 'user.home.edit', methods:['GET','PUT'])]
    public function edit(Request $request,EntityManagerInterface $em):Response
    {
        $user=$this->getUser();

        $profileForm=$this->createForm(ProfileFormType::class,$user,[
            "method"=>"PUT"
        ]);

        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid())
        {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','La modification a été fait avec success');

            return $this->redirectToRoute('user.home.index');

        }
        
        return $this->render('pages/user/home/edit.html.twig',[
            "profileForm"=>$profileForm->createView()
        ]);
    }

    #[Route('/profile/edit/password', name: 'user.home.edit.password', methods:['GET','PUT'])]
    public function editPassword(Request $request,UserPasswordHasherInterface $hasher,EntityManagerInterface $em):Response
    {
        $user=$this->getUser();

        $passwordForm=$this->createForm(EditPasswordFormType::class,null,[
            "method"=>'PUT'
        ]);

        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid())
        {

           $password=$passwordForm->getData()["password"];
           
           $newPasswordHasher=$hasher->hashPassword($user,$password);

           $user->setPassword($newPasswordHasher);

           $em->persist($user);

           $em->flush();

           $this->addFlash('success','Le mot de passe a été modifier avec success');

           return $this->redirectToRoute('visitor.authentication.login');


        }

        return $this->render('pages/user/home/edit_password.html.twig',[
            "passwordForm"=>$passwordForm
        ]);
    }

    #[Route('/profile/delete', name: 'user.home.delete', methods:['DELETE'])]
    public function delete(Request $request , EntityManagerInterface $em):Response 
    {
       
        
        if ($this->isCsrfTokenValid('delete_user',$request->request->get('csrf_token')))
        {
            $user=$this->getUser();
        
            $em->remove($user);
            $em->flush();

           $this->container->get('security.token_storage')->setToken(null);

            $this->addFlash('success','Votre Compte a été supprimer');

            return $this->redirectToRoute('visitor.authentication.login');


        }
    }


}
