<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    public function eventsAction(Request $request)
    {
        $eventRepository = $this->getDoctrine()->getRepository(Event::class);

        $dateFrom = (int)$request->get('start');
        $dateTo = (int)$request->get('end');

        $dateTimeFrom = new \DateTime();
        $dateTimeFrom->setTimestamp($dateFrom);

        $dateTimeTo = new \DateTime();
        $dateTimeTo->setTimestamp($dateTo);

        /** @var Event [] $events */
        $events = $eventRepository->getByFilter([
            'from' => $dateTimeFrom,
            'to' => $dateTimeTo,
        ]);
        
        $data = [];
        
        foreach ($events as $event) {
            $data[] = [
                'id'     => $event->getId(),
                'title'  => $event->getName(),
                'date'   => $event->getDate()->format('Y-m-d'),
                'author' => [
                    'id'    => $event->getAuthor()->getId(),
                    'name'  => $event->getAuthor()->getUsername(),
                    'email' => $event->getAuthor()->getEmail(),
                ]
            ];
        }

        return new JsonResponse($data);
    }

    public function eventEditAction(Request $request)
    {
        $success = false;
        $id = (int)$request->get('id');

        if ($request->get('year') && $request->get('month') && $request->get('day')) {
            $year = (int)$request->get('year');
            $month = (int)$request->get('month');
            $day = (int)$request->get('day');

            $month++;

            $dateTime = new \DateTime();
            $dateTime->setDate($year, $month, $day);

            $em = $this->getDoctrine()->getManager();

            /** @var Event $event */
            $event = $em->find(Event::class, $id);
            $event->setDate($dateTime);

            $em->flush();

            $success = true;
        }

        return new JsonResponse([
            'success' => $success
        ]);
    }
}
