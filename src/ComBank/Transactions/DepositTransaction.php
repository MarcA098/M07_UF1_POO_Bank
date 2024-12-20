<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{
    public function applyTransaction(BackAccountInterface $account) : float{
        return $account->getBalance() + $this->getAmount();
    }

    public function getTransactionInfo() : string{
        return "DEPOSIT_TRANSACTION";
    }
   
}
