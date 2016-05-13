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
use AppBundle\Entity\Ville;
use AppBundle\Entity\User;

class AccueilController extends Controller
{
	public function showAccueilAction ()
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		$tabville = $em->getRepository('AppBundle:Ville')->findOneByIdUser($user->getId());
		// Must point to composer's autoload file.
		$ville = $tabville->getVille();
	      if (empty($ville))
	      {
	      	$ville="chartres";
	      }  
	
		// Language of data (try your own language here!):
		$lang = 'fr';

		// Units (can be 'metric' or 'imperial' [default]):
		$units = 'metric';

		// Create OpenWeatherMap object. 
		// Don't use caching (take a look into Examples/Cache.php to see how it works).
		$owm = new OpenWeatherMap('2553ef1ea9863b1340bddf742eacb447');
		
		try {
			$weather = $owm->getWeather( $ville  , $units, $lang);
		} 
		catch(OWMException $e) {
		    echo 'OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
		} 
		catch(\Exception $e) {
		    echo 'General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
		}
			
		            var_dump($weather);

			return $this->render('/default/accueil.html.twig', array(
          'user' => $user,
          'weather' => $weather,
      ));
	}

	public function modifyAccueilAction (Request $request)
	{
		$em = $this->getDoctrine()->getManager();
    	$user = $this->container->get('security.context')->getToken()->getUser();

    	$tabville = $em->getRepository('AppBundle:Ville')->findOneByIdUser($user->getId());

    	$ville = $request->request->get('ville');
    	 if (empty($ville))
	      {
	      	$ville="chartres";
	      }
    	$tabville->setIdUser($user->getId());
    	$tabville->setVille($ville);

    	$em->persist($tabville);
        $em->flush();

	 	$url = $this -> generateUrl('view_accueil');
        $response = new RedirectResponse($url);
        return $response;
	}

}
