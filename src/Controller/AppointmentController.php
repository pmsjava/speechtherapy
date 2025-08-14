<?php
namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Service\AppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    public function __construct(private AppointmentService $service) {}

    /** @Route("/", name="home") */
    public function home(): Response { return $this->redirectToRoute('appointment_new'); }

    /** @Route("/appointment/new", name="appointment_new", methods={"GET","POST"}) */
    public function new(Request $request): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($appointment);
            $this->addFlash('success', 'Spotkanie dodane!');
            return $this->redirectToRoute('appointment_new');
        }

        return $this->render('appointment/new.html.twig', ['form' => $form->createView()]);
    }
}
