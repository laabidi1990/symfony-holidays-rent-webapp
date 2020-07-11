<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * Permet d'afficher la liste des annonces pour l'administrateur
     * 
     * @Route("/admin/ads/{page}", name="admin_ads_index", requirements={"page": "\d+"})
     */
    public function index($page = 1, PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
                    ->setCurrentPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher la formulaire d'édition pour l'admin
     * 
     * @Route("/admin/ad/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return void
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success', "L'annonce a bien été modifié.");
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }


    /**
     * Supprimer une annonce par l'admin
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param EntityManagerDecorator $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        if(count($ad->getBookings()) > 0) {
            $this->addFlash('warning', "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle a déja des réservations");
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> à été suprimer avec succés");
        }

        return $this->redirectToRoute('admin_ads_index');

    }

}
