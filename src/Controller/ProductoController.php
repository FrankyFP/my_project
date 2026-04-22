<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\HistorialMovimiento;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\InventoryManager;

#[Route('/producto')]
final class ProductoController extends AbstractController
{
    #[Route(name: 'app_producto_index', methods: ['GET'])]
    public function index(ProductoRepository $productoRepository): Response
    {
        return $this->render('producto/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_producto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($producto);
            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_producto_show', methods: ['GET'])]
    public function show(Producto $producto): Response
    {
        return $this->render('producto/show.html.twig', [
            'producto' => $producto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_producto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Producto $producto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }

    // 2. BLOQUEAR EL BORRADO SOLO A ADMINS
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_producto_delete', methods: ['POST'])]
    public function delete(Request $request, Producto $producto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($producto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
    }

    // 1. AÑADIR EL USUARIO AL HISTORIAL
    #[Route('/{id}/reposicion', name: 'app_producto_reposicion', methods: ['POST'])]
    public function reposicion(Producto $producto, EntityManagerInterface $entityManager): JsonResponse
    {
        $producto->setCantidad($producto->getCantidad() + 1);

        $historial = new HistorialMovimiento();
        $historial->setProducto($producto);
        $historial->setCantidadCambiada(1);
        $historial->setFecha(new \DateTimeImmutable());
        
        // ¡Magia! Obtenemos el usuario logueado y lo guardamos
        $historial->setUsuario($this->getUser());

        $entityManager->persist($historial);
        $entityManager->flush();

        return $this->json([
            'nueva_cantidad' => $producto->getCantidad()
        ]);
    }

    #[Route('/api/producto/list', name: 'api_producto_index', methods: ['GET'])]
    public function indexJson(ProductoRepository $repo): JsonResponse
    {
        $productos = $repo->findAll();
        $data = array_map(fn($p) => [
            'id' => $p->getId(),
            'nombre' => $p->getNombre(),
            'cantidad' => $p->getCantidad(),
            'categoria' => $p->getCategoria()?->getNombre()
        ], $productos);

        return $this->json($data);
    }

    #[Route('/api/producto/{id}/reposicion', name: 'api_producto_reposicion', methods: ['POST'])]
    public function reposicionApi(
        Producto $producto,
        InventoryManager $inventoryManager
    ): JsonResponse {
        try {
            $nuevaCantidad = $inventoryManager->reposicion($producto, $this->getUser());

            return $this->json([
                'status' => 'success',
                'message' => 'Stock actualizado correctamente',
                'nueva_cantidad' => $nuevaCantidad
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'No se pudo actualizar el stock'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
