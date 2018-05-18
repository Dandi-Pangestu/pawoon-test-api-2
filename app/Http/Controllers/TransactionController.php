<?php

namespace App\Http\Controllers;

use App\Http\Models\Transaction;
use App\Http\Models\TransactionItem;
use App\Http\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class TransactionController extends Controller
{

    public function sync(Request $request) {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $user = User::where('uuid', $input['user_uuid'])->first();
            foreach ($input['transactions'] as $trx) {
                $transaction = new Transaction();
                $transaction->uuid = Uuid::uuid4()->toString();
                $transaction->user_id = $user->id;
                $transaction->device_timestamp = Carbon::parse($trx['device_timestamp']);
                $transaction->payment_method = $trx['payment_method'];
                $transaction->total_amount = $trx['total_amount'];
                $transaction->paid_amount = $trx['paid_amount'];
                $transaction->change_amount = $trx['change_amount'];

                if ($transaction->save()) {
                    foreach ($trx['items'] as $item) {
                        $transaction_item = new TransactionItem();
                        $transaction_item->uuid = Uuid::uuid4()->toString();
                        $transaction_item->transaction_id = $transaction->id;
                        $transaction_item->title = $item['title'];
                        $transaction_item->qty = $item['qty'];
                        $transaction_item->price = $item['price'];
                        $transaction_item->save();
                    }
                } else {
                    return response()->json(['message' => 'Error'], 500);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Success'], 200);
    }
}
