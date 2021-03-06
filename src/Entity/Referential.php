<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Repositories
 *
 * @ORM\Table(name="referential",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="ref_id_type_lhash", columns={"ref_id", "type", "label_hash"})},
 *     indexes={
 *     @ORM\Index(name="created_at", columns={"created_at"}),
 *     @ORM\Index(name="ref_id_label", columns={"ref_id", "label"}, flags={"fulltext"}),
 *     @ORM\Index(name="end_date", columns={"end_date"}),
 *     @ORM\Index(name="fk_repositories_referential_types1_idx", columns={"id"}),
 *     @ORM\Index(name="ref_id", columns={"ref_id"}),
 *     @ORM\Index(name="score", columns={"score"}),
 *     @ORM\Index(name="start_date", columns={"start_date"}),
 *     @ORM\Index(name="updated_at", columns={"updated_at"})
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\ReferentialRepository")
 */
class Referential
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_id", type="string", length=7, nullable=false, options={"fixed"=true})
     */
    private $refId;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="label_hash", type="string", length=32, nullable=false, columnDefinition="CHAR(32) NOT NULL")
     */
    private $labelHash;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score = '0';

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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, columnDefinition="DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="ReferentialType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Metadata", mappedBy="referential")
     */
    private $metadata;

    /**
     * ReferentialTypes constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        $this->labelHash = md5($label);

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUniqueId()
    {
        return $this->getType() . $this->getRefId() . $this->getLabelHash();
    }

    public function getType(): string
    {
        return $this->type->getId();
    }

    public function setType(ReferentialType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRefId(): ?string
    {
        return $this->refId;
    }

    public function setRefId(string $refId): self
    {
        $this->refId = $refId;

        return $this;
    }

    public function getLabelHash(): string
    {
        return $this->labelHash;
    }

    public function getMetadata(): ?Collection
    {
        return $this->metadata;
    }

    public function setMetadata(?Collection $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function __toString()
    {
        return "";
    }
}
