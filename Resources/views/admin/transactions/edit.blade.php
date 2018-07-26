@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('order::transactions.title.edit transaction') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.order.transaction.index') }}">{{ trans('order::transactions.title.transactions') }}</a></li>
        <li class="active">{{ trans('order::transactions.title.edit transaction') }}</li>
    </ol>
@stop

@section('content')
    {!! Form::open(['route' => ['admin.order.transaction.update', $transaction->id], 'method' => 'put']) !!}
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-1">
                            <div class="form-group {{ $errors->has('currency_code') ? 'has-error' : '' }}">
                                <label for="currency_code">{{ trans('order::transactions.currency_code') }}</label>
                                <select class="selectize" name="currency_code" id="currency_code">
                                    @foreach (Currency::getCurrencies() as $currency_code => $currency)
                                        <option value="{{ $currency_code }}" {{ $currency_code == old('currency_code', $transaction->currency_code)  ? 'selected' : '' }}>
                                            {{ Currency::getCurrencyName($currency_code, true) }}
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('currency_code', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            {!! Form::normalInput('amount', trans('order::transactions.amount'), $errors, $transaction) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::normalInput('pay_at', trans('order::transactions.pay_at'), $errors, $transaction, ['class' => 'form-control datetimepicker']) !!}
                        </div>
                        <div class="col-sm-2">
                            {!! Form::normalSelect('receipt_type', trans('order::transactions.receipt_type'), $errors, [
                                'cash' => trans('order::transactions.receipt_types.cash'),
                                'tax' => trans('order::transactions.receipt_types.tax'),
                            ], $transaction, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::normalInput('receipt_at', trans('order::transactions.receipt_at'), $errors, $transaction, ['class' => 'form-control datetimepicker']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::normalInput('payer_name', trans('order::transactions.payer_name'), $errors, $transaction) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::normalInput('bank_name', trans('order::transactions.bank_name'), $errors, $transaction, ['disabled']) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::normalInput('bank_account', trans('order::transactions.bank_account'), $errors, $transaction, ['disabled']) !!}
                        </div>
                    </div>

                    {!! Form::normalTextarea('message', trans('order::transactions.message'), $errors, $transaction, ['class' => 'form-control']) !!}

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.order.transaction.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                </div>
            </div>

        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index') }}</dd>
    </dl>
@stop

@include('order::admin.transactions.partials.scripts')

@push('js-stack')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.order.transaction.index') ?>" }
                ]
            });
        });
    </script>
    <script>
        $( document ).ready(function() {
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
            $('.selectize').selectize();
        });
    </script>
@endpush
