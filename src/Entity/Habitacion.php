<?php

namespace App\Entity;

use App\Repository\HabitacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitacionRepository::class)]
class Habitacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num_camas = null;

//    #[ORM\OneToMany(targetEntity: DetallesReserva::class, mappedBy: 'num_habitacion')]
//    private Collection $detallesReserva;

    #[ORM\OneToMany(targetEntity: DetallesReserva::class, mappedBy: 'habitacion')]
    private Collection $detallesReservas;

    public function __construct()
    {
//        $this->detallesReserva = new ArrayCollection();
        $this->detallesReservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCamas(): ?int
    {
        return $this->num_camas;
    }

    public function setNumCamas(int $num_camas): static
    {
        $this->num_camas = $num_camas;

        return $this;
    }

    /**
     * @return Collection<int, DetallesReserva>
     */
//    public function getDetallesReservas(): Collection
//    {
//        return $this->detallesReservas;
//    }

//    public function addDetallesReserva(DetallesReserva $detallesReserva): static
//    {
//        if (!$this->detallesReserva->contains($detallesReserva)) {
//            $this->detallesReserva->add($detallesReserva);
//            $detallesReserva->setNumHabitacion($this);
//        }
//
//        return $this;
//    }
//
//    public function removeDetallesReserva(DetallesReserva $detallesReserva): static
//    {
//        if ($this->detallesReserva->removeElement($detallesReserva)) {
//            // set the owning side to null (unless already changed)
//            if ($detallesReserva->getNumHabitacion() === $this) {
//                $detallesReserva->setNumHabitacion(null);
//            }
//        }
//
//        return $this;
//    }

    /**
     * @return Collection<int, DetallesReserva>
     */
    public function getDetallesReservas(): Collection
    {
        return $this->detallesReservas;
    }
}
