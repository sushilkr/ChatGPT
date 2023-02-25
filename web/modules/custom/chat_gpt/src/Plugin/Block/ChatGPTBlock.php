<?php
namespace Drupal\chat_gpt\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ChatGPTBlock' block.
 *
 * @Block(
 *  id = "chatgpt_block",
 *  admin_label = @Translation("Chat GPT"),
 * )
 */

Class ChatGPTBlock extends BlockBase{
    /**
  * {@inheritdoc}
  */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\chat_gpt\Form\ChatGPTForm');
    return $form;
  }
}