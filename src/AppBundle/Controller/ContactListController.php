<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ContactList;
use AppBundle\Service\WorldCountries;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Intl\Intl;
class ContactListController extends Controller
{
    private $picDir;

    public function __construct($picDir)
    {
        $this->picDir = $picDir;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        
        try{
            $worldCountries = new WorldCountries();

            $contactList = $this->getDoctrine()
                ->getRepository(ContactList::class)
                ->findBy(array(), array('id' => 'DESC'));

            return $this->render('contactlist/index.html.twig', [
                    'data' => $contactList,
                    'picDir' => $this->picDir,
                    'allCountries' => $worldCountries->allCountries(),
                ]);
        }
        catch(\Exception $e){
            error_log($e->getMessage());
        }
    }
}
