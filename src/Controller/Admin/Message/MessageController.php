<?php

namespace App\Controller\Admin\Message;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    #[Route('/admin/messages', name: 'admin.message.index')]
    public function index(ContactRepository $contactRepository): Response
    {
        $contact=$contactRepository->findAll();

        return $this->render('pages/admin/message/index.html.twig',[
            'messages'=>$contact
        ]);
    }

    #[Route('/admin/messages/{id}/delete', name: 'admin.message.delete')]
    public function delete(Contact $contact ,Request $request,EntityManagerInterface $em):Response
    {
        if ($this->isCsrfTokenValid('delete_message_'.$contact->getId(),$request->request->get('csrf_token')))
        {
            $em->remove($contact);
            $em->flush();

            $this->addFlash('delete','votre contact a été supprimer avec success');

            return $this->redirectToRoute('admin.message.index');
        }

    }

}
