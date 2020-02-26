<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FamilyRepository")
 */
class Family
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({
     *     "family",
     *     "families",
     *     "card_group",
     *     "card_groups",
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Groups({
     *     "family",
     *     "families",
     *     "card_group",
     *     "card_groups",
     * })
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardGroup", mappedBy="family")
     * @Serializer\Groups({
     *     "family",
     *     "families",
     * })
     */
    private $cardGroups;

    public function __construct()
    {
        $this->cardGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|CardGroup[]
     */
    public function getCardGroups(): Collection
    {
        return $this->cardGroups;
    }

    public function addCardGroup(CardGroup $cardGroup): self
    {
        if (!$this->cardGroups->contains($cardGroup)) {
            $this->cardGroups[] = $cardGroup;
            $cardGroup->setFamily($this);
        }

        return $this;
    }

    public function removeCardGroup(CardGroup $cardGroup): self
    {
        if ($this->cardGroups->contains($cardGroup)) {
            $this->cardGroups->removeElement($cardGroup);
            // set the owning side to null (unless already changed)
            if ($cardGroup->getFamily() === $this) {
                $cardGroup->setFamily(null);
            }
        }

        return $this;
    }
}
