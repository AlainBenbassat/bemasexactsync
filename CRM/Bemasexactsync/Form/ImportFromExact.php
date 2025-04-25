<?php

use CRM_Bemasexactsync_ExtensionUtil as E;
class CRM_Bemasexactsync_Form_ImportFromExact extends CRM_Core_Form {
  private $sourceExactId = NULL;
  private $targetContactId = NULL;

  private $exactFields = [
    'Name', 'AddressLine1', 'AddressLine2', 'AddressLine3', 'City', 'Country', 'Postcode', 'Language', 'Email', 'Phone', 'VATLiability', 'VATNumber', 'PeppolIdentifierType', 'PeppolIdentifier'
  ];

  public function buildQuickForm(): void {
    $this->addFormTitle();
    $this->addFormElements();
    $this->addFormButtons();

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function setDefaultValues() {
    $defaults = parent::setDefaultValues();

    $exactContact = new CRM_Bemasexactsync_ExactContact($this->getSourceExactId());
    //$civiContact = new CRM_Bemasexactsync_CiviContact($this->getTargetContactId());

    foreach ($this->exactFields as $field) {
      $defaults["exact_$field"] = $exactContact->$field;
    }

    $defaults['test'] = print_r($exactContact, TRUE);

    return $defaults;
  }

  public function postProcess(): void {
    $values = $this->exportValues();

    parent::postProcess();
  }

  private function addFormTitle() {
    $this->setTitle('Importeren van ');
  }

  private function addFormElements() {
    $this->add('hidden', 'exact_id', $this->getSourceExactId());
    $this->add('hidden', 'contact_id', $this->getTargetContactId());
    //$this->add('text', 'title', E::ts('Title'), [], TRUE);
    foreach ($this->exactFields as $field) {
      $this->add('text', "exact_$field", $field, [], FALSE);
    }
    $this->add('wysiwyg', 'test', 'Debug', []);
  }

  private function getSourceExactId() {
    if ($this->sourceExactId === NULL) {
      $this->sourceExactId = CRM_Utils_Request::retrieve('exact_id', 'Positive');
    }

    if (empty($this->sourceExactId)) {
      throw new Exception('Geen Exact ID gevonden');
    }

    return $this->sourceExactId;
  }

  private function getTargetContactId() {
    if ($this->targetContactId === NULL) {
      $this->targetContactId = CRM_Utils_Request::retrieve('contact_id', 'Positive');
    }

    if (empty($this->targetContactId)) {
      throw new Exception('Geen Contact ID gevonden');
    }

    return $this->targetContactId;
  }

  private function addFormButtons() {
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => 'Importeren',
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ],
    ]);
  }

  private function getRenderableElementNames(): array {
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
