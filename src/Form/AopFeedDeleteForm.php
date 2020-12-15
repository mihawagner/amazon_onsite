<?php

namespace Drupal\amazon_onsite\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class AopFeedDeleteForm.
 *
 * Builds a form to confirm the deletion of an AOP Feed config entity.
 *
 * @package Drupal\amazon_onsite\Form
 */
class AopFeedDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritDoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete AOP feed @label?', [
      '@label' => $this->entity->label(),
    ]);
  }

  /**
   * {@inheritDoc}
   */
  public function getCancelUrl() {
    return new Url('entity.aop_feed.collection');
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();
    $this->messenger()->addMessage($this->t('The entity "@label" has been deleted.', [
      '@label' => $this->entity->label(),
    ]));
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
