<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PublicHolidayValidator extends ConstraintValidator
{
    const PUBLIC_HOLIDAY = [
        '01/01',
        '01/05',
        '08/05',
        '14/07',
        '15/08',
        '01/11',
        '11/11',
        '25/12'];
    const DAY_OFF = ['Sunday'];

    public function validate($date, Constraint $constraint)
    {
        /* @var $constraint PublicHoliday */

        // Search $date in PUBLIC_HOLIDAY  return bool
        if ( \in_array($date->format('d/m'), self::PUBLIC_HOLIDAY) ) {

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $date->format('d/m'))
                ->addViolation();
        }

        // Search $date in DAY_OFF  return bool
        if ( \in_array($date->format('l'), self::DAY_OFF) ) {

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $date->format('l'))
                ->addViolation();
        }
    }
}
