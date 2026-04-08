<?php

namespace App\Controller;

use App\Entity\Juegos;
use App\Form\JuegosType;
use App\Repository\JuegosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/juegos')]
class JuegosController extends AbstractController
{
    // 1. LEER TODOS (Lista)
    #[Route('/', name: 'app_juegos_index', methods: ['GET'])]
    public function index(JuegosRepository $juegosRepository): Response
    {
        return $this->render('juegos/index.html.twig', [
            'juegos' => $juegosRepository->findAll(),
        ]);
    }

    // 2. CREAR
    #[Route('/nuevo', name: 'app_juegos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $juego = new Juegos();
        $form = $this->createForm(JuegosType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($juego); // Prepara para guardar
            $entityManager->flush();         // Ejecuta la consulta (INSERT)

            return $this->redirectToRoute('app_juegos_index');
        }

        return $this->render('juegos/new.html.twig', [
            'juego' => $juego,
            'form' => $form,
        ]);
    }

    // 3. EDITAR
    #[Route('/{id}/editar', name: 'app_juegos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Juegos $juego, EntityManagerInterface $entityManager): Response
    {
        // Symfony busca el juego automáticamente por el {id} de la URL
        $form = $this->createForm(JuegosType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Ya existe, solo hacemos un UPDATE

            return $this->redirectToRoute('app_juegos_index');
        }

        return $this->render('juegos/edit.html.twig', [
            'juego' => $juego,
            'form' => $form,
        ]);
    }

    // 4. BORRAR
    #[Route('/{id}/borrar', name: 'app_juegos_delete', methods: ['POST'])]
    public function delete(Request $request, Juegos $juego, EntityManagerInterface $entityManager): Response
    {
        // Usamos un token CSRF por seguridad para que no borren juegos con un simple enlace
        if ($this->isCsrfTokenValid('delete'.$juego->getId(), $request->request->get('_token'))) {
            $entityManager->remove($juego); // Prepara para borrar
            $entityManager->flush();        // Ejecuta el DELETE
        }

        return $this->redirectToRoute('app_juegos_index');
    }
}