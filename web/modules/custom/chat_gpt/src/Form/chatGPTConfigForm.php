<?php

namespace Drupal\chat_gpt\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class chatGPTConfigForm.
 */
class chatGPTConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'chat_gpt.chatgptconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'chat_g_p_t_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('chat_gpt.chatgptconfig');
    $form['chatgpt_api_endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Endpoint'),
      '#description' => $this->t('Enter the ChatGPT API Endpoint'),
      '#maxlength' => 128,
      '#size' => 128,
      '#default_value' => $config->get('chatgpt_api_endpoint'),
    ];
    $form['chatgpt_api_model'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Model'),
      '#description' => $this->t('Enter ChatGPT API Model <a href="https://beta.openai.com/docs/models/gpt-3" target="_blank"> View </a>'),
      '#maxlength' => 128,
      '#size' => 128,
      '#default_value' => $config->get('chatgpt_api_model'),
    ];
    $form['chatgpt_api_access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Access Token'),
      '#description' => $this->t('Enter the ChatGPT Access Token'),
      '#maxlength' => 128,
      '#size' => 128,
      '#default_value' => $config->get('chatgpt_api_access_token'),
    ];
    $form['chatgpt_api_max_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Max Token'),
      '#description' => $this->t('Enter the ChatGPT max token here to limit the output words. 1 token is approx 4 chars in English. We can verify from <a href="https://platform.openai.com/tokenizer" target="blank"> here </a> to count number of tokens for your text.'),
      '#maxlength' => 128,
      '#size' => 128,
      '#default_value' => $config->get('chatgpt_api_max_token'),
    ];
    $form['chatgpt_api_temperature'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ChatGPT API Temperature'),
      '#description' => $this->t('Enter the Temperature value here. Please set it to 0.9 for most creative output.'),
      '#maxlength' => 128,
      '#size' => 128,
      '#default_value' => $config->get('chatgpt_api_temperature'),
    ];
    $form['chatgpt_api_language'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Language'),
      '#description' => $this->t('Enter the languages with comma separeted.'),
      '#default_value' => $config->get('chatgpt_api_language'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('chat_gpt.chatgptconfig')
      ->set('chatgpt_api_endpoint', $form_state->getValue('chatgpt_api_endpoint'))
      ->set('chatgpt_api_model', $form_state->getValue('chatgpt_api_model'))
      ->set('chatgpt_api_access_token', $form_state->getValue('chatgpt_api_access_token'))
      ->set('chatgpt_api_max_token', $form_state->getValue('chatgpt_api_max_token'))
      ->set('chatgpt_api_temperature', $form_state->getValue('chatgpt_api_temperature'))
      ->set('chatgpt_api_language', $form_state->getValue('chatgpt_api_language'))
      ->save();
  }

}
