<?php namespace ComBank\Support\Traits;

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Bank\BankAccount;
use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTrait
{
        public function validateEmail(string $email): bool{
    
            //I get the url from the public api
            $url = "https://api.usercheck.com/email/$email";
        
    
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            //curl_exec to execute the api and take the results
            $response = curl_exec($ch);
    
            //I save the json from the api in a variable to manage in the code
            $data = json_decode($response,true);
    
            //I close the api
            curl_close($ch);
    
            //Return the result of the status from the json
            if ($data ["status"] == 200){
                return true;
            }else{
                return false;
            }
    
        }
    
    
        function convertBalance(float $euros): float {
            
            $from = "EUR";
            $to = "USD";
    
            //I get the url from the public api
            $url = "https://api.fxfeed.io/v1/convert?api_key=fxf_ghmIud6wxzpKA6cuLZTM&from=$from&to=$to&amount=$euros";
    
            $ch = curl_init();
    
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            
            //curl_exec to execute the api and take the results
            $response = curl_exec($ch);
            
            //I save the json from the api in a variable to manage in the code
            $data = json_decode($response,true);
            
            //I close the api
            curl_close($ch);
    
            //Return the result of the convertedBalance from the json
            return $data ["result"];
        }
    
    
        public function detectFraud(BankTransactionInterface $transaction): bool{
            
            //I get the url from the public api
            $url = "https://673cc1d796b8dcd5f3fb7aff.mockapi.io/Fraud_API/fraudApi";
    
            $ch = curl_init();
    
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    
            //curl_exec to execute the api and take the results
            $response = curl_exec($ch);
            
            //I save the json from the api in a variable to manage in the code
            $data = json_decode($response,true);
    
            //I close the api
            curl_close($ch);
    
            //I define the fraud and I set it as false
            $fraud = false;
    
                    
            //For each to return the result of the action:allowed/blocked from the json
            foreach($data as $key => $value ){
                //I check each record from the api 
                if ($data[$key]['movementType']==$transaction->getTransactionInfo()){
                    //I check the range of the amount 
                    if($data[$key]['amount_max']>$transaction->getAmount() && $data[$key]['amount']<$transaction->getAmount()){
                        //I look if the action is allowed or not
                        if ($data[$key]['action']==BankTransactionInterface::TRANSACTION_BLOCKED){
                            $fraud = true;
                            break;
                        }else{
                            $fraud = false;
                        }
    
                    }
                }
            }
            return $fraud;
        }    
}
