
<nav class="sidebar-nav">

    <ul id="sidebarnav">

        <li>
            <a class="waves-effect waves-dark" href="{!! url('dashboard') !!}" aria-expanded="false">

                <i class="mdi mdi-home"></i>

                <span class="hide-menu">{{trans('lang.dashboard')}}</span>

            </a>
        </li>

        <li>
            <a class="waves-effect waves-dark" href="{!! url('users') !!}" aria-expanded="false">

                <i class="mdi mdi-account-multiple"></i>

                <span class="hide-menu">{{trans('lang.user_customer')}}</span>

            </a>
        </li>
        <li>
            <a class="waves-effect waves-dark" href="{!! url('vendors') !!}" aria-expanded="false">

                <i class="mdi mdi-account-multiple"></i>

                <span class="hide-menu">{{trans('lang.owner_vendor')}}</span>

            </a>
        </li>
        <li><a class="waves-effect waves-dark" href="{!! url('restaurants') !!}" aria-expanded="false">

                <i class="mdi mdi-shopping"></i>

                <span class="hide-menu">{{trans('lang.restaurant_plural')}}</span>

            </a>
        </li>

        <li><a class="waves-effect waves-dark" href="{!! url('drivers') !!}" aria-expanded="false">

                <i class="mdi mdi-car"></i>

                <span class="hide-menu">{{trans('lang.driver_plural')}}</span>

            </a>
        </li>


        <li><a class="waves-effect waves-dark" href="{!! url('categories') !!}" aria-expanded="false">

                <i class="mdi mdi-clipboard-text"></i>

                <span class="hide-menu">{{trans('lang.category_plural')}}</span>

            </a>
        </li>


        <li>
            
        <a class="waves-effect waves-dark" href="{!! url('foods') !!}" aria-expanded="false">
                <i class="mdi mdi-food"></i>
                <span class="hide-menu">{{trans('lang.food_plural')}}</span>
            </a>

        </li>

        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                <i class="mdi mdi-plus-box"></i>
                <span class="hide-menu">{{trans('lang.attribute_plural')}}</span>
            </a>

            <ul aria-expanded="false" class="collapse">

                <li><a href="{!! route('attributes') !!}">{{trans('lang.item_attribute_id')}}</a></li>

                <li><a href="{!! route('reviewattributes') !!}">{{trans('lang.review_attribute_plural')}}</a></li>

            </ul>

        </li>

       <li><a class="waves-effect waves-dark" href="{!! url('orders') !!}" aria-expanded="false">
                <i class="mdi mdi-library-books"></i>
                <span class="hide-menu">{{trans('lang.order_plural')}}</span>
            </a>
        </li>

        


        <li><a class="waves-effect waves-dark" href="{!! url('coupons') !!}" aria-expanded="false">

                <i class="mdi mdi-sale"></i>

                <span class="hide-menu">{{trans('lang.coupon_plural')}}</span>

            </a>
        </li>


        <li><a class="waves-effect waves-dark" href="{!! url('notification') !!}" aria-expanded="false">

                <i class="fa fa-table "></i>

                <span class="hide-menu">{{trans('lang.notification')}}</span>

            </a>
        </li>

        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">

                <i class="mdi mdi-bank"></i>

                <span class="hide-menu">{{trans('lang.payment_plural')}}</span>

            </a>

            <ul aria-expanded="false" class="collapse">

                <li><a href="{!! url('payments') !!}">{{trans('lang.payment_plural')}}</a></li>

                <li><a href="{!! url('restaurantsPayouts') !!}">{{trans('lang.restaurants_payout_plural')}}</a></li>

                <li><a href="{!! url('driverpayments') !!}">{{trans('lang.driver_plural')}}
                        {{trans('lang.payment_plural')}}</a></li>

                <li><a href="{!! url('driversPayouts') !!}">{{trans('lang.drivers_payout')}}</a></li>

                <li><a href="{!! url('walletstransaction') !!}">{{trans('lang.wallet_transaction')}}</a></li>

                <li><a href="{!! url('payoutRequests/restaurants') !!}">{{trans('lang.payout_request')}}</a></li>

            </ul>

        </li>

        <li>
            <a class="waves-effect waves-dark" href="{!! url('banners') !!}" aria-expanded="false">

                <i class="mdi mdi-monitor-multiple "></i>

                <span class="hide-menu">{{trans('lang.menu_items')}}</span>

            </a>
        </li>
        
        <li><a class="waves-effect waves-dark" href="{!! url('cms') !!}" aria-expanded="false">
                <i class="mdi mdi-book-open-page-variant"></i>
                <span class="hide-menu">{{trans('lang.cms_plural')}}</span>
            </a>
        </li>

        <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">

                <i class="mdi mdi-settings"></i>

                <span class="hide-menu">{{trans('lang.app_setting')}}</span>

            </a>

            <ul aria-expanded="false" class="collapse">

                <li><a href="{!! url('settings/app/globals') !!}">{{trans('lang.app_setting_globals')}}</a></li>

                <li><a href="{!! url('settings/currencies') !!}">{{trans('lang.currency_plural')}}</a></li>

                <li><a href="{!! url('settings/payment/stripe') !!}">{{trans('lang.payment_methods')}}</a></li>

                <li>
                    <a href="{!! url('settings/app/adminCommission') !!}">{{trans('lang.restaurant_admin_commission')}}</a>
                </li>

                <li><a href="{!! url('settings/app/radiusConfiguration') !!}">{{trans('lang.radios_configuration')}}</a>
                </li>

                <li><a href="{!! url('settings/app/bookTable') !!}">{{trans('lang.dine_in_future_setting')}}</a></li>

                <li><a href="{!! url('settings/app/vatSetting') !!}">{{trans('lang.vat_setting')}}</a></li>

                <li><a href="{!! url('settings/app/deliveryCharge') !!}">{{trans('lang.deliveryCharge')}}</a></li>
                <li><a href="{!! url('settings/app/languages') !!}">{{trans('lang.languages')}}</a></li>

                <li><a href="{!! url('settings/app/specialOffer') !!}">{{trans('lang.special_offer')}}</a></li>
                <li><a href="{!! url('termsAndConditions') !!}">{{trans('lang.terms_and_conditions')}}</a></li>
                <li><a href="{!! url('privacyPolicy') !!}">{{trans('lang.privacy_policy')}}</a></li>

                <li><a href="{!! url('homepageTemplate') !!}">{{trans('lang.homepageTemplate')}}</a></li>

                <li><a href="{!! url('footerTemplate') !!}">{{trans('lang.footer_template')}}</a></li>

            </ul>

        </li>

    </ul>

    <p class="web_version"></p>
    
</nav>
