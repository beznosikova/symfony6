<?php

namespace App\Validator;

use App\Entity\Survey;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class SurveyStatusValidator extends ConstraintValidator
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    private function canNotChangeStatusOnReady(Survey $survey): bool
    {
        $unitOfWork = $this->entityManager->getUnitOfWork();
        $unitOfWork->computeChangeSets();
        $changes = $unitOfWork->getEntityChangeSet($survey);

        if (!empty($changes['status']) && $survey->isReady()) {
            return empty($survey->getQuestions()->count());
        }

        return false;
    }

    public function validate($survey, Constraint $constraint): void
    {
        if (!$survey instanceof Survey) {
            throw new UnexpectedValueException($survey, Survey::class);
        }

        if (!$constraint instanceof SurveyStatus) {
            throw new UnexpectedTypeException($constraint, SurveyStatus::class);
        }
        
        if ($this->canNotChangeStatusOnReady($survey)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $survey->getStatus())
                ->addViolation();
        }
    }
}
