<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Support\Traits\ApiTrait;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Bank\NationalBankAccount;
use ComBank\Bank\Contracts\Person;

require_once 'bootstrap.php';



//---[Bank account 1]---/

$bankAccount1 = new BankAccount(400.0);
$bankAccount1->applyOverdraft(new NoOverdraft());
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    pr( "Saldo inicial: " . $bankAccount1->getBalance());
    
    // close account
    $bankAccount1->closeAccount();
    // reopen account
    $bankAccount1->reopenAccount();

    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new DepositTransaction(150.0));
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(25.0));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(600.0));

} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());


//---[Bank account 2]---/

$bankAccount2 = new BankAccount(200.0);
$bankAccount2->applyOverdraft(new SilverOverdraft());

pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    
    // show balance account
    pr( "Saldo inicial: " . $bankAccount2->getBalance());
    // deposit +100
    $bankAccount2->transaction(new DepositTransaction(100.0));
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300

    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300));
    pl('My new balance after withdrawal (-300): ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50));

    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120));

} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(20));
    
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());
try {
   $bankAccount2->closeAccount();
   pl("My account is now closed");
   $bankAccount2->closeAccount();

} catch (BankAccountException $e) {
    pl($e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

// Crea una instancia de BankAccount
$bankAccount5 = new BankAccount(500.0);

//Validaciones

pl('--------- [Start testing bank national account (no conversion)] --------');

    $nationalAccount = new NationalBankAccount(500 , null, "EUR");
    pl('My balance '. $nationalAccount->getBalance() .' € ('. $nationalAccount->getCurrency() .')');

    //---[Bank account International]---/
    // create a new International Account with balance 300

    pl('--------- [Start testing bank International account (Dollar conversion)] --------');
    $internationalAccount = new InternationalBankAccount(300, null, "EUR");

    pl('My balance '. $internationalAccount->getBalance() . ' € ('. $internationalAccount->getCurrency() .')');

    $currentBalance = $internationalAccount->getBalance();
    pl('Converting balance to Dollars '. $internationalAccount->convertBalance($currentBalance));

    //---[PERSON'S EMAIL]---/
    // create a new Person  and test his/her email

    pl('--------- [Start testing EMAIL] --------');
    $person1 = new Person("john.doe@example.com", "54559040G", "Guillem", null);

    pl('--------- [Start testing EMAIL] --------');
    $person1 = new Person("john.doe@invalid-email", "54559040G", "Guillem", null);


    // Test of different transactions
     //---[Bank account 3]---/
    // Account with balance 5000

    pl('--------- [Start testing bank account (Fraud API)] --------');
    $bankAccount3 = new BankAccount(5001);

    pl(mixed: 'Doing transaction withdrawal (-5001) with current balance : ' . $bankAccount3->getBalance());
    try{
        $bankAccount3->transaction(bankTransaction:new WithdrawTransaction(5001) );

    } catch(FailedTransactionException $e){
        pl($e->getMessage());
    }
    pl('My balance '. $bankAccount3->getBalance() . ' € ('. $bankAccount3->getCurrency() .')');


    pl(mixed: 'Doing transaction withdrawal (-5000) with current balance : ' . $bankAccount3->getBalance());
    try{
        $bankAccount3->transaction(bankTransaction:new WithdrawTransaction(5000) );

    } catch(FailedTransactionException $e){
        pl($e->getMessage());
    }

    pl('My balance '. $bankAccount3->getBalance() . ' € ('. $bankAccount3->getCurrency() .')');


    pl(mixed: 'Doing transaction deposit (+20000) with current balance : ' . $bankAccount3->getBalance());
    try{
        $bankAccount3->transaction(bankTransaction:new DepositTransaction(20000) );

    } catch(FailedTransactionException $e){
        pl($e->getMessage());
    }
    pl('My balance '. $bankAccount3->getBalance() . ' € ('. $bankAccount3->getCurrency() .')');


    pl(mixed: 'Doing transaction deposit (+20001) with current balance : ' . $bankAccount3->getBalance());
    try{
        $bankAccount3->transaction(bankTransaction:new DepositTransaction(20001) );

    } catch(FailedTransactionException $e){
        pl($e->getMessage());
    }
    pl('My balance '. $bankAccount3->getBalance() . ' € ('. $bankAccount3->getCurrency() .')');








