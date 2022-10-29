@extends('layouts.dashboard')

@section('title')
    {{__('main.pay_history')}}
@endsection

@section('content')
    <div class="grey_block">
        <div class="profile_title">{{__('main.pay_history')}}</div>

        <div class="transactions-table">
            <div class="transactions-table-header">
                <a href="{{route('dashboard.pay-history', ['sort' => 'createdAt', 'direction' => request('sort') === 'createdAt' && request('direction') === 'asc' ? 'desc' : 'asc'])}}" class="transactions-table-header-item fb-2">
                    {{__('main.date')}}
                    @if(request('sort') === 'createdAt')
                        <i class="fas fa-long-arrow-alt-{{request('direction') === 'asc' ? 'down' : 'up'}}"></i>
                    @endif
                </a>
                <a class="transactions-table-header-item fb-2">{{__('main.type')}}</a>
                <a class="transactions-table-header-item fb-5">{{__('main.description')}}</a>
                <a href="{{route('dashboard.pay-history', ['sort' => 'amount', 'direction' => request('sort') === 'amount' && request('direction') === 'asc' ? 'desc' : 'asc'])}}" class="transactions-table-header-item fb-1">
                    {{__('main.amount')}}
                    @if(request('sort') === 'amount')
                        <i class="fas fa-long-arrow-alt-{{request('direction') === 'asc' ? 'down' : 'up'}}"></i>
                    @endif
                </a>
            </div>
            <div class="transactions-table-body">
                @forelse($transactions as $transaction)
                    @include('includes.transaction', ['$transaction' => $transaction])
                @empty
                    {{__('dashboard.no_transactions')}}
                @endforelse
            </div>
        </div>

        {{$transactions->links()}}
    </div>
@endsection
