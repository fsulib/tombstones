<?php

namespace Drupal\tombstones\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TombstonesSettingsForm.
 */
class TombstonesSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tombstones_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['tombstones.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('tombstones.settings');

    $form['tombstones_paused'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Pause Tombstone creation?'),
      '#description' => $this->t('Enable to pause the creation of Tombstone nodes. New Tombstone nodes will not be created when other nodes are deleted if this setting is enabled.'),
      '#default_value' => ( !is_null($config->get('tombstones_paused')) ? $config->get('tombstones_paused') : FALSE ),
    ];

    $form['tombstones_use_hooks'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Activate Tombstone node creation via Drupal node deletion hooks?'),
      '#description' => $this->t('Enable to have new Tombstone nodes created automatically when other nodes are deleted via Drupal node deletion hooks. Disable if you are creating Tombstone nodes in a different way.'),
      '#default_value' => ( !is_null($config->get('tombstones_use_hooks')) ? $config->get('tombstones_use_hooks') : TRUE ),
    ];

    $bundles = \Drupal::entityManager()->getBundleInfo('node');
    $options = [];
    foreach ($bundles as $key => $value) {
      if ($key != 'tombstone') {
        $options[$key] = $value['label'];
      }
    }
    $form['tombstones_ctypes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('What content types should get Tombstones on deletion?'),
      '#description' => $this->t('Enable each content type that should get a Tombstone on deletion.'),
      '#default_value' => ( !is_null($config->get('tombstones_ctypes')) ? $config->get('tombstones_ctypes') : FALSE),
      '#options' => $options,
    ];
   

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('tombstones.settings');
    $config->set('tombstones_paused', $form_state->getValue('tombstones_paused'));
    $config->set('tombstones_use_hooks', $form_state->getValue('tombstones_use_hooks'));
    $config->set('tombstones_ctypes', $form_state->getValue('tombstones_ctypes'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
