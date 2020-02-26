<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardGroupRepository")
 */
class CardGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({
     *     "cards",
     *     "card",
     *     "card_groups",
     *     "card_group",
     *     "families",
     *     "family"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Groups({
     *     "cards",
     *     "card",
     *     "card_groups",
     *     "card_group",
     *     "families",
     *     "family"
     * })
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Card", mappedBy="groups")
     * @Serializer\Groups({
     *     "card_groups",
     *     "card_group",
     *     "families",
     *     "family"
     * })
     */
    private $cards;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Family", inversedBy="cardGroups")
     * @Serializer\Groups({
     *     "card_groups",
     *     "card_group"
     * })
     */
    private $family;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
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
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->addGroup($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            $card->removeGroup($this);
        }

        return $this;
    }

    public function getFamily(): ?Family
    {
        return $this->family;
    }

    public function setFamily(?Family $family): self
    {
        $this->family = $family;

        return $this;
    }
}
