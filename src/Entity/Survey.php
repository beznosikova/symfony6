<?php

namespace App\Entity;

use App\Enums\SurveyStatus;
use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SurveyRepository::class)]
class Survey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    private ?string $name = null;

    #[ORM\Column(length: 255, enumType: SurveyStatus::class, options: ["default" => SurveyStatus::EDIT])]
    private ?SurveyStatus $status = null;

    #[ORM\Column(nullable: true)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[Ignore]
    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: Question::class, orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->status = SurveyStatus::EDIT;
    }

    public function __toString(): string
    {
        return 'survey string';
    }

    public function inEdition(): bool
    {
        return true; // todo; add logic
    }

    public function getApiUrl(): string
    {
        return "/survey/show/$this->id";
        // todo: use service with url generator
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status?->value;
        // TODO: how customize enum value for API??
        return $this->status->text();
    }

    public function setStatus(?string $status): static
    {
        $this->status = SurveyStatus::tryFrom($status);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setSurvey($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSurvey() === $this) {
                $question->setSurvey(null);
            }
        }

        return $this;
    }
}
