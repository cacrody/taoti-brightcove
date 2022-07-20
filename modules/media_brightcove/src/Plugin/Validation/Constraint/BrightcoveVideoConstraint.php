<?php

namespace Drupal\media_brightcove\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * A media test constraint.
 *
 * @Constraint(
 *   id = "BrightcoveVideoConstraint",
 *   label = @Translation("Media constraint for Brightcove Videos.", context = "Validation"),
 *   type = { "entity", "entity_reference" }
 * )
 */
class BrightcoveVideoConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'Inappropriate field settings.';

}
