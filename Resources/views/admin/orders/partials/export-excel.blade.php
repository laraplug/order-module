
<div id="ex1" class="modal">
    <div class="modal-header">
    <h3>출력할 기간을 선택해 주세요</h3>
    </div>
    <div class="modal-content">
        <form action="" name="exportExcel">
            <div class="input-group input-daterange" style="width: 100%">
                <input type="text" name="startDay" class="form-control month-picker start-date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{date("Y-m-d",strtotime("-1 month"))}}">
                <div class="input-group-addon">to</div>
                <input type="text" name="endDay" class="form-control month-picker end-date" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{date("Y-m-d")}}">
            </div>
         </form>
    </div>
    <div class="modal-footer">
{{--        <a href="#" rel="modal:close">Close</a>--}}
        <button class="btn btn-primary" style="float: right" onclick="onSubmit()">
            출력
        </button>
    </div>
</div>
{!! Theme::style('vendor/admin-lte/plugins/datepicker/datepicker3.css') !!}
@push('js-stack')
    {!! Theme::script('vendor/admin-lte/plugins/datepicker/bootstrap-datepicker.js') !!}
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/locales/bootstrap-datepicker.{{locale()}}.min.js"></script>
    <script>
        $(function(){
            $('.input-daterange input').each(function() {
                $(this).datepicker('clearDates');
            });

        })
        function onSubmit(){
            var startDay = $('.start-date').val();
            var endDay = $('.end-date').val();
            var excelForm = document.exportExcel;
            excelForm.action = 'orders/excel';
            excelForm.target = '_blank';
            excelForm.method = 'get';
            excelForm.submit();
        }
    </script>
@endpush