<?php

namespace Drupal\amazon_onsite\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

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
    $row['url'] = $entity->getUrl();
    return $row + parent::buildRow($entity);
  }

}