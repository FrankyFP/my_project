<?php

namespace App\Entity;

use App\Repository\HistorialMovimientoRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: HistorialMovimientoRepository::class)]
class HistorialMovimiento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cantidadCambiada = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fecha = null;

    #[ORM\ManyToOne(inversedBy: 'historialMovimientos')]
    private ?Producto $producto = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidadCambiada(): ?int
    {
        return $this->cantidadCambiada;
    }

    public function setCantidadCambiada(int $cantidadCambiada): static
    {
        $this->cantidadCambiada = $cantidadCambiada;

        return $this;
    }

    public function getFecha(): ?\DateTimeImmutable
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeImmutable $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): static
    {
        $this->producto = $producto;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }
}
