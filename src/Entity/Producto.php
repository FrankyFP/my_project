<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
class Producto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'productos')]
    private ?Categoria $categoria = null;

    /**
     * @var Collection<int, HistorialMovimiento>
     */
    #[ORM\OneToMany(targetEntity: HistorialMovimiento::class, mappedBy: 'producto')]
    private Collection $historialMovimientos;

    public function __construct()
    {
        $this->historialMovimientos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * @return Collection<int, HistorialMovimiento>
     */
    public function getHistorialMovimientos(): Collection
    {
        return $this->historialMovimientos;
    }

    public function addHistorialMovimiento(HistorialMovimiento $historialMovimiento): static
    {
        if (!$this->historialMovimientos->contains($historialMovimiento)) {
            $this->historialMovimientos->add($historialMovimiento);
            $historialMovimiento->setProducto($this);
        }

        return $this;
    }

    public function removeHistorialMovimiento(HistorialMovimiento $historialMovimiento): static
    {
        if ($this->historialMovimientos->removeElement($historialMovimiento)) {
            // set the owning side to null (unless already changed)
            if ($historialMovimiento->getProducto() === $this) {
                $historialMovimiento->setProducto(null);
            }
        }

        return $this;
    }
}
