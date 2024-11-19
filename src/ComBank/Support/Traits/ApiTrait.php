<?php namespace ComBank\Support\Traits;

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Bank\BankAccount;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class ApiTrait {
public function validateEmail(String $email): bool {
    return 0;
}

public function convertBalance(float $covertedBalance): float {
    return $covertedBalance;
}

public function detectFraud(BankTransactionInterface $bankTransactionInterface): bool {
    return 0;  
}
}
