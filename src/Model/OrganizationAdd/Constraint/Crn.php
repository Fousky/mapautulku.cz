<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class Crn extends Constraint
{
}
