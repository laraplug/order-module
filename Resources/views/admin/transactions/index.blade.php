@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('order::transactions.title.transactions') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('order::transactions.title.transactions') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('order::transactions.pay_at') }}</th>
                                <th>{{ trans('order::transactions.receipt_type') }}</th>
                                <th>{{ trans('order::transactions.receipt_at') }}</th>
                                <th>{{ trans('order::transactions.payment_method') }}</th>
                                <th>{{ trans('order::transactions.transaction_id') }}</th>
                                <th>{{ trans('order::transactions.user_name') }}</th>
                                <th>{{ trans('order::transactions.payer_name') }}</th>
                                <th>{{ trans('order::transactions.amount') }}</th>
                                <th>{{ trans('order::transactions.message') }}</th>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($transactions)): ?>
                            <?php foreach ($transactions as $i => $transaction): ?>
                            <tr>
                                <td>{{ $transactions->count() - $i }}</td>
                                <td>{{ $transaction->pay_at }}</td>
                                <td>{{ $transaction->receipt_type_name }}</td>
                                <td>{{ $transaction->receipt_at }}</td>
                                <td>{{ $transaction->payment_method->getName() }}</td>
                                <td>{{ $transaction->gateway_transaction_id }}</td>
                                <td>{{ $transaction->user->present()->fullname }}</td>
                                <td>{{ $transaction->payer_name }}</td>
                                <td>{{ Shop::money($transaction->amount) }}</td>
                                <td>{{ $transaction->message }}</td>
                                <td>
                                    <a href="{{ route('admin.order.transaction.edit', [$transaction->id]) }}">
                                        {{ $transaction->created_at }}
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.order.transaction.edit', [$transaction->id]) }}" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-pencil"></i></a>
                                        <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.order.transaction.destroy', [$transaction->id]) }}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('order::transactions.title.create transaction') }}</dd>
    </dl>
@stop

@push('js-stack')
    <?php $locale = locale(); ?>
    <script type="text/javascript">
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
@endpush
