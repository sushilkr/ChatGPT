<?php

namespace Drupal\chat_gpt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Implements the ChatGPT form.
 */
class ChatGPTForm extends FormBase {

    /**
     * The OpenAI API client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $openaiClient;

    /**
     * Constructs a new ChatGPTForm object.
     */
    public function __construct(\GuzzleHttp\Client $openai_client) {
        $this->openaiClient = $openai_client;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        $openai_client = $container->get('chat_gpt.openai_client');
        return new static($openai_client);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'chat_gpt_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Message'),
            '#description' => $this->t('Enter your message.'),
            '#required' => TRUE,
        ];

        $form['#attached']['library'][] = 'chat_gpt/chat-gpt-lib';

        $form['lang_sel'] = [
            '#type' => 'select',
            '#title' => 'Select language',
            '#options' => [
                NULL => 'Choose Language',
                'English' => 'English',
                'French' => 'French',
                'Hindi' => 'Hindi',
                'Spanish' => 'Spanish',
            ],
            '#ajax' => [
                'callback' => '::promptCallback',
                'wrapper' => 'replace-textfield-container',
            ],
        ];

        $form['replace_textfield_container'] = [
            '#type' => 'container',
            '#attributes' => ['id' => 'replace-textfield-container'],
        ];
        $form['replace_textfield_container']['replace_textfield'] = [
            '#type' => 'textarea',
            '#title' => $this->t("Translated message"),
        ];

        $lang_val = $form_state->getValue('lang_sel');
        if ($lang_val !== NULL) {
            $gen_resp = $this->gptAPICall($lang_val, $form_state->getValue('message'));
            $form['replace_textfield_container']['replace_textfield']['#value'] = $gen_resp;
        }

         $form['chatgpt_result'] = [
          '#type' => 'markup',
          '#markup' => '<a id="copy_button" class="button btn" style="display: inline-block;" data-clipboard-action="copy" data-clipboard-target="#edit-replace-textfield" >Copy and Paste</a>',
          ];

        $form['reset'] = [
            '#type' => 'button',
            '#button_type' => 'reset',
            '#value' => t('Clear '),
            '#weight' => 9,
            '#validate' => [],
            '#attributes' => [
                'onclick' => 'this.form.reset(); return false;',
            ],
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Send'),
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $message = $form_state->getValue('message');
        $lang_sel = $form_state->getValue('lang_sel');

        \Drupal::messenger()->addMessage("Input: " . $message . " Language: " . $lang_sel);
    }

    /**
     * Handles switching the available regions based on the selected theme.
     */
    public function promptCallback($form, FormStateInterface $form_state) {
        return $form['replace_textfield_container'];
    }

    public function gptAPICall($lang_sel, $message) {
        $config = $this->config('chat_gpt.chatgptconfig');
        $endpoint = $config->get('chatgpt_api_endpoint');
        $model = $config->get('chatgpt_api_model');
        $access_token = $config->get('chatgpt_api_access_token');
        $temperature = (int) $config->get('chatgpt_api_temperature');
        $max_tokens = (int) $config->get('chatgpt_api_max_token');
        /* echo "endpoint-". $endpoint;
          echo "<br>";
          echo "model-".$model;
          echo "<br>";
          echo "access_token-". $access_token;
          echo "<br>";
          echo "temp-". $temperature;
          echo "<br>";
          echo "max_tokens-". $max_tokens;
          echo "<br>";
          exit; */

        $data = [
            'model' => $model,
            'prompt' => "Translate this into 1. $lang_sel:\n\n $message \n\n.",
            'temperature' => $temperature,
            'max_tokens' => $max_tokens,
            "top_p" => 1.0,
            "frequency_penalty" => 0.0,
            "presence_penalty" => 0.0
        ];

        $response = $this->openaiClient->request('POST', $endpoint, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
            ],
            'json' => $data,
        ]);

        $response_body = $response->getBody()->getContents();
        $response_data = Yaml::parse($response_body);
        return $response_data['choices'][0]['text'];
    }

}
