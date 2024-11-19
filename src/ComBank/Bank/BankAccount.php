<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Transactions\DepositTransaction;
use Exception;

class BankAccount implements BackAccountInterface {
    // Atributes 
    // Cambiados de privados a protected
    protected float $balance;
    protected String $status;
    protected OverdraftInterface $overdraft;

    // NEW
    protected person $Holder;
    protected float $currency;
    

    
    // Constructor
    public function __construct(float $balance) {
        $this->balance = $balance;
        $this->status = BackAccountInterface::STATUS_OPEN;
        $this->balance;
        $this->overdraft = new NoOverdraft();

    }

    // Getters & Setters
    public function getBalance(): float {
        return $this->balance;
     }
     public function getOverdraft(): OverdraftInterface {
        return $this->overdraft;
        
     }
     public function setBalance(float $balance): void {
        $this -> balance = $balance;
    }

    // Methods
    public function transaction(BankTransactionInterface $trans): void {
            if($this->status === BackAccountInterface::STATUS_CLOSED){
                throw new BankAccountException("cuenta cerrada, no puedes hacer transacciones");
            }else{
                $newBalance = $trans->applyTransaction($this);
                $this->balance = $newBalance;
            }
    }
    public function isOpen(): bool { // He cambiado el nombre del método porque creo que isOpen es lo mismo que open account 
        return $this->status === BackAccountInterface::STATUS_OPEN;
    }
    public function closeAccount(): void { // Este sigue igual
        if($this->status == BackAccountInterface::STATUS_OPEN){
            $this->status = BackAccountInterface::STATUS_CLOSED;
        }else{
            throw new BankAccountException("La cuenta ya está cerrada");
        }
    }
    public function reopenAccount(): void {
        if ($this->status === BackAccountInterface::STATUS_CLOSED) {
            $this->status = BackAccountInterface::STATUS_OPEN;
        }else{
            throw new BankAccountException("esta cuenta ya está abierta");
        }
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void {
        $this -> overdraft = $overdraft;
    }

}
