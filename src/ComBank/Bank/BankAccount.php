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
use ComBank\Support\Traits\ApiTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use PhpParser\Node\Stmt\Return_;
use ComBank\Bank\Contracts\Person;

class BankAccount implements BackAccountInterface
{
    use ApiTrait;

    protected ? Person $holder;
    protected $balance;
    protected $status;
    
    protected $overdraft;

    protected $currency;



    function __construct($balance, OverdraftInterface $overdraft = null, string $currency = "EUR", Person $holder = null){
        $this->balance = $balance;
        $this->status = BackAccountInterface::STATUS_OPEN;


        if( $overdraft === null ){
            $this->overdraft = new NoOverdraft();
        } else {
            $this->overdraft = $overdraft;
        }

        if( $currency === "USD" ){
            $this->currency = "USD";
        } else {
            $this->currency = "EUR";
        }

        $this->holder = $holder;

    }
    public function getBalance() :float{
        return $this->balance;
    }

    public function getOverdraft() :OverdraftInterface{
        return $this->overdraft; 
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void{
        $this->overdraft = $overdraft;
    }

    public function setBalance($float) : void{
        $this->balance = $float;

    }

    public function getCurrency(){
        return $this->currency;
    }
    public function transaction(BankTransactionInterface $bankTransaction): void {
        if($this->status === BackAccountInterface::STATUS_OPEN){
            try{
                $this->setBalance($bankTransaction->applyTransaction($this));
            }catch(InvalidOverdraftFundsException $e){
                throw new FailedTransactionException($e->getMessage(), $e->getCode(), $e);
            }
        }else{
            throw new BankAccountException("ERROR");
        }
        
    }

    public function isOpen(): bool {
        return $this->status === BackAccountInterface::STATUS_OPEN;
    }
    
    public function openAccount() : bool{
        if($this->status === BackAccountInterface::STATUS_OPEN){
            return true;
        }
        else{
            return false;
        }    
    }

    public function reopenAccount() : void{
        if($this->status === BackAccountInterface::STATUS_OPEN){
            throw new BankAccountException("Error: Account is already open");
        }
        else{
            $this->status = BackAccountInterface::STATUS_OPEN;
            echo("<br> My account is now opened <br>");
        }
       
    }

    public function closeAccount() : void{
        if($this->status === BackAccountInterface::STATUS_CLOSED){
            throw new BankAccountException("Error: Account is already closed");

        }else{
            $this->status = BackAccountInterface::STATUS_CLOSED;
            echo("<br> My account is now closed <br>");
        }
        
    }
}