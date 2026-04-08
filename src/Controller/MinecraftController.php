<?php

namespace App\Controller;

use App\Entity\Minecraft;
use App\Form\MinecraftType;
use App\Repository\MinecraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/minecraft')]
final class MinecraftController extends AbstractController
{
    #[Route(name: 'app_minecraft_index', methods: ['GET'])]
    public function index(MinecraftRepository $minecraftRepository): Response
    {
        return $this->render('minecraft/index.html.twig', [
            'minecrafts' => $minecraftRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_minecraft_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $minecraft = new Minecraft();
        $form = $this->createForm(MinecraftType::class, $minecraft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($minecraft);
            $entityManager->flush();

            return $this->redirectToRoute('app_minecraft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('minecraft/new.html.twig', [
            'minecraft' => $minecraft,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_minecraft_show', methods: ['GET'])]
    public function show(Minecraft $minecraft): Response
    {
        return $this->render('minecraft/show.html.twig', [
            'minecraft' => $minecraft,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_minecraft_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Minecraft $minecraft, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MinecraftType::class, $minecraft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_minecraft_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('minecraft/edit.html.twig', [
            'minecraft' => $minecraft,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_minecraft_delete', methods: ['POST'])]
    public function delete(Request $request, Minecraft $minecraft, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$minecraft->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($minecraft);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_minecraft_index', [], Response::HTTP_SEE_OTHER);
    }
}
