<?php namespace ComBank\Bank;
use ComBank\Support\Traits\ApiTrait;

class InternationalBankAccount extends BankAccount 
{

    
    public function getConvertedBalance(): float{
        return parent::convertBalance($this->balance);
    }

    public function getConvertedCurrency(): string{
        return "USD";
    }
}