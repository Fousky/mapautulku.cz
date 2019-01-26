<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class CrnValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if ($this->isValidCrn((string) $value) === false) {
            $this
                ->context
                ->buildViolation('Tato hodnota není platné IČ.')
                ->addViolation();
        }
    }

    public function isValidCrn(string $crn): bool
    {
        $crn = (string) preg_replace('/\s+/', '', $crn);

        if (!preg_match('/^\d{8}$/', $crn)) {
            return false;
        }

        $a = 0;
        for ($i = 0; $i < 7; $i++) {
            $a += (int) $crn[$i] * (8 - $i);
        }

        $a %= 11;

        if ($a === 0) {
            $c = 1;
        } elseif ($a === 1) {
            $c = 0;
        } else {
            $c = 11 - $a;
        }

        return (int) $crn[7] === $c;
    }
}
