<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CRM_Bemasexactsync_Cleanup {
  public function __construct() {
    CRM_Bemasexactsync_CleanupCustomFields::init();
  }

  public function import($inputFileName) {
    $sheet = $this->getExcelSheet($inputFileName);

    $i = 2;
    while (!empty($sheet->getCell("A$i")->getValue())) {
      $this->importRow($sheet, $i);
      $i++;
    }
  }

  private function getExcelSheet(string $inputFileName): \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet {
    $reader = new Xlsx();
    $spreadsheet = $reader->load($inputFileName);
    return $spreadsheet->getActiveSheet();
  }

  private function importRow($sheet, $i) {
    $row = $this->readRowAsArray($sheet, $i);
    $contactId = $this->getContactByExactId($row['Code']);
    if ($contactId) {
      //$this->storeExactData($contactId, $row);
      echo "$contactId gevonden\n";
    }
  }

  private function readRowAsArray($sheet, $i): array {
    $colHeaders = [
      'A' => 'Code',
      'B' => 'Naam',
      'C' => 'Adres',
      'D' => 'Adresregel 2',
      'E' => 'Postcode',
      'F' => 'Plaats',
      'G' => 'Land',
      'H' => 'Klant',
      'I' => 'Leverancier',
      'J' => 'Btw-nummer',
      'K' => 'Btw-regime',
      'L' => 'Contactpersoon',
      'M' => 'E-Mailadres',
    ];

    $row = [];
    foreach ($colHeaders as $colLetter => $colName) {
      $row[$colName] = $sheet->getCell($colLetter . $i)->getFormattedValue();
    }

    return $row;
  }

  private function getContactByExactId($exactId): int {
    $contacts = \Civi\Api4\Contact::get(FALSE)
      ->selectRowCount()
      ->addSelect('id')
      ->addWhere('Organization_details.POPSY_ID', '=', $exactId)
      ->execute();

    if ($contacts->countMatched() == 0) {
      echo "Contact met Exact Code = $exactId niet gevonden\n";
      return 0;
    }
    elseif ($contacts->countMatched() == 1) {
      return $contacts[0]['id'];
    }
    else {
      echo "Contact met Exact Code = $exactId " . $contacts->countMatched() . " keer gevonden\n";
      return 0;
    }
  }

  private function storeExactData($contactId, $row) {
    $results = \Civi\Api4\Contact::update(FALSE)
      ->addValue('temp_exact_check.exact_data', json_encode($row))
      ->addWhere('id', '=', $contactId)
      ->execute();
  }
}
