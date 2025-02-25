<?php

class CRM_Bemasexactsync_CleanupCustomFields {
  public static function init() {
    $customGroup = self::getOrCreateCustomGroup();
    self::getOrCreateCustomfields($customGroup['id']);
  }

  public static function getOrCreateCustomGroup() {
    $name = 'temp_exact_check';
    $title = 'Temp Exact Check';

    $customGroup = \Civi\Api4\CustomGroup::get(FALSE)
      ->addWhere('name', '=', $name)
      ->execute()
      ->first();

    if ($customGroup) {
      return $customGroup;
    }

    $customGroups = \Civi\Api4\CustomGroup::create(FALSE)
      ->addValue('name', $name)
      ->addValue('title', $title)
      ->addValue('extends', 'Organization')
      ->addValue('style', 'Inline')
      ->addValue('collapse_display', false)
      ->addValue('is_active', true)
      ->addValue('table_name', 'civicrm_value_exact_cleanup')
      ->addValue('is_multiple', false)
      ->addValue('collapse_adv_display', false)
      ->addValue('is_reserved', false)
      ->addValue('is_public', false)
      ->execute();

    $customGroupId = $customGroups[0]['id'];

    \Civi\Api4\CustomGroup::update(FALSE)
      ->addValue('title', $title)
      ->addWhere('id', '=', $customGroupId)
      ->setLanguage('nl_NL')
      ->execute();

    \Civi\Api4\CustomGroup::update(FALSE)
      ->setLanguage('fr_FR')
      ->addValue('title', $title)
      ->addWhere('id', '=', $customGroupId)
      ->execute();

    \Civi\Api4\CustomGroup::update(FALSE)
      ->setLanguage('en_US')
      ->addValue('title', $title)
      ->addWhere('id', '=', $customGroupId)
      ->execute();

    return $customGroups[0];
  }

  public static function getOrCreateCustomFields(int $customGroupId) {
    self::getOrCreateCustomfieldExactData($customGroupId);
  }

  public static function getOrCreateCustomfieldExactData(int $customGroupId) {
    $name = 'exact_data';
    $label = 'Exact Data';

    $customField = \Civi\Api4\CustomField::get(FALSE)
      ->addWhere('name', '=', $name)
      ->execute()
      ->first();

    if ($customField) {
      return $customField;
    }

    $customFields = \Civi\Api4\CustomField::create(FALSE)
      ->addValue('name', $name)
      ->addValue('label', $label)
      ->addValue('custom_group_id', $customGroupId)
      ->addValue('data_type', 'Memo')
      ->addValue('html_type', 'TextArea')
      ->addValue('default_value', null)
      ->addValue('is_required', false)
      ->addValue('is_searchable', false)
      ->addValue('is_search_range', false)
      ->addValue('weight', 200)
      ->addValue('attributes', 'rows=4, cols=60')
      ->addValue('is_active', true)
      ->addValue('is_view', false)
      ->addValue('note_columns', 60)
      ->addValue('note_rows', 4)
      ->addValue('column_name', 'exact_data')
      ->addValue('option_group_id', null)
      ->addValue('start_date_years', null)
      ->addValue('end_date_years', null)
      ->addValue('date_format', null)
      ->addValue('time_format', null)
      ->addValue('serialize', 0)
      ->addValue('filter', null)
      ->addValue('in_selector', false)
      ->addValue('fk_entity', null)
      ->addValue('fk_entity', 'set_null')
      ->execute();

    $customFieldId = $customFields[0]['id'];

    \Civi\Api4\CustomField::update(FALSE)
      ->setLanguage('nl_NL')
      ->addValue('title', $label)
      ->addWhere('id', '=', $customFieldId)
      ->execute();

    \Civi\Api4\CustomField::update(FALSE)
      ->setLanguage('fr_FR')
      ->addValue('title', $label)
      ->addWhere('id', '=', $customFieldId)
      ->execute();

    \Civi\Api4\CustomField::update(FALSE)
      ->setLanguage('en_US')
      ->addValue('title', $label)
      ->addWhere('id', '=', $customFieldId)
      ->execute();

    return $customFields[0];
  }
}
