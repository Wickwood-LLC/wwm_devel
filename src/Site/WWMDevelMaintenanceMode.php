<?php

namespace Drupal\wwm_devel\Site;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Site\MaintenanceMode;

/**
 * Extends core maintenance mode service class
 */
class WWMDevelMaintenanceMode extends MaintenanceMode {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $config = \Drupal::service('config.factory')->get('wwm_devel.settings');

    if (!parent::applies($route_match) && !$config->get('maintenance_mode')) {
      return FALSE;
    }

    if ($route = $route_match->getRouteObject()) {
      if ($route->getOption('_maintenance_access')) {
        return FALSE;
      }
    }

    return TRUE;
  }

}
