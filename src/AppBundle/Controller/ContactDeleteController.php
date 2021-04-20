<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ContactList;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactDeleteController extends Controller
{
    private $picDir;

    public function __construct($upload_pictures_directory)
    {
        $this->picDir = $upload_pictures_directory;
    }

    /**
     * @Route("/delete/{id}", name="deletePage")
     */
    public function deleteAction(Request $request)
    {
        
        try{
            $id = $request->get('id');

            $entityManager = $this->getDoctrine()->getManager();
            $singleContactDelete = $entityManager->getReference(ContactList::class,$id);
            $filename = $this->picDir.'/'.$singleContactDelete->getPicture();

            if(file_exists($filename)){
                
                unlink($filename);
                $entityManager->remove($singleContactDelete); 
                $entityManager->flush();
                return $this->redirectToRoute('homepage');
            }
        }
        catch(\Exception $e){
            error_log($e->getMessage());
        }       
        
    }
}
