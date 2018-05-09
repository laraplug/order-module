<?php

namespace Modules\Order\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterOrderSidebar implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(config('asgard.order.config.sidebar-group'), function (Group $group) {
            $group->item(trans('order::orders.title.orders'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(0);
                $item->route('admin.order.order.index');
                $item->authorize(
                    $this->auth->hasAccess('order.orders.index')
                );
            });
            $group->item(trans('order::orderstatuses.title.orderstatuses'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(0);
                $item->route('admin.order.orderstatus.index');
                $item->authorize(
                    $this->auth->hasAccess('order.orderstatuses.index')
                );
            });
            $group->item(trans('order::transactions.title.transactions'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(0);
                $item->route('admin.order.transaction.index');
                $item->authorize(
                    $this->auth->hasAccess('order.transactions.index')
                );
            });
            $group->item(trans('order::transportations.title.transportations'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(0);
                $item->route('admin.order.transportation.index');
                $item->authorize(
                    $this->auth->hasAccess('order.transportations.index')
                );
            });
// append




        });

        return $menu;
    }
}
