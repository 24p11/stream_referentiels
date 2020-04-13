<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Xx
 *
 * @ORM\Table(name="xx")
 * @ORM\Entity
 */
class Xx
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="data", type="string", length=50, nullable=true)
     */
    private $data;

    /**
     * @var json|null
     *
     * @ORM\Column(name="json_data", type="json", nullable=true)
     */
    private $jsonData;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getJsonData(): ?array
    {
        return $this->jsonData;
    }

    public function setJsonData(?array $jsonData): self
    {
        $this->jsonData = $jsonData;

        return $this;
    }


}
