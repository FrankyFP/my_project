<?php

namespace App\Service;

use App\Entity\HistorialMovimiento;
use App\Entity\Producto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InventoryManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function reposicion(Producto $producto, ?User $user): int
    {
        $nuevaCantidad = $producto->getCantidad() + 1;
        $producto->setCantidad($nuevaCantidad);

        $historial = new HistorialMovimiento();
        $historial->setProducto($producto);
        $historial->setCantidadCambiada(1);
        $historial->setFecha(new \DateTimeImmutable());
        $historial->setUsuario($user);

        $this->entityManager->persist($historial);
        $this->entityManager->flush();

        return $nuevaCantidad;
    }
}