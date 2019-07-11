<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Crn extends Constraint
{
}
