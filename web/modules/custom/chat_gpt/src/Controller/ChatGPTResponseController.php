<?php

namespace Drupal\chat_gpt\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Implements a ChatGPT response controller.
 */
class ChatGPTResponseController extends ControllerBase {

  /**
   * Display the ChatGPT response.
   */
  public function displayResponse($message, $response) {
    $content = [
      '#type' => 'container',
      '#attributes' => ['class' => ['chat-gpt-response']],
      'message' => [
        '#markup' => $this->t('You said: @message', ['@message' => $message]),
      ],
      'response' => [
        '#markup' => $this->t('ChatGPT says: @response', ['@response' => $response]),
      ],
    ];
    return new Response(render($content));
  }

}
