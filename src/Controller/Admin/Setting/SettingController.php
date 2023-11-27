<?php

namespace App\Controller\Admin\Setting;

use App\Entity\Setting;
use App\Form\SettingFormType;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController
{
    #[Route('/admin/setting', name: 'admin.setting.index',methods:['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {

        $setting=$settingRepository->find(1);
     
        return $this->render('pages/admin/setting/index.html.twig',[
            "setting"=>$setting
        ]);
    }

    #[Route('/admin/setting/{id}/edit', name: 'admin.setting.edit',methods:['GET','POST'])]
    public function edit(Setting $setting,Request $request,EntityManagerInterface $em):Response
    {
        
        $settingForm=$this->createForm(SettingFormType::class,$setting);
        
        $settingForm->handleRequest($request);

        if($settingForm->isSubmitted() && $settingForm->isValid())
        {
            $em->persist($setting);
            $em->flush();
            $this->addFlash('success','Les modifications ont été fait avec success');
            return $this->redirectToRoute('admin.setting.index');
        }

    
        return $this->render('pages/admin/setting/edit.html.twig',[
            "settingForm"=>$settingForm->createView()
        ]);
    }

}
