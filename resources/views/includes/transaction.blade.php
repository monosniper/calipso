<div class="transaction">
    <div class="transaction-col fb-2">{{$transaction->created_at->diffForHumans()}}</div>
    <div class="transaction-col fb-2">{{__('main.pay_type.'.$transaction->type)}}</div>
    <div class="transaction-col fb-5">{{$transaction->meta ? $transaction->meta['description'] : ''}}</div>
    <div class="transaction-col fb-1">{{$transaction->amount}}$</div>
</div>
