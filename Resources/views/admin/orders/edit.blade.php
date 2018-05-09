@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('order::orders.title.edit order') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.order.order.index') }}">{{ trans('order::orders.title.orders') }}</a></li>
        <li class="active">{{ trans('order::orders.title.edit order') }}</li>
    </ol>
@stop

@section('content')
    {!! Form::open(['route' => ['admin.order.order.update', $order->id], 'method' => 'put']) !!}
    <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">주문자 정보</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::normalInput('payment_name', trans('order::orders.form.name'), $errors, $order) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::normalInput('payment_phone', trans('order::orders.form.phone'), $errors, $order) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::normalInput('payment_email', trans('order::orders.form.email'), $errors, $order) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::normalInput('payment_postcode', trans('order::orders.form.postcode'), $errors, $order) !!}
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" id="payment_postcode_search" class="form-control btn btn-primary c-btn-square">
                            <span class="glyphicon glyphicon-map-marker"></span>
                            {{ trans('order::orders.button.search postcode') }}
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::normalInput('payment_address', trans('order::orders.form.address'), $errors, $order) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::normalInput('payment_address_detail', trans('order::orders.form.address_detail'), $errors, $order) !!}
                    </div>
                </div>
            </div>
          </div><!-- /.box -->
        </div><!-- /.col-md-6 -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">배송지 정보</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::normalInput('shipping_name', trans('order::orders.form.name'), $errors, $order) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::normalInput('shipping_phone', trans('order::orders.form.phone'), $errors, $order) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::normalInput('shipping_email', trans('order::orders.form.email'), $errors, $order) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::normalInput('shipping_postcode', trans('order::orders.form.postcode'), $errors, $order) !!}
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" id="shipping_postcode_search" class="form-control btn btn-primary c-btn-square">
                            <span class="glyphicon glyphicon-map-marker"></span>
                            {{ trans('order::orders.button.search postcode') }}
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::normalInput('shipping_address', trans('order::orders.form.address'), $errors, $order) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::normalInput('shipping_address_detail', trans('order::orders.form.address_detail'), $errors, $order) !!}
                    </div>
                </div>
            </div>
          </div><!-- /.box -->
      </div><!-- /.col-md-6 -->
    </div>
    <div class="row">
        <div class="col-md-12">

            @include('order::admin.orders.partials.products')

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.order.order.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    @include('core::partials.delete-modal')
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

@push('js-stack')
    <!-- jQuery와 Postcodify를 로딩한다 -->
    <script src="//d1p7wdleee1q2z.cloudfront.net/post/search.min.js"></script>
    <!-- "검색" 단추를 누르면 팝업 레이어가 열리도록 설정한다 -->
    <script>
    $(function() {
        $("#payment_postcode_search").postcodifyPopUp({
            insertPostcode5 : "[name=payment_postcode]",
            insertAddress : "[name=payment_address]",
            insertDetails : "[name=payment_address_detail]",
            beforeSearch: function(keywords) {
                $.ajaxSetup({ headers: null });
            },
            afterSearch: function(keywords, results) {
                $.ajaxSetup({ headers: window.AuthorizationHeaderValue });
            }
        });
        $("#shipping_postcode_search").postcodifyPopUp({
            insertPostcode5 : "[name=shipping_postcode]",
            insertAddress : "[name=shipping_address]",
            insertDetails : "[name=shipping_address_detail]",
            beforeSearch: function(keywords) {
                $.ajaxSetup({ headers: null });
            },
            afterSearch: function(keywords, results) {
                $.ajaxSetup({ headers: window.AuthorizationHeaderValue });
            }
        });
    });
    </script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.order.order.index') ?>" }
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
        });
    </script>
@endpush
