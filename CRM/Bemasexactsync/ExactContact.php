<?php

class CRM_Bemasexactsync_ExactContact {
  public $account = '';

  public function __construct(int $exactId) {
    $exactOL = new CRM_Exactonline_Utils();
    $exactOL->exactConnection->connect();

    // find the customer
    $customerFinder = new \Picqer\Financials\Exact\Account($exactOL->exactConnection);
    $c = $customerFinder->filter("trim(Code) eq '$exactId'");
    if (count($c) !== 1) {
      throw new Exception("klant met exact ID = $exactId niet gevonden");
    }
    $this->account = $c[0];

  }

}
