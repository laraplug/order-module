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
    <div class="row" ng-app="orders" ng-controller="OrderController as vm">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-header">
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table datatable dt-options="vm.dtOptions" dt-columns="vm.dtColumns" class="data-table table table-bordered table-hover" width="100%">
              </table>
              <!-- /.box-body -->
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>

      <script type="text/ng-template" id="actionColumn.tmpl">
          <a ng-href="{% vm.route('admin.order.order.show', [row.id]) %}" class="btn btn-primary btn-flat"><i class="fa fa-eye"></i></a>
          <a ng-href="{% vm.route('admin.order.order.edit', [row.id]) %}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
      </script>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular-sanitize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.2/angular-datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js"></script>

    <script type="text/javascript">

    angular.module('orders', [
        'ngSanitize',
        'datatables',
        'ui.bootstrap'
    ])
    .config(function($interpolateProvider, $httpProvider) {
      $interpolateProvider.startSymbol('{%');
      $interpolateProvider.endSymbol('%}');
      $httpProvider.defaults.headers.common['Authorization'] = AuthorizationHeaderValue;
    })
    .controller('OrderController', function($scope, $http, $compile, $templateCache, DTOptionsBuilder, DTColumnBuilder, $filter) {
        var vm = this;

        vm.dtOptions = DTOptionsBuilder.newOptions()
            .withOption('ajax', route('api.order.orders.datatable').toString())
            .withLanguageSource('{{ Module::asset("core:js/vendor/datatables/".locale().".json") }}')
            .withDataProp('data')
            .withOption('processing', true)
            .withOption('serverSide', true)
            .withPaginationType('full_numbers')
            .withOption('rowCallback', function(row, data) {
                // Angular scope데이터 적용
                var newScope = $scope.$new();
                newScope.row = data;
                $compile(angular.element(row).contents())(newScope);
            });

        vm.dtColumns = [
            DTColumnBuilder.newColumn('id', 'ID'),
            DTColumnBuilder.newColumn('shop_name', '{{ trans('order::orders.table.shop_name') }}'),
            DTColumnBuilder.newColumn('name', '{{ trans('order::orders.table.name') }}'),
            DTColumnBuilder.newColumn('payment_name', '{{ trans('order::orders.table.payment_name') }}'),
            DTColumnBuilder.newColumn('total', '{{ trans('order::orders.table.total') }}').renderWith(function(data, type, full) {
                console.log(full);
                return $filter('currency')(data, '￦', 0);
            }),
            DTColumnBuilder.newColumn('status.name', '{{ trans('order::orders.table.status') }}'),
            DTColumnBuilder.newColumn('created_at', '{{ trans('core::core.table.created at') }}'),
            DTColumnBuilder.newColumn('id', '{{ trans('core::core.table.actions') }}').renderWith(function() {
                //템플릿 로드
                return $templateCache.get('actionColumn.tmpl');
            }).withOption('width', '10%')
        ];

        // 라우팅함수 추가
        vm.route = window.route;
    });

    </script>
@endpush
