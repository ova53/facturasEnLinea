<?php

namespace App\Entity;

use App\Repository\FacturaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturaRepository::class)
 */
class Factura
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $numDoc;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $amout;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $bill;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $autorization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumDoc(): ?string
    {
        return $this->numDoc;
    }

    public function setNumDoc(string $numDoc): self
    {
        $this->numDoc = $numDoc;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmout(): ?float
    {
        return $this->amout;
    }

    public function setAmout(float $amout): self
    {
        $this->amout = $amout;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBill(): ?string
    {
        return $this->bill;
    }

    public function setBill(string $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function getAutorization(): ?string
    {
        return $this->autorization;
    }

    public function setAutorization(string $autorization): self
    {
        $this->autorization = $autorization;

        return $this;
    }
}
