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
 *   admin_permission = "",
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
 *     "edit-form" = "/admin/config/system/aop_feed/{aop_feed}",
 *     "delete-form" = "/admin/config/system/aop_feed/{aop_feed}/delete",
 *   },
 * )
 */
class AopFeed extends ConfigEntityBase implements AopFeedInterface {

  /**
   * The AOP Feed ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The AOP Feed title.
   *
   * @var string
   */
  protected $title;

  /**
   * The AOP Feed channel title.
   *
   * @var string
   */
  protected $channel_title;

  /**
   * The AOP Feed website_url.
   *
   * @var string
   */
  protected $website_url;

  /**
   * The AOP Feed language.
   *
   * @var string
   */
  protected $language;

  /**
   * The AOP Feed logo path.
   *
   * @var string
   */
  protected $logo_path;

  /**
   * The AOP Feed description.
   *
   * @var string
   */
  protected $feed_description;

  /**
   * {@inheritDoc}
   */
  public function getUrl() {
    try {
      return $this->toUrl('canonical', ['absolute' => TRUE])->toString();
    }
    catch (\Throwable $exception) {
      return NULL;
    }
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
   * @inheritDoc
   */
  public function setLogoPath(string $logoPath) {
    $this->set('logo_path', $logoPath);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLanguage() {
    return $this->get('language');
  }

  /**
   * @inheritDoc
   */
  public function setLanguage(string $language) {
    $this->set('language', $language);
    return $this;
  }

}