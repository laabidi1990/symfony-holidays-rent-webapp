<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page}", name="admin_bookings_index", requirements={"page" : "\d+"})
     */
    public function index($page = 1, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
                    ->setCurrentPage($page);
                    
        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher la formulaire d'édition des réservations pour l'admin
     * 
     * @Route("/admin/booking/{id}/edit", name="admin_bookings_edit")
     *
     * @param Booking $comment
     * @return void
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking, ['validation_groups' => ['default']]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //$booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration());
            $booking->setAmount(0); //PreUpdate in entity

            //$manager->persist($booking);
            $manager->flush();

            $this->addFlash('success', "La réservation n° <strong>{$booking->getId()}</strong> a bien été modifiée");

            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprimer une réservation par l'admin
     * 
     * @Route("/admin/booking/{id}/delete", name="admin_bookings_delete")
     *
     * @param Comment $ad
     * @param EntityManagerDecorator $manager
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager)
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation établie par <strong>{$booking->getBooker()->getFullName()}</strong> pour l'annonce <strong>{$booking->getAd()->getTitle()}</strong> à été suprimer avec succés"
        );

        return $this->redirectToRoute('admin_bookings_index');
    }
}
