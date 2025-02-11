<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Form\CommentType;
use App\Repository\DealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentController extends AbstractController
{
    #[Route('/comment/add/{id}', name: 'comment_add', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Request $request, Deal $deal, EntityManagerInterface $entityManager)
    {
        $content = $request->request->get('content');
        $parentId = $request->request->get('parent_id');

        if (empty($content)) {
            $this->addFlash('danger', 'Le commentaire ne peut pas être vide.');
            return $this->redirectToRoute('deal_show', ['id' => $deal->getId()]);
        }

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setUser($this->getUser());
        $comment->setDeal($deal);

        if ($parentId) {
            $parentComment = $entityManager->getRepository(Comment::class)->find($parentId);
            if ($parentComment) {
                $comment->setParent($parentComment);
            }
            $this->addFlash('success', 'Votre réponse a été ajoutée avec succès.');
        }else{
            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès.');
        }

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('deal_show', [
            'id' => $deal->getId(),
            'comment' => $comment->getId()
        ]);
    }

    #[Route('/comment/delete/{id}', name: 'comment_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Comment $comment, EntityManagerInterface $entityManager)
    {
        $dealId = $comment->getDeal()->getId();

        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été supprimé avec succès.');

        return $this->redirectToRoute('deal_show', ['id' => $dealId]);
    }

    #[Route('/comment/edit/{id}', name: 'comment_edit', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Request $request, int $id, EntityManagerInterface $em, CsrfTokenManagerInterface $csrfTokenManager): JsonResponse
    {
        $csrfToken = $request->headers->get('X-CSRF-TOKEN');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('edit_comment', $csrfToken))) {
            return new JsonResponse(['success' => false, 'message' => 'Token CSRF invalide'], 403);
        }

        $comment = $em->getRepository(Comment::class)->find($id);

        if (!$comment) {
            return new JsonResponse(['success' => false, 'message' => 'Commentaire non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['content'])) {
            return new JsonResponse(['success' => false, 'message' => 'Contenu manquant'], 400);
        }

        $comment->setContent($data['content']);
        $em->flush(); 

        return new JsonResponse(['success' => true, 'message' => 'Commentaire mis à jour avec succès']);
    }
}
