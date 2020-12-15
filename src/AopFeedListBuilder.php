<?php

namespace Drupal\amazon_onsite;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Class AopFeedListBuilder.
 *
 * @package Drupal\amazon_onsite
 */
class AopFeedListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritDoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Name');
    $header['url'] = $this->t('URL');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritDoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\amazon_onsite\Entity\AopFeedInterface $entity */
    $row['label'] = $entity->label();
    $row['url'] = $entity->toUrl('canonical', ['absolute' => TRUE]);
    return $row + parent::buildRow($entity);
  }

}
