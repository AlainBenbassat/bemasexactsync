<?php

require_once 'bemasexactsync.civix.php';

use CRM_Bemasexactsync_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function bemasexactsync_civicrm_config(&$config): void {
  _bemasexactsync_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function bemasexactsync_civicrm_install(): void {
  _bemasexactsync_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function bemasexactsync_civicrm_enable(): void {
  _bemasexactsync_civix_civicrm_enable();
}
