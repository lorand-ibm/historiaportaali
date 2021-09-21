<?php

/**
 * @file
 * Contains \Drupal\helhist_map\Plugin\Block\MapControlsBlock.
 */

namespace Drupal\helhist_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a Map Controls block
 *
 * @Block(
 *   id = "helhist_map_map_controls_block",
 *   admin_label = @Translation("HelHist Map Controls"),
 *   category = @Translation("HelHist")
 * )
 */
class MapControlsBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#theme' => 'map_controls',
      '#map_eras' => $this->getMapEras()
    ];

    return $build;
  }

  private function getMapEras() {
    $query = \Drupal::entityTypeManager()
      ->getListBuilder('node')
      ->getStorage()
      ->getQuery();
    $query->condition('type', 'map_layer');
    $query->condition('status', 1);
    $query->sort('field_map_era', 'DESC');

    $map_layer_nids = $query->execute();
    
    if (empty($map_layer_nids)) {
      return [];
    }

    $map_layer_nodes = Node::loadMultiple($map_layer_nids);

    $eras = [];
    foreach ($map_layer_nodes as $node) {
      $map_era = $node->get('field_map_era')->getString();

      $eras[$map_era] = [
        'title' => $map_era,
        'map_api' => $node->get('field_map_api')->getString(),
        'map_wms_title' => $node->get('field_map_wms_title')->getString()
      ];
    }

    return $eras;
  }
}