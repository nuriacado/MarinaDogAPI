<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $claveApi = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClaveApi(): ?string
    {
        return $this->claveApi;
    }

    public function setClaveApi(string $claveApi): static
    {
        $this->claveApi = $claveApi;

        return $this;
    }
}
