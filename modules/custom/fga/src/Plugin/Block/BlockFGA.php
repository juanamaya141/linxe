<?php

    namespace Drupal\fga\Plugin\Block;
    use Drupal\Core\Block\BlockBase;

    /**
     * Provides a 'DefaultBlock' block.
     *
     * @Block(
     *  id = "example_block",
     *  admin_label = @Translation("Example block"),
     * )
     */
    class BlockFGA extends BlockBase {

        /**
         * {@inheritdoc}
         */
        public function build() {
		    return array (
                '#prefix' => '',
                '#suffix' => '',
                '#markup' => $this->t('Hello World!'),
                '#cache' => ['max-age' => 0]
            ); 
        }
    }