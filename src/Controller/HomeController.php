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
        $totalArticulos = $productoRepo->createQueryBuilder('p')
            ->select('SUM(p.cantidad)')
            ->getQuery()
            ->getSingleScalarResult();

        $productosBajos = $productoRepo->createQueryBuilder('p')
            ->andWhere('p.cantidad <= :limite')
            ->setParameter('limite', 5)
            ->orderBy('p.cantidad', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        $ultimosMovimientos = $historialRepo->findBy([], ['fecha' => 'DESC'], 10);

        return $this->render('home/index.html.twig', [
            'total_articulos' => $totalArticulos ?? 0,
            'productos_bajos' => $productosBajos, // Asegúrate de que el nombre coincide con tu Twig
            'ultimos_movimientos' => $ultimosMovimientos,
        ]);

        return $this->render('home/index.html.twig', [
            'total_articulos' => $totalArticulos,
            'productos_bajos' => $productosBajos,
            'ultimos_movimientos' => $ultimosMovimientos,
        ]);
    }
}
