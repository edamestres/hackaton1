<?php

namespace AppBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
use Vendor\autoload;
use AppBundle\Entity\User;
use AppBundle\Entity\Ville;

class ProfilController extends Controller
{
    /**
     * Show the user
     */
    public function showProfilAction()
    {
    	$em = $this->getDoctrine()->getManager();
      $user = $this->container->get('security.context')->getToken()->getUser();

      return $this->render('/default/profil.html.twig', array(
          'user' => $user
      ));
    }
     public function modifyProfilAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
      $user = $this->container->get('security.context')->getToken()->getUser();
      $username = $request->request->get('username');
      $email = $request->request->get('email');

    	if (!empty($username))
    	{
    		$user->setUsername($username);
   		}
   		if(!empty($email))
   		{
   			$user->setEmail($email);	
   		}
   		
        $em->persist($user);
        $em->flush();

       	$url = $this -> generateUrl('profil_user');
       	$response = new RedirectResponse($url);
       	return $response;
    }
}