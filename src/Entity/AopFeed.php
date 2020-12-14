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

}