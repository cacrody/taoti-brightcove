<?php

namespace Drupal\brightcove\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Brightcove Text Track edit forms.
 *
 * @ingroup brightcove
 */
class BrightcoveTextTrackForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\brightcove\Entity\BrightcoveTextTrack $entity */
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('Created the %label Brightcove Text Track.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addStatus($this->t('Saved the %label Brightcove Text Track.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.brightcove_text_track.canonical', ['brightcove_text_track' => $entity->id()]);
  }

}
