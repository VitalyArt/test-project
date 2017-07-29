<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $event = new Event();
        $eventRepository = $this->getDoctrine()->getRepository(Event::class);

        $form = $this
            ->createFormBuilder($event)
            ->add('name')
            ->add('description')
            ->add('status', EntityType::class, [
                'class' => 'AppBundle\Entity\EventStatus',
                'choice_label' => 'name'
            ])
            ->getForm()
        ;

        $eventsList = $eventRepository->getByFilter([]);

        return $this->render('AppBundle:Main:index.html.twig', [
            'form' => $form->createView(),
            'events' => $eventsList
        ]);
    }
}
