<?php

/**
 * @file
 * Contains \Drupal\helhist_map\MapService.
 *
 */

namespace Drupal\helhist_map;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;

class MapService {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function getMapLayers() {
    $layers = [];
    $map_layer_nodes = $this->getMapLayerNodes();
    
    if (!$map_layer_nodes || empty($map_layer_nodes)) {
      return $layers;
    }
    
    foreach ($map_layer_nodes as $node) {
      $layer_title = $node->get('field_layer_title')->getString();
      $map_layer_api_endpoints_field = $node->get('field_map_api_endpoints');

      $layer_api_endpoints = [];
      foreach ($map_layer_api_endpoints_field as $endpoint) {
        $endpoint_paragraph = $endpoint->entity;

        $layer_api_endpoints[] = [
          'map_api' => $endpoint_paragraph->get('field_map_api')->getString(),
          'wms_title' => $endpoint_paragraph->get('field_map_wms_title')->getString()
        ];
      }

      $layers[$layer_title] = [
        'layer_title' => $layer_title,
        'map_api_endpoints' => json_encode($layer_api_endpoints)
      ];
    }

    return $layers;
  }

  private function getMapLayerNodes() {
    $query = $this->entityTypeManager
      ->getListBuilder('node')
      ->getStorage()
      ->getQuery();
    $query->condition('type', 'map_layer');
    $query->condition('status', 1);
    $query->sort('field_layer_title', 'DESC');

    $map_layer_nids = $query->execute();
    
    if (empty($map_layer_nids)) {
      return [];
    }

    $map_layer_nodes = Node::loadMultiple($map_layer_nids);

    return $map_layer_nodes;
  }
}