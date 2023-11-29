<?php

namespace App\Controller\Admin\Profile;

use App\Form\ProfileFormType;
use App\Form\EditPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class ProfileController extends AbstractController
{
    #[Route('/admin/profile', name: 'admin.profile.index',methods:['GET'])]
    public function index(): Response
    {
        
        return $this->render('pages/admin/profile/index.html.twig');
    }

    #[Route('/admin/profile/edit', name: 'admin.profile.edit', methods:['GET','PUT'])]
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

            return $this->redirectToRoute('admin.profile.index');

        }
        
        return $this->render('pages/admin/profile/edit.html.twig',[
            "profileForm"=>$profileForm->createView()
        ]);
    }

    #[Route('/admin/profile/edit/password', name: 'admin.profile.edit.password', methods:['GET','PUT'])]
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

        return $this->render('pages/admin/profile/edit_password.html.twig',[
            "passwordForm"=>$passwordForm
        ]);
    }


}
