<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    
    public function applyTransaction(BackAccountInterface $account) : float{

        if(!$account->getOverdraft()->isGrantOverdraftFunds($account->getBalance() - $this->getAmount())){
            throw new FailedTransactionException("No es una cuenta overdraft.");
        }elseif($account->getOverdraft()->isGrantOverdraftFunds($account->getBalance() - $this->getAmount())) {
            return $account->getBalance() - $this->getAmount();
        }else{
            throw new InvalidOverdraftFundsException("No es una cuenta overdraft.");
        }
    }

    public function getTransactionInfo() : string{
        return "WITHDRAW_TRANSACTION";
    }
   
}
