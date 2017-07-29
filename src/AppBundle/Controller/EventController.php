<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Form\EventType;
use Proxies\__CG__\AppBundle\Entity\EventStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function createAction(Request $request)
    {
        $eventRepository = $this->getDoctrine()->getRepository(Event::class);

        $form = $this
            ->createFormBuilder(new Event())
            ->add('name')
            ->add('description')
            ->add('status', EntityType::class, [
                'class' => EventStatus::class,
                'choice_label' => 'name'
            ])
            ->getForm()
        ;

        $eventsList = $eventRepository->getByFilter();

        return $this->render('AppBundle:Event:create.html.twig', [
            'form' => $form->createView(),
            'events' => $eventsList
        ]);
    }

    public function myAction(Request $request)
    {
        $eventRepository = $this->getDoctrine()->getRepository(Event::class);

        /** @var User $user */
        $user = $this->getUser();

        $eventFilter = [
            'author' => $user->getId()
        ];

        return $this->render('AppBundle:Event:my.html.twig', [
            'events' => $eventRepository->getByFilter($eventFilter)
        ]);
    }

    public function viewAction(Request $request)
    {
        $eventRepository = $this->getDoctrine()->getRepository(Event::class);

        return $this->render('AppBundle:Event:view.html.twig', [
            'event' => $eventRepository->find($request->get('id'))
        ]);
    }

    public function editAction(Request $request)
    {
        $eventRepository = $this->getDoctrine()->getRepository(Event::class);
        $event = $eventRepository->find($request->get('id'));

        $form = $this->createFormBuilder($event)
            ->add('name')
            ->add('date')
            ->add('color')
            ->add('description')
            ->add('status', EntityType::class, [
                'class' => EventStatus::class,
                'choice_label' => 'name'
            ])
            ->getForm()
        ;

        //var_dump($form->isSubmitted());
        //var_dump($form->getData());
        //exit;
        if ($form->isSubmitted()/* && $form->isValid()*/) {
            print_r('test');
            exit;
        }

        return $this->render('AppBundle:Event:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
