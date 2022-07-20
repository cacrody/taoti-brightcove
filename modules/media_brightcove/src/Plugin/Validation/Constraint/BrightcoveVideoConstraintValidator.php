<?php

namespace Drupal\media_brightcove\Plugin\Validation\Constraint;

use Drupal\Core\Field\EntityReferenceFieldItemList;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the BrightcoveVideoConstraint.
 */
class BrightcoveVideoConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    if (!($value instanceof EntityReferenceFieldItemList)) {
      $this->context->addViolation($constraint->message);
    }
    /** @var \Drupal\Core\Field\EntityReferenceFieldItemList $value */
    $target_type = $value->getFieldDefinition()
      ->getFieldStorageDefinition()
      ->getSetting('target_type');

    if ($target_type != 'brightcove_video') {
      $this->context->addViolation($constraint->message);
    }
  }

}
