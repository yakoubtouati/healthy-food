<?php

namespace App\Controller\Visitor\Contact;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'visitor.contact.create',methods:['GET','POST'])]
    public function index(Request $request, EntityManagerInterface $em, SendEmailService $sendEmailService): Response
    {
        $contact=new Contact();

        $contactForm=$this->createForm(ContactFormType::class,$contact);

        $contactForm->handleRequest($request);

        if( $contactForm->isSubmitted() && $contactForm->isValid())
        {
           

            $em->persist($contact);

            $em->flush();


             $sendEmailService->send([
                'sender_email' => 'healthy-food@gmail.com',
                "sender_name"  => "Healthy-food",
                "recipient_email" => "healthy-food@gmail.com",
                "subject" => "Un message reçu sur healthy-food",
                "html_template" => "emails/contact.html.twig",
                "context"   => [
                    "contact_first_name"    => $contact->getFirstName(),
                    "contact_last_name"     => $contact->getLastName(),
                    "contact_email"         => $contact->getEmail(),
                    "contact_phone"         => $contact->getPhone(),
                    "contact_message"       => $contact->getContent(),
                ]
            ]);


            $this->addFlash('success','Le message a été bien envoyer');

            return $this->redirectToRoute('visitor.contact.create');
            
            dd($contactForm->getData());
        }



        return $this->render('pages/visitor/contact/index.html.twig',[
            "contactForm"=>$contactForm->createView()
        ]);
    }
}
