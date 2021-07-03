<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use App\Models\TransactionModel;
use App\Helper\Utils;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Http;
use App\Http\Constants\Constants;
use Validator;

class TransferController extends Controller 
{
    
    public function transferMoney(Request $request) 
    { 
        //print_r('Elijah'); exit;
        $validator = Validator::make($request->all(), [ 
            'bank' => 'required', 
            'account_number' => 'required', 
            'amount' => 'required', 
            'description' => 'required'
        ]);

        if ($validator->fails()) { 
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 401);            
        }

        $reference_number = self::generateRandomNumber(20);

        $secret_key = env('FLUTTERWAVE_SECRET');

        $payload = [
            'account_bank' => $request->bank,
            'account_number' => $request->account_number,
            'amount' => $request->amount,
            'narration' => $request->description,
            'currency' => 'NGN',
            'debit_currency' => 'NGN',
            'reference' => $reference_number
        ];

        $flutter_response = Http::withHeaders([
            'Authorization' => 'Bearer ' . 'FLWSECK_TEST-0ae450091f968bab8b9ae054f03a76fc-X'//$secret_key
        ])->post(Constants::BASE . Constants::TRANSFER, $payload);

        if($flutter_response->failed()){
            return response()->json([
                'status'   => false,
                'message'   => 'Transfer failed'
            ], 400);
        } 

        if($flutter_response['status'] == 'error'){
            throw new \App\Exceptions\TransferValidationException($flutter_response['message']);
        }

        $response = $flutter_response ->json();

        $transaction = new TransactionModel();
        $transaction->reference = $response['data']['reference'];
        $transaction->transaction_id = $response['data']['id'];
        $transaction->gateway = 'flutterwave';
        $transaction->amount = $request->amount;
        $transaction->narration = $response['data']['narration'];
        $transaction->transaction_type = 'debit';
        $transaction->paid_at = date('Y-m-d H:i:s');
        $transaction->account_number = $response['data']['account_number'];
        $transaction->bank_name = $response['data']['bank_name'];
        $transaction->account_name = $response['data']['full_name'];
        $transaction->currency = $response['data']['currency'];
        $transaction->save();

        return response()->json([
            'status'    => true,
            'data'  => [
                'response' => $response,
                'transaction'=> $transaction
            ]
        ]);
        
    }

    public function listTransactions(Request $request) 
    { 
        $transactions = TransactionModel::all();

        if(!$transactions){
            return response()->json([
                'status' => false,
                'message' => 'Transactions not found'
            ], 404); 
        }

        return response()->json([
            'status' => true,
            'message' => $transactions
        ], 200);
    }

    public static function generateRandomNumber($length) {
        $result = '';
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

}