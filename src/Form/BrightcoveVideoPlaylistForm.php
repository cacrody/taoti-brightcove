<?php

namespace Drupal\brightcove\Form;

use Drupal\brightcove\BrightcoveUtil;
use Drupal\brightcove\Entity\BrightcovePlayer;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base form controller for Video and Playlist forms.
 */
abstract class BrightcoveVideoPlaylistForm extends ContentEntityForm {

  /**
   * The default API Client.
   *
   * @var string
   */
  protected $defaultAPIClient;

  /**
   * Constructs a ContentEntityForm object.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param string $defaultAPIClient
   *   The default API Client.
   */
  public function __construct(EntityRepositoryInterface $entity_repository, EntityTypeBundleInfoInterface $entity_type_bundle_info, TimeInterface $time, $defaultAPIClient) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->defaultAPIClient = $defaultAPIClient;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
      $container->get('config.factory')->get('brightcove.settings')->get('defaultAPIClient')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    /** @var \Drupal\brightcove\Entity\BrightcoveVideoPlaylistCmsEntity $entity */
    $entity = $this->entity;
    $triggering_element = $form_state->getTriggeringElement();

    // Check for an updated version of the Video.
    if ($entity->id() && empty($triggering_element)) {
      BrightcoveUtil::checkUpdatedVersion($entity);
    }

    if ($entity->isNew()) {
      // Set default api client.
      if (!$form['api_client']['widget']['#default_value']) {
        $form['api_client']['widget']['#default_value'] = $this->defaultAPIClient;
      }

      // Update player list on client selection.
      $form['api_client']['widget']['#ajax'] = [
        'callback' => [self::class, 'apiClientUpdateForm'],
        'event' => 'change',
        'wrapper' => 'player-ajax-wrapper',
      ];

      // Add ajax wrapper for player.
      $form['player']['widget']['#prefix'] = '<div id="' . $form['api_client']['widget']['#ajax']['wrapper'] . '">';
      $form['suffix']['widget']['#suffix'] = '</div>';
    }
    else {
      // Disable api client selection after the playlist is created.
      $form['api_client']['widget']['#disabled'] = TRUE;
    }

    // Change none option's name.
    $form['player']['widget']['#options'] = self::getPlayerOptions($form, $form_state);

    return $form;
  }

  /**
   * Get the player options for the given api client.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The list of options for the player selection.
   */
  protected static function getPlayerOptions(array $form, FormStateInterface $form_state) {
    if (empty($form_state->getValue('api_client'))) {
      $api_client = $form['api_client']['widget']['#default_value'];
    }
    else {
      $api_client = $form_state->getValue('api_client')[0]['target_id'];
    }

    return [
      '_none' => t("Use API Client's default player"),
    ] + BrightcovePlayer::getList($api_client, TRUE);
  }

  /**
   * Ajax callback to update the player options list.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax command response.
   */
  public static function apiClientUpdateForm(array $form, FormStateInterface $form_state) {
    $form['player']['widget']['#options'] = self::getPlayerOptions($form, $form_state);

    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand(
      '#' . $form['api_client']['widget']['#ajax']['wrapper'],
      $form['player']
    ));

    return $response;
  }

}
