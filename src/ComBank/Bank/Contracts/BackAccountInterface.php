<?php namespace ComBank\Bank\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:26 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

interface BackAccountInterface
{
    const STATUS_OPEN = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';
    
    public function getBalance(): float;
    public function getOverdraft(): OverdraftInterface;

    //NEW

    public function transaction(BankTransactionInterface $bankTransactionInterface): void;
    public function isOpen(): bool;
    public function reopenAccount(): void;
    public function closeAccount(): void;
    public function applyOverdraft(OverdraftInterface $overdraftInterface): void;
    public function setBalance(float $newBalance): void;

    
}
