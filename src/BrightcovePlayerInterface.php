<?php

namespace Drupal\brightcove;

/**
 * Provides an interface for defining Brightcove Player.
 *
 * @ingroup brightcove
 */
interface BrightcovePlayerInterface {

  /**
   * Returns the Brightcove Player ID.
   *
   * @return string
   *   The Brightcove Player ID (not the entity's).
   */
  public function getPlayerId();

  /**
   * Sets The Brightcove Player ID.
   *
   * @param string $player_id
   *   The Brightcove Player ID (not the entity's).
   *
   * @return \Drupal\brightcove\BrightcovePlayerInterface
   *   The called Brightcove Player.
   */
  public function setPlayerId($player_id);

  /**
   * Returns whether the player is adjusted for the playlist or not.
   *
   * @return bool|null
   *   TRUE or FALSE whether the player is adjusted or not, or NULL if not set.
   */
  public function isAdjusted();

  /**
   * Sets the Player as adjusted.
   *
   * @param bool|null $adjusted
   *   TRUE or FALSE whether the player is adjusted or not, or NULL to unset
   *   the value.
   *
   * @return \Drupal\brightcove\BrightcovePlayerInterface
   *   The called Brightcove Player.
   */
  public function setAdjusted($adjusted);

  /**
   * Returns the height of the player.
   *
   * @return float
   *   The height of the player.
   */
  public function getHeight();

  /**
   * Sets the height of the player.
   *
   * @param float $height
   *   The height of the player.
   *
   * @return \Drupal\brightcove\BrightcovePlayerInterface
   *   The called Brightcove Player.
   */
  public function setHeight($height);

  /**
   * Returns the width of the player.
   *
   * @return float
   *   The width of the player.
   */
  public function getWidth();

  /**
   * Sets the width of the player.
   *
   * @param float $width
   *   The width of the player.
   *
   * @return \Drupal\brightcove\BrightcovePlayerInterface
   *   The called Brightcove Player.
   */
  public function setWidth($width);

  /**
   * Returns the units for the height and width.
   *
   * @return string
   *   The units for the height and width.
   */
  public function getUnits();

  /**
   * Sets the units for the height and width.
   *
   * @param string $units
   *   The units for the height and width.
   *
   * @return \Drupal\brightcove\BrightcovePlayerInterface
   *   The called Brightcove Player.
   */
  public function setUnits($units);

  /**
   * Returns whether if the player is responsive or not.
   *
   * @return string
   *   TRUE if the player is responsive, FALSE if fixed.
   */
  public function isResponsive();

  /**
   * Sets the responsive indicator.
   *
   * @param bool $is_responsive
   *   TRUE if the player is responsive, FALSE if fixed.
   *
   * @return \Drupal\brightcove\BrightcovePlayerInterface
   *   The called Brightcove Player.
   */
  public function setResponsive($is_responsive);

  /**
   * Returns whether the player is a playlist player or a single video player.
   *
   * @return bool
   *   TRUE if playlist player, FALSE if single video player.
   */
  public function isPlaylist();

  /**
   * Sets if the player is a playlist player or single video player.
   *
   * @param bool $is_playlist
   *   TRUE if playlist player, FALSE if single video player.
   *
   * @return \Drupal\brightcove\BrightcovePlayerInterface
   *   The called Brightcove Player.
   */
  public function setPlaylist($is_playlist);

  /**
   * Gets the version of the player.
   *
   * @return string
   *   The version of the player.
   */
  public function getVersion();

}
