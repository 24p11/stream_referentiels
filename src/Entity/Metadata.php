<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="metadata", indexes={
 * @ORM\Index(name="start_date", columns={"start_date"}),
 * @ORM\Index(name="end_date", columns={"end_date"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\MetadataRepository")
 *
 */
class Metadata
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entry;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referential", inversedBy="id")
     */
    private $referential;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntry(): ?string
    {
        return $this->entry;
    }

    public function setEntry(string $entry): self
    {
        $this->entry = $entry;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getReferential(): ?Referential
    {
        return $this->referential;
    }

    public function setReferential(Referential $referential): self
    {
        $this->referential = $referential;

        return $this;
    }


}
