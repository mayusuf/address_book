<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use AppBundle\Entity\ContactList;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Service\FileUploader;

class ContactAddController extends Controller
{
    /**
     * @Route("/add_form", name="addContactpage")
     */
    public function addFormAction(Request $request)
    {
        try {
            $contactList = new ContactList();
        
            $form = $this->createForm(ContactType::class, $contactList, [
                'action' => 'save_contact',
                'method' => 'POST',
            ]);

            return $this->render('contactlist/add.html.twig', [
                'form' => $form->createView(),
            ]);
        }catch (\Exception $e) {
            error_log($e->getMessage());
        }
        
    }

    /**
     * @Route("/save_contact", name="saveContact")
     */
    public function saveContactAction(Request $request,FileUploader $fileUploader)
    {
        
        try {
            $contactList = new ContactList();

            $form = $this->createForm(ContactType::class, $contactList);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $contactList = $form->getData();
                

                $pictureFile = $form->get('picture')->getData();

                if ($pictureFile) {
                    $pictureFileName = $fileUploader->upload($pictureFile);
                    $contactList->setPicture($pictureFileName);
                }
                // ... perform some action, such as saving the task to the database
                // for example, if Task is a Doctrine entity, save it!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contactList);
                $entityManager->flush();

                return $this->redirectToRoute('homepage');
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        
    }
    
}
