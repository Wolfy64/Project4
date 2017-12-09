<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VisitTypeValidator extends ConstraintValidator
{
    const HALF_DAY_TIME = 14; // Time in 24H to start an 1/2 Day

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint VisitType */

        if (\date('H') > self::HALF_DAY_TIME) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', self::HALF_DAY_TIME)
                ->addViolation();
        }
    }
}
