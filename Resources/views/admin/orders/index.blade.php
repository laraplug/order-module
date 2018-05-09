@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('order::orders.title.orders') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('order::orders.title.orders') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.order.order.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('order::orders.button.create order') }}
                    </a>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">{{ trans('order::orders.table.shop_name') }}</th>
                                <th width="20%">{{ trans('order::orders.table.name') }}</th>
                                <th width="10%">{{ trans('order::orders.table.payment_name') }}</th>
                                <th width="10%">{{ trans('order::orders.table.total') }}</th>
                                <th width="15%">{{ trans('order::orders.table.status') }}</th>
                                <th width="10%">{{ trans('core::core.table.created at') }}</th>
                                <th width="10%" data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    {{ $order->id }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.shop.shop.edit', [$order->shop_id]) }}">
                                        {{ $order->shop->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.order.order.show', [$order->id]) }}">
                                        {{ $order->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $order->payment_name }}
                                </td>
                                <td>
                                    {{ Shop::money($order->total) }}
                                </td>
                                <td>
                                    {{ $order->status->name }}
                                </td>
                                <td>
                                    {{ $order->created_at }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.order.order.show', [$order->id]) }}" class="btn btn-primary btn-flat"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('admin.order.order.edit', [$order->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('order::orders.table.shop_name') }}</th>
                                <th>{{ trans('order::orders.table.name') }}</th>
                                <th>{{ trans('order::orders.table.payment_name') }}</th>
                                <th>{{ trans('order::orders.table.total') }}</th>
                                <th>{{ trans('order::orders.table.status') }}</th>
                                <th>{{ trans('core::core.table.created at') }}</th>
                                <th>{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </tfoot>
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
        <dd>{{ trans('order::orders.title.create order') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.order.order.create') ?>" }
                ]
            });
        });
    </script>
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
