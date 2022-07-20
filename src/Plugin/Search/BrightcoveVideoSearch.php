<?php

namespace Drupal\brightcove\Plugin\Search;

use Drupal\brightcove\BrightcoveVideoInterface;
use Drupal\Core\Access\AccessibleInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\search\Plugin\SearchInterface;
use Drupal\search\Plugin\SearchPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Executes a keyword search for videos against the {brightcove_video} table.
 *
 * @SearchPlugin(
 *   id = "brightcove_video_search",
 *   title = @Translation("Brightcove Video")
 * )
 */
class BrightcoveVideoSearch extends SearchPluginBase implements AccessibleInterface, SearchInterface {

  /**
   * Maximum number of video entities to return.
   */
  const RESULT_LIMIT = 15;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Brightcove Video entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $brightcoveVideoStorage;

  /**
   * Creates a BrightcoveVideoSearch object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $pluginId
   *   The plugin_id for the plugin instance.
   * @param array $definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(array $configuration, string $pluginId, array $definition, AccountInterface $currentUser, Connection $database, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $pluginId, $definition);
    $this->currentUser = $currentUser;
    $this->database = $database;
    $this->brightcoveVideoStorage = $entity_type_manager->getStorage('brightcove_video');

    $this->addCacheTags([
      'brightcove_videos_list',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function access($operation = 'view', AccountInterface $account = NULL, $return_as_object = FALSE) {
    $result = AccessResult::allowedIfHasPermissions($account, [
      'view published brightcove videos',
      'view unpublished brightcove videos',
    ], 'OR');
    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $definition) {
    return new static(
      $configuration,
      $pluginId,
      $definition,
      $container->get('current_user'),
      $container->get('database'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Executes the search.
   *
   * @return array
   *   A structured list of search results.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function execute() {
    $results = [];
    if ($this->isSearchExecutable()) {
      $results = $this->prepareResults($this->queryVideos(), $results);
    }
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function getHelp() {
    $help = [
      'list' => [
        '#theme' => 'item_list',
        '#items' => [
          $this->t('Video search looks for videos using words and partial words from the name and description fields. Example: straw would match videos straw, strawmar, and strawberry.'),
          $this->t('You can use * as a wildcard within your keyword. Example: s*m would match videos that contains strawman, seem, and blossoming.'),
        ],
      ],
    ];

    return $help;
  }

  /**
   * Prepare the result set from the chosen entities.
   *
   * @param array $entities
   *   The entities to add to the passed results.
   * @param array $results
   *   Initial result set to extend.
   *
   * @return array
   *   The combined results.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   *   May happen if the video has no URL available. Should not happen.
   */
  protected function prepareResults(array $entities, array $results) {
    /** @var \Drupal\Core\Entity\ContentEntityInterface $video */
    foreach ($entities as $video) {
      $url = $video->toUrl()->toString();
      $result = [
        'title' => $video->getName(),
        'link' => $url,
      ];

      $this->addCacheableDependency($video);
      $results[] = $result;
    }

    return $results;
  }

  /**
   * Query the matching video entities from the database.
   *
   * @return array
   *   An array of matching video entities.
   */
  protected function queryVideos() {
    // Get query from the storage.
    $query = $this->brightcoveVideoStorage->getQuery();

    // Escape for LIKE matching.
    $keys = $this->database->escapeLike($this->keywords);

    // Replace wildcards with MySQL/PostgreSQL wildcards.
    $keys = preg_replace('!\*+!', '%', $keys);
    $like = "%{$keys}%";

    $query->condition($query->orConditionGroup()
      ->condition('name', $like, 'LIKE')
      ->condition('description', $like, 'LIKE')
      ->condition('long_description', $like, 'LIKE')
      ->condition('related_link__title', $like, 'LIKE')
    );

    // Restrict user's access based on video status.
    // If the user cannot view the published nor the unpublished videos then the
    // user would get an access denied to the page so this case shouldn't be
    // checked here.
    $statuses = [];
    if ($this->currentUser->hasPermission('view published brightcove videos')) {
      $statuses[] = BrightcoveVideoInterface::PUBLISHED;
    }
    if ($this->currentUser->hasPermission('view unpublished brightcove videos')) {
      $statuses[] = BrightcoveVideoInterface::NOT_PUBLISHED;
    }
    // Sanity condition, SQL query doesn't like empty lists so add a NULL value
    // if the user does not have either of the required permissions, this should
    // never happen though.
    if (empty($statuses)) {
      $statuses[] = NULL;
    }
    $query->condition('status', $statuses, 'IN');

    $ids = $query->sort('created', 'DESC')
      ->pager(static::RESULT_LIMIT)
      ->execute();

    return $this->brightcoveVideoStorage->loadMultiple($ids);
  }

}
