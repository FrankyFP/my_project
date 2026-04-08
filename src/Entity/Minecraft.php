<?php

namespace App\Entity;

use App\Repository\MinecraftRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MinecraftRepository::class)]
class Minecraft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    #[ORM\Column(length: 255)]
    private ?string $Plataforma = null;

    #[ORM\Column(length: 255)]
    private ?string $gamemode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getPlataforma(): ?string
    {
        return $this->Plataforma;
    }

    public function setPlataforma(string $Plataforma): static
    {
        $this->Plataforma = $Plataforma;

        return $this;
    }

    public function getGamemode(): ?string
    {
        return $this->gamemode;
    }

    public function setGamemode(string $gamemode): static
    {
        $this->gamemode = $gamemode;

        return $this;
    }
}
