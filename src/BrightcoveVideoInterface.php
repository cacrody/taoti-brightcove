<?php

namespace Drupal\brightcove;

/**
 * Provides an interface for defining Brightcove Videos.
 *
 * @ingroup brightcove
 */
interface BrightcoveVideoInterface {

  /**
   * Denotes that the video is published.
   */
  const PUBLISHED = 1;

  /**
   * Denotes that the video is not published.
   */
  const NOT_PUBLISHED = 0;

  /**
   * Brightcove video thumbnail images path.
   */
  const VIDEOS_IMAGES_THUMBNAILS_DIR = 'brightcove/videos/images/thumbnails';

  /**
   * Brightcove video poster images path.
   */
  const VIDEOS_IMAGES_POSTERS_DIR = 'brightcove/videos/images/posters';

  /**
   * Brightcove thumbnail image type.
   */
  const IMAGE_TYPE_THUMBNAIL = 'thumbnail';

  /**
   * Brightcove poster image type.
   */
  const IMAGE_TYPE_POSTER = 'poster';

  /**
   * Brightcove economics type, free.
   */
  const ECONOMICS_TYPE_FREE = 'FREE';

  /**
   * Brightcove economics type, ad supported.
   */
  const ECONOMICS_TYPE_AD_SUPPORTED = 'AD_SUPPORTED';

  /**
   * Brightcove active state.
   */
  const STATE_ACTIVE = 'ACTIVE';

  /**
   * Brightcove inactive state.
   */
  const STATE_INACTIVE = 'INACTIVE';

  /**
   * Brightcove video tags vocabulary ID.
   */
  const TAGS_VID = 'brightcove_video_tags';

  /**
   * Helper function to save the image for the entity.
   *
   * @param string $type
   *   The type of the image. Possible values are:
   *     - IMAGE_TYPE_THUMBNAIL: Recommended aspect ratio of 16:9 and a minimum
   *                             width of 640px.
   *     - IMAGE_TYPE_POSTER: Recommended aspect ratio of 16:9 and a minimum
   *                          width of 160px.
   * @param \Brightcove\Item\Video\Image|array $image
   *   The image from Brightcove.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   *
   * @throws \Exception
   *   If the type is not matched with the possible types.
   */
  public function saveImage($type, $image);

  /**
   * Returns the video's duration.
   *
   * @return int
   *   Video duration.
   */
  public function getDuration();

  /**
   * Sets the video duration.
   *
   * @param int $duration
   *   The duration of the video.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setDuration($duration);

  /**
   * Returns the video's related link.
   *
   * @return array
   *   An array list of links.
   */
  public function getRelatedLink();

  /**
   * Sets the video's related link.
   *
   * @param array|null $related_link
   *   The related link.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setRelatedLink($related_link);

  /**
   * Returns the long description of the video.
   *
   * @return string
   *   The long description of the video.
   */
  public function getLongDescription();

  /**
   * Sets the video's long description.
   *
   * @param string $long_description
   *   The long description of the video.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setLongDescription($long_description);

  /**
   * Returns the economics state.
   *
   * @return bool
   *   The economics state of the video.
   */
  public function getEconomics();

  /**
   * Sets the video's economics state.
   *
   * @param bool $economics
   *   The economics state of the video.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setEconomics($economics);

  /**
   * Returns the video file.
   *
   * @return array
   *   The video file entity.
   */
  public function getVideoFile();

  /**
   * Sets the video file.
   *
   * @param array|null $video_file
   *   The video file entity.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setVideoFile($video_file);

  /**
   * Returns the video URL.
   *
   * @return string
   *   The video URL.
   */
  public function getVideoUrl();

  /**
   * Sets the video URL.
   *
   * @param string $video_url
   *   The video URL.
   *
   * @return $this
   */
  public function setVideoUrl($video_url);

  /**
   * Returns the video's profile.
   *
   * @return string
   *   The video profile.
   */
  public function getProfile();

  /**
   * Sets the video's profile.
   *
   * @param string $profile
   *   Video's profile.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setProfile($profile);

  /**
   * Returns the video's poster image.
   *
   * @return array
   *   The poster image on the entity.
   */
  public function getPoster();

  /**
   * Sets the video's poster image.
   *
   * @param array|null $poster
   *   The poster image which needs to be saved on the entity.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setPoster($poster);

  /**
   * Returns the video's thumbnail image.
   *
   * @return array
   *   The thumbnail image on the entity.
   */
  public function getThumbnail();

  /**
   * Sets the video's thumbnail image.
   *
   * @param array|null $thumbnail
   *   The thumbnail image which needs to be saved on the entity.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setThumbnail($thumbnail);

  /**
   * Returns the schedule starts at date.
   *
   * @return string
   *   The datetime of the schedule starts at date.
   */
  public function getScheduleStartsAt();

  /**
   * Returns the custom field values.
   *
   * @return array
   *   Each field's value keyed by it's field ID.
   */
  public function getCustomFieldValues();

  /**
   * Sets the custom field values.
   *
   * @param array $values
   *   Field values keyed by field's ID.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setCustomFieldValues(array $values);

  /**
   * Sets the video's schedule starts at date.
   *
   * @param string $schedule_starts_at
   *   The datetime of the schedule starts at date.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setScheduleStartsAt($schedule_starts_at);

  /**
   * Returns the schedule ends at date.
   *
   * @return string
   *   The datetime of the schedule ends at date.
   */
  public function getScheduleEndsAt();

  /**
   * Sets the video's schedule ends at date.
   *
   * @param string $schedule_ends_at
   *   The datetime of the schedule ends at date.
   *
   * @return \Drupal\brightcove\BrightcoveVideoInterface
   *   The called Brightcove Video.
   */
  public function setScheduleEndsAt($schedule_ends_at);

}
