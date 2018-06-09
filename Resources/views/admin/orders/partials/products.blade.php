<div class="box box-primary" id="items" ng-app="items" ng-controller="ItemController">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('order::orders.title.order items') }}</h3>
      <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </button>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td width="10%" class="text-center">{{ trans('product::products.image') }}</td>
                    <td width="15%" class="text-center">{{ trans('product::products.name') }}</td>
                    <td width="30%" class="text-center">{{ trans('product::options.title.options') }}</td>
                    <td width="10%" class="text-center">{{ trans('product::products.price') }}</td>
                    <td width="10%" class="text-center">{{ trans('product::bundleproducts.quantity') }}</td>
                    <td width="10%" class="text-center">{{ trans('product::bundleproducts.total') }}</td>
                    <td width="5%" class="text-center">{{ trans('core::core.table.actions') }}</td>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in orderItems" ng-init="$itemIndex = $index">
                    <input type="hidden"
                        name="items[{% $itemIndex %}][id]"
                        ng-value="item.id" />

                    <input type="hidden"
                        name="items[{% $itemIndex %}][product_id]"
                        ng-value="item.product.id" />

                    <td>
                        <img ng-src="{% item.product.small_thumb %}" />
                    </td>
                    <td>
                        {% item.product.name %}
                    </td>
                    <td>
                        <div class="row" ng-repeat="option in item.product.options">
                            <label class="col-sm-2">
                              <input type="checkbox"
                                  ng-true-value="1" ng-false-value="0"
                                  ng-model="option.enabled"
                                  ng-disabled="item.is_readonly"
                                  ng-change="!option.enabled ? item.option_values[option.slug] = '' : ''">
                            </label>
                            <div class="col-sm-10 form-group">
                                <label>{% option.name %}</label>
                                <div compile="option.form_field"></div>

                                <input type="hidden"
                                    name="items[{% $itemIndex %}][option_values][{% option.slug %}]"
                                    ng-value="item.option_values[option.slug]"
                                    ng-disabled="item.is_readonly || !option.enabled" />
                            </div>
                        </div>
                    </td>
                    <td>
                        {% item.price %}
                    </td>
                    <td>
                        <input type="text" class="form-control"
                            placeholder="{{ trans('product::bundleproducts.quantity') }}"
                            ng-model="item.quantity"
                            ng-value="item.quantity"
                            ng-disabled="item.is_readonly"
                            name="items[{% $itemIndex %}][quantity]" />
                    </td>
                    <td>
                        {% item.price * item.quantity %}
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-flat" ng-click="removeBundleItem($itemIndex)" ng-disabled="item.is_readonly">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <selectize placeholder="{{ trans('product::bundleproducts.messages.search to add product') }}" config="selectizeConfig" ng-model="productId"></selectize>
    </div>
    <!-- /.box-footer -->
</div>

@push('css-stack')
    {!! Theme::style('vendor/admin-lte/plugins/datepicker/datepicker3.css') !!}
@endpush

@push('js-stack')
    {!! Theme::script('vendor/admin-lte/plugins/datepicker/bootstrap-datepicker.js') !!}
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/locales/bootstrap-datepicker.{{locale()}}.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>
    <script src="{{ Module::asset('product:js/ng-components/selectize.directive.js') }}"></script>
    <script>
    angular.module('items', [
        'selectize'
    ])
    .config(function($interpolateProvider, $httpProvider) {
      $interpolateProvider.startSymbol('{%');
      $interpolateProvider.endSymbol('%}');
      $httpProvider.defaults.headers.common['Authorization'] = AuthorizationHeaderValue;
    })
    .controller('ItemController', function($scope, $http) {

        $scope.orderItems = [];

        $scope.getOrderItems = function() {
            var url = route('api.order.orders.items.index', { order: {{ $order->id }} }).toString();
            $http.get(url)
            .then(function(res) {
                var items = res.data.data;
                items = items.map(function(item) {
                    return normalizeBundleItem(item);
                });
                $scope.orderItems = items;
            });
        }
        $scope.getOrderItems();

        var normalizeBundleItem = function(item) {
            if(!item['product']) return;

            // 옵션은 존재하면 enabled처리
            item.product.options.map(function(option) {
                if(item.option_values[option.slug] !== undefined) {
                    option.value = item.option_values[option.slug];
                    option.enabled = 1;
                }
                return option;
            });

            item.product.options.map(function(option) {
                option.form_field = option.form_field.replace('name="options[', 'name="items[{% $itemIndex %}][options][');
                option.form_field = option.form_field.replace('name=', 'ng-model="item.option_values[option.slug]" ng-disabled="item.is_readonly || !option.enabled" name=');
                return option;
            });

            return item;
        };

        $scope.removeBundleItem = function(index) {
            $scope.orderItems.splice(index, 1);
        };

        $scope.calcProductPrice = function (bundleItem) {
            var total = Number(bundleItem.product.price);
            var selectedOptions = bundleItem.product.options.filter(function(option) {
                return option.value !== undefined;
            });
            for(var option of selectedOptions) {
                // 배열타입 옵션이면 가격적용
                // Apply price if collection type option
                if(option.is_collection && option.value) {
                    var filtered = option.values.filter(function( item ) {
                        return item.code == option.value;
                    });
                    if(filtered.length > 0) {
                      var selected = filtered[0];
                      if(selected.price_type == 'FIXED') {
                        total += selected.price_value;
                      }
                      else if(selected.price_type == 'PERCENTAGE') {
                        total += bundleItem.product.sale_price * (selected.price_value / 100);
                        total = Math.round(total);
                      }
                    }
                }
            }
            // 가격이 음수일수는 없음
            // There's no minus for price
            if(total < 0) total = 0;
            return total;
        };



        // selectize
        $scope.selectizeConfig = {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            create: false,
            onItemAdd: function(value, $item) {
                var product = this.options[value];

                var item = normalizeBundleItem({
                    'product': product,
                    'quantity': 1,
                    'option_values': {}
                });
                $scope.orderItems.push(item);

                this.clear();
            },
            render: {
                option: function(item, escape) {
                    return '<div>' +
                        '<span class="title">' +
                            '<span class="name">' + escape(item.name) + '</span>' +
                        '</span>' +
                    '</div>';
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: route('api.product.products.indexServerSide') + '?search=' + encodeURIComponent(query),
                    type: 'GET',
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback(res.data);
                    }
                });
            }
        };

    })
    .directive('compile', ['$compile', function ($compile) {
      return function(scope, element, attrs) {
        scope.$watch(
          function(scope) {
            return scope.$eval(attrs.compile);
          },
          function(value) {
            element.html(value);
            $compile(element.contents())(scope);
          }
       )};
    }])
    .directive("datepicker", function() {
        return {
          restrict: "A",
          require: "ngModel",
          link: function(scope, elem, attrs, ngModelCtrl) {
              elem.on("changeDate", updateModel);
              elem.datepicker({
                  language: '{{ locale() }}'
              });

              function updateModel(event) {
                  ngModelCtrl.$setViewValue(event.date);
              }
          }
        }
    });

    //angular.bootstrap(document.getElementById("items"), ['items']);
    </script>
@endpush
