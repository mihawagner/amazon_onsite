<?php

namespace Drupal\amazon_onsite\Form;

use Drupal\amazon_onsite\Entity\AopFeed;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\File\Exception\FileException;
use Drupal\Core\File\FileSystemInterface;;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The AOP feed form for amazon onsite module.
 */
class AopFeedForm extends EntityForm {

  /**
   * Drupal\Core\Extension\ModuleHandlerInterface module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler, FileSystemInterface $file_system) {
    $this->moduleHandler = $module_handler;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $feedItem = $this->entity;

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $feedItem->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$feedItem->isNew(),
    ];

    $form['channel_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $feedItem->label(),
      '#required' => TRUE,
    ];
    $form['website_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Website URL'),
      '#description' => $this->t('The website url which is associated with this RSS channel. (HTTPS is required)'),
      '#default_value' => $feedItem->getWebsiteUrl(),
      '#pattern' => 'https://.*',
      '#required' => TRUE,
    ];
    $form['feed_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Feed description'),
      '#default_value' => $feedItem->getDescription(),
      '#required' => TRUE,
    ];
    $form['logo'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Channel logo'),
    ];
    $form['logo']['logo_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Path to image'),
      '#default_value' => $feedItem->getLogoPath(),
    ];
    $form['logo']['logo_upload'] = [
      '#type' => 'file',
      '#title' => $this->t('Upload image'),
      '#maxlength' => 40,
      '#description' => $this->t("If you don't have direct file access to the server, use this field to upload your logo."),
      '#upload_validators' => [
        'file_validate_is_image' => [],
      ],
    ];

    $form['language'] = [
      '#type' => 'select',
      '#options' => AopFeed::supportedLanguages(),
      '#title' => $this->t('Language'),
      '#description' => $this->t('ISO639-1 language string'),
      '#default_value' => $feedItem->getLanguage(),
    ];
    $form['channel_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Feed URL'),
      '#default_value' => $feedItem->getUrl(),
      '#disabled' => TRUE,
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    if ($this->moduleHandler->moduleExists('file')) {

      // Check for a new uploaded logo.
      if (isset($form['logo'])) {
        $upload_location = isset($form['logo']['logo_upload']['#upload_location']) ? $form['logo']['logo_upload']['#upload_location'] : FALSE;
        $upload_name = implode('_', $form['logo']['logo_upload']['#parents']);
        $upload_validators = isset($form['logo']['logo_upload']['#upload_validators']) ? $form['logo']['logo_upload']['#upload_validators'] : [];

        $file = file_save_upload($upload_name, $upload_validators, $upload_location, 0);
        if ($file) {
          // Put the temporary file in form_values so we can save it on submit.
          $form_state->setValue('logo_upload', $file);
        }
      }
      // If the user provided a path for a logo or favicon file, make sure a
      // file exists at that path.
      if ($form_state->getValue('logo_path')) {
        $path = $this->validatePath($form_state->getValue('logo_path'));
        if (!$path) {
          $form_state->setErrorByName('logo_path', $this->t('The image path is invalid.'));
        }
      }
    }

    if ($form_state->getValue('language')) {
      if (!in_array($form_state->getValue('language'), AopFeed::supportedLanguages())) {
        $form_state->setErrorByName('language', $this->t('Language is invalid.'));
      }
    }
  }

  /**
   * Helper function for the system_theme_settings form.
   *
   * Attempts to validate normal system paths, paths relative to the public
   * files directory, or stream wrapper URIs. If the given path is any of the
   * above, returns a valid path or URI that the theme system can display.
   *
   * @param string $path
   *   A path relative to the Drupal root or to the public files directory, or
   *   a stream wrapper URI.
   *
   * @return mixed
   *   A valid path that can be displayed through the theme system, or FALSE if
   *   the path could not be validated.
   */
  protected function validatePath($path) {
    // Absolute local file paths are invalid.
    if ($this->fileSystem->realpath($path) == $path) {
      return FALSE;
    }
    // A path relative to the Drupal root or a fully qualified URI is valid.
    if (is_file($path)) {
      return $path;
    }
    // Prepend 'public://' for relative file paths within public filesystem.
    if (StreamWrapperManager::getScheme($path) === FALSE) {
      $path = 'public://' . $path;
    }
    if (is_file($path)) {
      return $path;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    try {
      if (!empty($form_state->getValue('logo_upload'))) {
        $filename = $this->fileSystem->copy($form_state->getValue('logo_upload')->getFileUri(), file_default_scheme() . '://');
        $logo_path = $filename;
      }
    }
    catch (FileException $e) {
      // Ignore.
    }

    $status = $this->entity
      ->set('channel_title', $form_state->getValue('channel_title'))
      ->set('website_url', $form_state->getValue('website_url'))
      ->set('feed_description', $form_state->getValue('feed_description'))
      ->set('language', $form_state->getValue('language'))
      ->set('logo_path', !empty($logo_path) ? $logo_path : $form_state->getValue('logo_path'))
      ->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addMessage($this->t('Aop feed "@name" successfully added.', [
        '@name' => $this->entity->label(),
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('Aop feed "@name" was changed..', [
        '@name' => $this->entity->label(),
      ]));
    }
    $form_state->setRedirect('entity.aop_feed.collection');
  }

  /**
   * Checks if the entity exists.
   *
   * @param string $id
   *   The entity machine name.
   *
   * @return bool
   *   TRUE if entity exists, FALSE otherwise.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('aop_feed')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}