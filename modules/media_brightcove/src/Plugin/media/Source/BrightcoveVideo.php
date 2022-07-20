<?php

namespace Drupal\media_brightcove\Plugin\media\Source;

use Drupal\Core\Entity\Display\EntityFormDisplayInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\file\Entity\File;
use Drupal\media\MediaInterface;
use Drupal\media\MediaSourceBase;
use Drupal\media\MediaTypeInterface;
use Drupal\media\MediaSourceFieldConstraintsInterface;

/**
 * Brightcove Video entity media source.
 *
 * @MediaSource(
 *   id = "brightcove_video",
 *   label = @Translation("Brightcove Video"),
 *   description = @Translation("Use Brightcove Videos for reusable media."),
 *   allowed_field_types = {"entity_reference"},
 *   default_thumbnail_filename = "no-thumbnail.png",
 * )
 */
class BrightcoveVideo extends MediaSourceBase implements MediaSourceFieldConstraintsInterface {

  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes() {
    return [
      'name' => $this->t('Name'),
      'api_client' => $this->t('API Client'),
      'player' => $this->t('Player'),
      'video_id' => $this->t('Video ID'),
      'duration' => $this->t('Video Duration'),
      'description' => $this->t('Short description'),
      'long_description' => $this->t('Long description'),
      'poster' => $this->t('Video Still'),
      'thumbnail' => $this->t('Thumbnail'),
      // @todo Check if these are useful in the Brightcove Video media source.
      'complete' => $this->t('Complete'),
      'reference_id' => $this->t('Reference ID'),
      'state' => $this->t('State'),
      'tags' => $this->t('Tags'),
      'custom_fields' => $this->t('Custom fields'),
      'geo' => $this->t('Geo information'),
      'geo.countries' => $this->t('Geo countries'),
      'geo.exclude_countries' => $this->t('Exclude countries'),
      'geo.restricted' => $this->t('Geo Restricted'),
      'schedule' => $this->t('Schedule'),
      'starts_at' => $this->t('Starts at'),
      'ends_at' => $this->t('Ends at'),
      'picture_thumbnail' => $this->t('Thumbnail picture'),
      'picture_poster' => $this->t('Picture poster'),
      'video_source' => $this->t('Video source'),
      'economics' => $this->t('Economics'),
      'partner_channel' => $this->t('Partner channel'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(MediaInterface $media, $attribute_name) {
    switch ($attribute_name) {
      case 'thumbnail_uri':
        // Have a decent default.
        $uri = $this->configFactory->get('media.settings')->get('icon_base_uri') . '/no-thumbnail.png';

        // Check if the source field is still present and populated.
        $media_source = $media->getSource();
        /** @var \Drupal\brightcove\Entity\BrightcoveVideo $brightcove_video */
        if ($brightcove_video = $media->get($media_source->getConfiguration()['source_field'])->entity) {
          // Check if the thumbnail file is still present.
          if ($thumbnail = $brightcove_video->getThumbnail()) {
            if ($thumbnail_file = File::load($thumbnail['target_id'])) {
              $uri = $thumbnail_file->getFileUri();
            }
          }
        }
        return $uri;

      case 'name':
        return $media->get('name')->get(0)->getValue();

      // @todo Add cases for other metadata as/if needed.
      default:
        return parent::getMetadata($media, $attribute_name);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function createSourceFieldStorage() {
    return $this->entityTypeManager
      ->getStorage('field_storage_config')
      ->create([
        'entity_type' => 'media',
        'field_name' => $this->getSourceFieldName(),
        'type' => reset($this->pluginDefinition['allowed_field_types']),
        'settings' => [
          'target_type' => 'brightcove_video',
        ],
        'locked' => TRUE,
      ]);
  }

  /**
   * {@inheritdoc}
   */
  public function createSourceField(MediaTypeInterface $type) {
    $storage = $this->getSourceFieldStorage() ?: $this->createSourceFieldStorage();
    return $this->entityTypeManager
      ->getStorage('field_config')
      ->create([
        'field_storage' => $storage,
        'bundle' => $type->id(),
        'label' => $this->pluginDefinition['label'],
        'required' => TRUE,
        'settings' => [
          'handler' => 'default:brightcove_video',
        ],
      ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFieldConstraints() {
    // Make sure that the referenced entity is a brightcove_video one, although
    // this validation happens only when creating a media item with this
    // MediaSource plugin.
    // Theoretically this will not be needed when the automatically-created
    // source field gets really locked.
    // @see https://www.drupal.org/node/2274433#comment-12007765
    return [
      'BrightcoveVideoConstraint' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareFormDisplay(MediaTypeInterface $type, EntityFormDisplayInterface $display) {
    // Let the parent add the defaults.
    parent::prepareFormDisplay($type, $display);
    // Change only what's needed.
    $component = $display->getComponent($this->getSourceFieldDefinition($type)->getName());
    $component['type'] = 'brightcove_inline_entity_form_complex';
    $component['settings'] = [
      'form_mode' => 'default',
      'allow_new' => 1,
      'allow_existing' => 1,
      'match_operator' => 'CONTAINS',
    ];
    $display->setComponent($this->getSourceFieldDefinition($type)->getName(), $component);
  }

  /**
   * {@inheritdoc}
   */
  public function prepareViewDisplay(MediaTypeInterface $type, EntityViewDisplayInterface $display) {
    // Let the parent add the defaults.
    parent::prepareViewDisplay($type, $display);
    // Change only what's needed.
    $component = $display->getComponent($this->getSourceFieldDefinition($type)->getName());
    $component['label'] = 'hidden';
    $component['settings']['link'] = TRUE;
    $component['type'] = 'entity_reference_entity_view';
    $display->setComponent($this->getSourceFieldDefinition($type)->getName(), $component);
  }

}
