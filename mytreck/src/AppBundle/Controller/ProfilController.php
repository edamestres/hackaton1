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

class ProfilController extends Controller
{
    /**
     * Show the user
     */
    public function showProfilAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Must point to composer's autoload file.
      
      
      // Language of data (try your own language here!):
      $lang = 'fr';

      // Units (can be 'metric' or 'imperial' [default]):
      $units = 'metric';

      // Create OpenWeatherMap object. 
      // Don't use caching (take a look into Examples/Cache.php to see how it works).
      $owm = new OpenWeatherMap('2553ef1ea9863b1340bddf742eacb447');

      try {
        $weather = $owm->getWeather('Chartres', $units, $lang);
      } catch(OWMException $e) {
        echo 'OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
      } catch(\Exception $e) {
        echo 'General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
      }

      echo $weather->temperature;
            

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