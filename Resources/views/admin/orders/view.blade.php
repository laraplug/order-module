@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('order::orders.title.view order') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.order.order.index') }}">{{ trans('order::orders.title.orders') }}</a></li>
        <li class="active">{{ trans('order::orders.title.view order') }}</li>
    </ol>
@stop

@section('content')
    <form name="orderView">
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.order.order.destroy', [$order->id]) }}">
                <i class="fa fa-trash"></i> {{ trans('core::core.button.delete') }}
            </button>
            <a href="{{ route('admin.order.order.edit', $order->id) }}" class="btn btn-primary btn-flat pull-right" style="margin: 0px 15px 15px 0px;">
                <i class="fa fa-pencil"></i> {{ trans('core::core.button.update') }}
            </a>
        </div>
    </div>

    <div class="box box-primary">
      <div class="box-header with-border">
          <h3 class="box-title">결제 정보</h3>
      </div>
      <div class="box-body">
          <div class="row">
              <div class="col-sm-12">
                  <label>{{ trans('order::orders.form.payment_method') }}</label>
                  <p>{{ $order->payment_method->getName() }}</p>
              </div>
          </div>
          <div class="row">
              <div class="col-sm-12">
                  <table class="table">
                      <tbody>
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
                              <th>{{ trans('core::core.table.actions') }}</th>
                          </tr>
                          @foreach ($order->transactions as $transaction)
                          <tr>
                              <td>{{ $loop->index + 1 }}</td>
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
                                  <a class="btn btn-default btn-flat" href="{{ route('admin.order.transaction.edit', $transaction->id) }}">
                                      <i class="fa fa-pencil"></i>
                                  </a>
                                  <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.order.transaction.destroy', $transaction->id) }}">
                                      <i class="fa fa-trash"></i>
                                  </button>
                              </td>
                          </tr>
                          @endforeach
                          <tr>
                              <td colspan="7">
                                  <button type="button" onclick="location.href = '{{ route('admin.order.transaction.create', $order->id) }}';" class="btn btn-primary btn-sm">
                                      {{ trans('order::transactions.button.create transaction') }}
                                  </button>
                              </td>
                          </tr>
                    </tbody>
                </table>
              </div>
          </div>
      </div>
    </div><!-- /.box -->

    <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">주문자 정보</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label>{{ trans('order::orders.form.name') }}</label>
                        <p>{{ $order->payment_name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.phone') }}</label>
                        <p>{{ $order->payment_phone }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.email') }}</label>
                        <p>{{ $order->payment_email }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.postcode') }}</label>
                        <p>{{ $order->payment_postcode }}</p>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.address') }}</label>
                        <p>{{ $order->payment_address }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.address_detail') }}</label>
                        <p>{{ $order->payment_address_detail }}</p>
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
                        <label>{{ trans('order::orders.form.name') }}</label>
                        <p>{{ $order->shipping_name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.phone') }}</label>
                        <p>{{ $order->shipping_phone }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.email') }}</label>
                        <p>{{ $order->shipping_email }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.postcode') }}</label>
                        <p>{{ $order->shipping_postcode }}</p>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.address') }}</label>
                        <p>{{ $order->shipping_address }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>{{ trans('order::orders.form.address_detail') }}</label>
                        <p>{{ $order->shipping_address_detail }}</p>
                    </div>
                </div>
            </div>
          </div><!-- /.box -->
      </div><!-- /.col-md-6 -->
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">상품 정보</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                          <th width="5%">#</th>
                          <th width="10%">{{ trans('order::orders.items.product_image') }}</th>
                          <th width="30%">{{ trans('order::orders.items.product_name') }}</th>
                          <th width="10%">{{ trans('order::orders.items.price') }}</th>
                          <th width="10%">{{ trans('order::orders.items.quantity') }}</th>
                          <th width="10%">{{ trans('order::orders.items.total') }}</th>
                          <th width="10%">{{ trans('order::orders.items.action') }}</th>
                        </tr>
                        @foreach ($order->items as $i => $item)
                            @if ($item->product)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>
                                      <img src="{{ $item->product->small_thumb }}" style="width:100%; max-width:100px;" />
                                  </td>
                                  <td>
                                      <h4>{{ $item->product->name }}</h4>
                                      @if($item->option_values)
                                          @foreach ($item->product->options as $option)
                                              @if($item->option_values->has($option->slug))
                                              <p>{{ $option->name }} : {{ Shop::getOptionValueName($option, $item->option_values[$option->slug]) }}</p>
                                              @endif
                                          @endforeach
                                      @endif
                                  </td>
                                  <td>{{ Shop::money($item->price) }}</td>
                                  <td>{{ $item->quantity }}</td>
                                  <td>{{ Shop::money($item->total) }}</td>
                                  {{-- <td>{!! $item->getProductActionFields() !!}</td> --}}
                                  <td><input type="checkbox" class="btn-status" value="{{ $item->status->id }}" data-item-id="{{ $item->id }}" data-status-id="9"> 배송완료</td>
                              </tr>
                            @endif
                        @endforeach
                        <tr class="text-bold">
                          <td colspan="5" class="text-right">{{ trans('order::orders.form.total_price') }}</td>
                          <td>{{ Shop::money($order->total_price) }}</td>
                        </tr>
                        <tr class="text-bold">
                          <td colspan="5" class="text-right">{{ trans('order::orders.form.total_shipping') }}</td>
                          <td>{{ Shop::money($order->total_shipping) }}</td>
                        </tr>
                        <tr class="text-bold">
                          <td colspan="5" class="text-right">{{ trans('order::orders.form.total') }}</td>
                          <td>{{ Shop::money($order->total) }}</td>
                        </tr>
                      </tbody>
                  </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">상태 정보</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            {!! Form::normalSelect('status_id', trans('order::orders.form.status'), $errors, $orderStatuses, $order) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" id="btnSaveStatus" class="btn btn-primary">저장</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.col-md-6 -->
    </div>
    </form>

    @include('core::partials.delete-modal')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop

@section('scripts')
<script>
$(function() {
    // 버튼
    $('input[type="checkbox"].btn-status').each(function() {
        if($(this).data('status-id') == $(this).val()) $(this).attr('checked', true);
    })
    $('input[type="checkbox"].btn-status').change(function() {
        // 체크가 된 상태라면 배송완료 확인
        if($(this).is(":checked")) {
            console.log('checked');
            changeItemStatus($(this).data('item-id'), 9);
        }
        // 체크가 해제 된 상태라면 배송완료 취소 -> 대기 상태로 변경
        else {
            console.log('ucchecked');
            changeItemStatus($(this).data('item-id'), 3);
        }
    })

    $('#btnSaveStatus').click(function() {
        var statusId = $('select[name=status_id]').val();
        console.log('statusId', statusId);
        if(statusId) {
            $.ajax({
                type: 'PUT',
                url: '{{ route('api.order.orders.updateStatus', $order->id) }}',
                data: {'status_id': statusId},
                dataType: 'json',
                success: function(data) {
                    if(data.errors) {
                        alert(data.errors);
                    }
                    else {
                        alert(data.message);
                    }
                    console.log(data);
                },
                error:function (xhr, ajaxOptions, thrownError){
                }
            });
        }
    });
});

// 상품 상태변경 함수
function changeItemStatus(item_id, status_id) {
    if(item_id && status_id) {
        $.ajax({
            type: 'PUT',
            url: '{{ route('api.order.orders.updateItemStatus', $order->id) }}',
            data: {'status_id': status_id, 'item_id': item_id},
            dataType: 'json',
            success: function(data) {
                if(data.errors) {
                    alert(data.errors);
                }
                else {
                    alert(data.message);
                }
                console.log(data);
            },
            error:function (xhr, ajaxOptions, thrownError){
            }
        });
    }
}

</script>
@stop
