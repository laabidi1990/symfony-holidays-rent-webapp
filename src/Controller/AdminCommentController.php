<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page}", name="admin_comments_index", requirements={"page": "\d+"})
     */
    public function index(PaginationService $pagination, $page = 1)
    {
        $pagination->setEntityClass(Comment::class)
                    ->setCurrentPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

      /**
     * Permet d'afficher la formulaire d'édition pour l'admin
     * 
     * @Route("/admin/comment/{id}/edit", name="admin_comments_edit")
     *
     * @param Comment $comment
     * @return void
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', "Le commentaire a bien été modifié.");

            return $this->redirectToRoute('admin_comments_index');
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Supprimer un commentaire par l'admin
     * 
     * @Route("/admin/comment/{id}/delete", name="admin_comments_delete")
     *
     * @param Comment $ad
     * @param EntityManagerDecorator $manager
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getAuthor()->getFullName()}</strong> de'annonce <strong>{$comment->getAd()->getTitle()}</strong> à été suprimer avec succés"
        );

        return $this->redirectToRoute('admin_comments_index');

    }
}
