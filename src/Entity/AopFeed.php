<?php

namespace Drupal\amazon_onsite\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Class AopFeed.
 *
 * @package Drupal\amazon_onsite\Entity
 *
 * @ConfigEntityType(
 *   id = "aop_feed",
 *   label = @Translation("AOP Feed"),
 *   config_prefix = "aop_feed",
 *   admin_permission = "administer aop feeds",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   handlers = {
 *     "list_builder" = "Drupal\amazon_onsite\AopFeedListBuilder",
 *     "form" = {
 *       "add" = "Drupal\amazon_onsite\Form\AopFeedForm",
 *       "edit" = "Drupal\amazon_onsite\Form\AopFeedForm",
 *       "delete" = "Drupal\amazon_onsite\Form\AopFeedDeleteForm",
 *     },
 *   },
 *   links = {
 *     "canonical" = "/aop/{aop_feed}/rss.xml",
 *     "add-form" = "/admin/config/structure/aop_feed/add",
 *     "edit-form" = "/admin/config/structure/aop_feed/{aop_feed}/edit",
 *     "delete-form" = "/admin/config/structure/aop_feed/{aop_feed}/delete",
 *   },
 * )
 */
class AopFeed extends ConfigEntityBase implements AopFeedInterface {

  /**
   * The AOP Feed ID.
   *
   * @var string
   */
  public $id;

  /**
   * The AOP Feed title.
   *
   * @var string
   */
  public $title;

  /**
   * The AOP Feed channel title.
   *
   * @var string
   */
  public $channel_title;

  /**
   * The AOP Feed website_url.
   *
   * @var string
   */
  public $website_url;

  /**
   * The AOP Feed language.
   *
   * @var string
   */
  public $language;

  /**
   * The AOP Feed logo path.
   *
   * @var string
   */
  public $logo_path;

  /**
   * The AOP Feed description.
   *
   * @var string
   */
  public $feed_description;

  /**
   * Returns the feed title.
   *
   * @return string
   */
  public function label() {
    return $this->getTitle();
  }

  public function setTitle(string $title) {
    $this->set('channel_title', $title);
    return $this;
  }

  public function getTitle() {
    return $this->get('channel_title');
  }

  /**
   * {@inheritDoc}
   */
  public function getWebsiteUrl() {
    return $this->get('website_url');
  }

  public function setWebsiteUrl(string $url) {
    $this->set('website_url', $url);
    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getDescription() {
    return $this->get('feed_description');
  }

  /**
   * {@inheritDoc}
   */
  public function setDescription(string $description) {
    $this->set('feed_description', $description);
    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getLogoPath() {
    return $this->get('logo_path');
  }

  /**
   * {@inheritDoc}
   */
  public function setLogoPath(string $logoPath) {
    $this->set('logo_path', $logoPath);
    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getLanguage() {
    return $this->get('language');
  }

  /**
   * {@inheritDoc}
   */
  public function setLanguage(string $language) {
    $this->set('language', $language);
    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getUrl() {
    return $this->toUrl('canonical', ['absolute' => TRUE])->toString();
  }

  /**
   * Allowed languages codes for AOP Feed.
   *
   * @return array
   *   The language codes.
   */
  public static function supportedLanguages() {
    return [
      'de-DE' => 'de-DE',
    ];
  }

  /**
   * Returns a list suitable for usage in select options.
   *
   * @return array
   *   Array keyed by AopFeed::id().
   */
  public static function all() {
    $feeds = self::loadMultiple();
    $result = [];
    foreach ($feeds as $feed) {
      $result[$feed->id()] = $feed->label();
    }
    return $result;
  }

}
