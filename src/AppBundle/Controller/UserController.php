<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function listAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        return $this->render(
            'AppBundle:User:list.html.twig', [
                'users' => $userRepository->findAll()
            ]
        );
    }
}