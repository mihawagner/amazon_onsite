<?php

namespace Drupal\amazon_onsite\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Interface AopFeedInterface.
 *
 * @package Drupal\amazon_onsite\Entity
 */
interface AopFeedInterface extends ConfigEntityInterface {

  /**
   * Returns the AOP feed description.
   *
   * @return string
   *   The description.
   */
  public function getDescription();

  /**
   * Sets the AOP feed description.
   *
   * @param string $description
   *   The description.
   *
   * @return $this
   */
  public function setDescription(string $description);

  /**
   * Returns the path to the AOP feed logo.
   *
   * @return string
   *   The path to the logo.
   */
  public function getLogoPath();

  /**
   * Sets the AOP feed logo path.
   *
   * @param string $logoPath
   *   The logo path.
   */
  public function setLogoPath(string $logoPath);

  /**
   * Returns the ISO639-1 language code of the feed.
   *
   * @return string
   *   ISO639-1 language code.
   */
  public function getLanguage();

  /**
   * Sets the ISO639-1 language of the feed.
   *
   * @param string $language
   *   ISO639-1 language code.
   */
  public function setLanguage(string $language);
}
