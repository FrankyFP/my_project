<?php
namespace App\Controller;

use App\Repository\ProductoRepository;
use App\Repository\HistorialMovimientoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_inicio')]
    public function index(ProductoRepository $productoRepo, HistorialMovimientoRepository $historialRepo): Response
    {
        // 1. Total de artículos en stock
        $totalArticulos = $productoRepo->createQueryBuilder('p')
            ->select('SUM(p.cantidad)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        // 2. Los 5 productos con menos existencias
        $productosBajos = $productoRepo->findBy(
            [], // sin filtros
            ['cantidad' => 'ASC'], // Ordenados de menor a mayor
            5 // Límite de 5
        );

        // 3. Los últimos movimientos del almacén
        $ultimosMovimientos = $historialRepo->findBy(
            [],
            ['fecha' => 'DESC'], // Los más recientes primero
            10 // Mostrar los últimos 10
        );

        return $this->render('home/index.html.twig', [
            'total_articulos' => $totalArticulos,
            'productos_bajos' => $productosBajos,
            'ultimos_movimientos' => $ultimosMovimientos,
        ]);
    }
}
