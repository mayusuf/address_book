<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use AppBundle\Entity\ContactList;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Service\FileUploader;

class ContactEditController extends Controller
{
    /**
     * @Route("/edit_form/{id}", name="editContactpage")
     */
    public function editFormAction(Request $request,FileUploader $fileUploader)
    {
        try{

            $id = $request->get('id');
        
            $singleContact = $this->getDoctrine()
                ->getRepository(ContactList::class)
                ->find($id);

            $form = $this->createForm(ContactType::class, $singleContact, [
                'action' => '/update_contact/'.$id,
                'method' => 'POST',
            ]);

            return $this->render('contactlist/edit.html.twig', [
                'form' => $form->createView(),
                'pic_path' => $fileUploader->getPicDirectory()
            ]);
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        
    }

    /**
     * @Route("/update_contact/{id}", name="updateContactpage")
     */
    public function updateContactAction(Request $request,FileUploader $fileUploader)
    {
        
        try {
            $id = $request->get('id');

            $entityManager = $this->getDoctrine()->getManager();
            $contact = $entityManager->getRepository(ContactList::class)->find($id);

            $form = $this->createForm(ContactType::class, $contact);
            $form->handleRequest($request);
     
      
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                                

                $pictureFile = $form->get('picture')->getData();

                if ($pictureFile) {
                    $pictureFileName = $fileUploader->upload($pictureFile);
                    $contact->setPicture($pictureFileName);
                }

                //perform some action, such as saving the task to the database
               
                $entityManager->flush();
                
                return $this->redirectToRoute('homepage');
            }
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

    }
    
}
