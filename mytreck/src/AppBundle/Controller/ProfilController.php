<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfilController extends Controller
{
    /**
     * Show the user.
     */
    public function showProfilAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        return $this->render('/default/profil.html.twig', [
          'user' => $user,
      ]);
    }

    public function modifyProfilAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $username = $request->request->get('username');
        $email = $request->request->get('email');

        if (!empty($username)) {
            $user->setUsername($username);
        }
        if (!empty($email)) {
            $user->setEmail($email);
        }

        $em->persist($user);
        $em->flush();

        $url = $this->generateUrl('profil_user');
        $response = new RedirectResponse($url);

        return $response;
    }
}
