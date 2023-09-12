<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\Vehicle;
use App\Form\Transformer\ArrayDateTransformer;
use App\Form\Reservation\PhaseIIIType;
use App\Form\Reservation\PhaseIIType;
use App\Form\Reservation\PhaseIType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */

/**
 *
 */
class ReservationController extends AbstractController
{


    /**
     * @param Request $request
     * @return Response
     */
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/reservation', name: 'app_reservation', methods: ['GET'])]
    public function showForm(Request $request)
    {
        $trip = new Trip();
        $form = $this->createForm(PhaseIType::class, $trip);


        return $this->renderForm('reservation/new_phaseI.html.twig', [
            'form' => $form,
            'trip' => $trip,
            'step' => PhaseIType::PHASE_STEP
        ]);
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    #[Route('/reservation', name: 'app_reservation_phaseI', methods: ['POST'])]
    public function saveForm(Request $request, EntityManagerInterface $entityManager)
    {
        $trip = new Trip();

        $form = $this->createForm(PhaseIType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formII = $this->createForm(PhaseIIType::class, $trip,
                [
                    'action' => $this->generateUrl('app_reservation_phaseII'),
                    'rdate' => $trip->getDate()
                ]
            );

            return $this->renderForm('reservation/new_phaseII.html.twig', [
                'form' => $formII,
                'trip' => $trip,
                'step' => PhaseIIType::PHASE_STEP


            ]);
        }

        $this->addFlash('warning', 'reservation.warn.phaseI');
        return $this->redirect('/reservation');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route('/reservation/selectVehicle', name: 'app_reservation_phaseII', methods: ['POST'])]
    public function saveFormPhaseII(Request $request)
    {
        $trip = new Trip();

        $transformer = new ArrayDateTransformer();

        $form = $this->createForm(PhaseIIType::class, $trip, [
            'rdate' => $transformer->transform($request->request->all()['phase_ii']['Date']
            )]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $formIII = $this->createForm(PhaseIIIType::class, $trip,
                ['action' => $this->generateUrl('app_reservation_phaseIII'
                ),
                    'rdate' => $trip->getDate(),
                    'vehicle' => $trip->getVehicle()

                ]
            );


            return $this->renderForm('reservation/new_phaseIII.html.twig', [
                'form' => $formIII,
                'trip' => $trip,
                'step' => PhaseIIIType::PHASE_STEP
            ]);
        }
        $this->addFlash('warning', 'reservation.warn.phaseII');

        return $this->redirect('/reservation');
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param VehicleRepository $vehicleRepository
     * @return RedirectResponse
     */
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param VehicleRepository $vehicleRepository
     * @return RedirectResponse
     */
    #[Route('/reservation/selectDriver', name: 'app_reservation_phaseIII', methods: ['POST'])]
    public function saveFormPhaseIII(Request $request, EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository)
    {
        $trip = new Trip();

        $transformer = new ArrayDateTransformer();


        $form = $this->createForm(PhaseIIIType::class, $trip, [
           'rdate' => $transformer->transform($request->request->all()['phase_iii']['Date']),
           'vehicle' => $entityManager->find(Vehicle::class, $request->request->all()['phase_iii']['Vehicle'])
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('warning', 'reservation.msg.ok');
            return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);

// or summary page
//            return $this->render('trip/new_ok.html.twig', [
//                'data_trip' => $trip,
//            ]);
        }
        $this->addFlash('warning', 'reservation.warn.phaseIII');
        return $this->redirect('/reservation');
    }

}
