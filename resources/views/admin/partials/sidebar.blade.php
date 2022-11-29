<aside class="main-sidebar sidebar-light-danger elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link trinity__primary-color text-light">
        @include('partials.brand')
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->renderImagePath() }}" class="img-circle elevation-2" style="width: 35px; height: 35px;">
            </div>
            <div class="info">
                <a href="{{ route('admin.profiles.show') }}" class="d-block">
                    {{ auth()->user()->renderName() }}
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                {{--
                | ===========================================================
                | DASHBOARD
                | ===========================================================
                --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.dashboard',
                    ]) }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>



                {{--
                | ===========================================================
                | USERS
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission([
                    'admin.users.crud', 'admin.doctors.crud', 'admin.medreps.crud' 
                ]))
                    <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                            'admin.users.index','admin.users.create','admin.users.show',
                            'admin.doctors.index','admin.doctors.create','admin.doctors.show',
                            'admin.medreps.index','admin.medreps.create','admin.medreps.show',
                            'admin.medreps.location.tracker', 'admin.users.manage-credits',
                            'admin.doctors.manage-credits',
                        ]) }}">
                        <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.users.index','admin.users.create','admin.users.show',
                            'admin.medreps.index','admin.medreps.create','admin.medreps.show',
                            'admin.doctors.index','admin.doctors.create','admin.doctors.show',
                            'admin.medreps.location.tracker', 'admin.users.manage-credits',
                            'admin.doctors.manage-credits',
                        ]) }}">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                Users
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($self->hasAnyPermission(['admin.users.crud']))
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.users.index','admin.users.create','admin.users.show', 'admin.users.manage-credits'
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Patients / Secretaries
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if ($self->hasAnyPermission([
                                'admin.doctors.crud', 
                            ]))      
                                <li class="nav-item">
                                    <a href="{{ route('admin.doctors.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.doctors.index','admin.doctors.create','admin.doctors.show', 'admin.doctors.manage-credits',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>Doctors</p>
                                    </a>
                                </li>
                            @endif
                            
                            @if ($self->hasAnyPermission([
                                'admin.medreps.crud', 
                            ]))
                                <li class="nav-item">
                                    <a href="{{ route('admin.medreps.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.medreps.index','admin.medreps.create','admin.medreps.show',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>Medical Representatives</p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ route('admin.medreps.location.tracker') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.medreps.location.tracker'
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>Med Rep Location Logs</p>
                                    </a>
                                </li>

                            @endif

                        </ul>
                    </li>

                @endif



                {{--
                | ===========================================================
                | ORDER MANAGEMENT
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission([
                    'admin.status-types.crud', 'admin.invoices.crud'
                ]))

                    <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                            'admin.invoices.index', 'admin.invoices.show',
                            'admin.status-types.index', 'admin.status-types.create', 'admin.status-types.show',
                            'admin.invoices.failed-transaction', 'admin.invoices.failed-transaction-form'
                        ]) }}">
                        <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.invoices.index', 'admin.invoices.show',
                            'admin.status-types.index', 'admin.status-types.create', 'admin.status-types.show',
                            'admin.invoices.failed-transaction', 'admin.invoices.failed-transaction-form'
                        ]) }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Order Management
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            
                            @if ($self->hasAnyPermission([
                                'admin.status-types.crud'
                            ]))                            
                                <li class="nav-item">
                                    <a href="{{ route('admin.status-types.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.status-types.index', 'admin.status-types.create', 'admin.status-types.show',

                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Order status types
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if ($self->hasAnyPermission([
                                'admin.invoices.crud'
                            ]))                          
                                <li class="nav-item">
                                    <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.invoices.index','admin.invoices.show','admin.invoices.failed-transaction',
                                        'admin.invoices.failed-transaction-form'
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Invoices
                                        </p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>

                @endif



                {{--
                | ===========================================================
                | STOCKS
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission([
                    'admin.products.crud', 'admin.inventories.crud' 
                ]))
                    <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                            'admin.products.index','admin.products.create','admin.products.show',
                            'admin.inventories.index', 'admin.inventories.show', 'admin.products.all.variants',
                            'admin.products.create.variant', 'admin.products.upload',
                            'admin.product-parents.index','admin.product-parents.create','admin.product-parents.show',
                        ]) }}">
                        <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.products.index','admin.products.create','admin.products.show',
                            'admin.inventories.index', 'admin.inventories.show', 'admin.products.all.variants',
                            'admin.products.create.variant', 'admin.products.upload',
                            'admin.product-parents.index','admin.product-parents.create','admin.product-parents.show',
                            'admin.pharmacies.index','admin.pharmacies.create','admin.pharmacies.show'
                        ]) }}">
                            <i class="nav-icon fa fa-cubes"></i>
                            <p>
                                Product Management
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                                    
                        @if ($self->hasAnyPermission([
                            'admin.products.crud', 
                        ]))     
                           {{--  <li class="nav-item">
                                <a href="{{ route('admin.product-parents.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                    'admin.product-parents.index','admin.product-parents.create','admin.product-parents.show', 
                                ]) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Product Parents
                                    </p>
                                </a>
                            </li> --}}

                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                    'admin.products.index','admin.products.create','admin.products.show', 
                                    'admin.products.all.variants', 'admin.products.create.variant', 'admin.products.upload'
                                ]) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Products
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if ($self->hasAnyPermission([
                            'admin.inventories.crud', 
                        ]))
                            <li class="nav-item">
                                <a href="{{ route('admin.inventories.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                    'admin.inventories.index', 'admin.inventories.show',
                                ]) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Inventories
                                    </p>
                                </a>
                            </li>

                        @endif

                        </ul>
                    </li>
                @endif



                {{--
                | ===========================================================
                | ALL CALLS
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission([
                    'admin.calls.crud', 'admin.target-calls.crud' 
                ]))

                    <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                            'admin.calls.index','admin.calls.create','admin.calls.show',
                            'admin.target-calls.index','admin.target-calls.create','admin.target-calls.show',
                        ]) }}">
                        <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.calls.index','admin.calls.create','admin.calls.show',
                            'admin.target-calls.index','admin.target-calls.create','admin.target-calls.show',
                        ]) }}">
                            <i class="nav-icon fas fa-pen"></i>
                            <p>
                                All Calls
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($self->hasAnyPermission([
                                'admin.calls.crud' 
                            ]))                            
                                <li class="nav-item">
                                    <a href="{{ route('admin.calls.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.calls.index','admin.calls.create','admin.calls.show',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>Calls</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                @endif



                {{--
                | ===========================================================
                | REWARDS MANAGEMENT
                | ===========================================================
                --}}
                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'admin.redeems.index', 'admin.points.index', 'admin.rewards.index',
                        'admin.sponsorships.index', 'admin.sponsorships.create', 'admin.sponsorships.show',
                        'admin.rewards.create','admin.rewards.show',
                        'admin.points.create', 'admin.points.show',
                    ]) }}">
                    <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.redeems.index', 'admin.points.index', 'admin.rewards.index',
                        'admin.sponsorships.index', 'admin.sponsorships.create', 'admin.sponsorships.show',
                        'admin.rewards.create','admin.rewards.show',
                    ]) }}">

                        <i class="nav-icon fas fa-gift"></i>
                        <p>
                            Rewards Management
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($self->hasAnyPermission(['admin.points.index']))
                            <li class="nav-item">
                                <a href="{{ route('admin.points.index') }}" class="nav-link {{ $checker->route->areOnRoutes(['admin.points.index', 'admin.points.create', 'admin.points.show']) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>Points</p>
                                </a>
                            </li>
                        @endif

                        @if ($self->hasAnyPermission(['admin.redeems.crud']))
                            <li class="nav-item">
                                <a href="{{ route('admin.redeems.index') }}" class="nav-link {{ $checker->route->areOnRoutes(['admin.redeems.index', 'admin.redeems.create', 'admin.redeems.show']) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>Redeems</p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('admin.rewards.index') }}" class="nav-link {{ $checker->route->areOnRoutes(['admin.rewards.index', 'admin.rewards.create','admin.rewards.show']) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Rewards for Doctor</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.sponsorships.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'admin.sponsorships.index', 'admin.sponsorships.create', 'admin.sponsorships.show'
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Sponsorships
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--
                | ===========================================================
                | CONSULTATIONS MANAGEMENT
                | ===========================================================
                --}}

                <li class="nav-item">
                    <a href="{{ route('admin.consultations.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.consultations.index', 'admin.consultations.show',
                    ]) }}">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <p>
                            Consultations
                        </p>
                    </a>
                </li>

                {{--
                | ===========================================================
                | E-WALLET MANAGEMENT
                | ===========================================================
                --}}
                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'admin.credit-packages.index', 'admin.credit-packages.create', 'admin.credit-packages.show',
                        'admin.credit-invoices.index'
                    ]) }}">
                    <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.credit-packages.index', 'admin.credit-packages.create', 'admin.credit-packages.show',
                        'admin.credit-invoices.index'
                    ]) }}">

                        <i class="nav-icon fas fa-wallet"></i>
                        <p>
                            E-Wallet Management
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.credit-packages.index') }}" class="nav-link {{ $checker->route->areOnRoutes(['admin.credit-packages.index', 'admin.credit-packages.create','admin.credit-packages.show']) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Credit Packages</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.credit-invoices.index') }}" class="nav-link {{ $checker->route->areOnRoutes(['admin.credit-invoices.index']) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Invoices</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--
                | ===========================================================
                | PAYOUTS MANAGEMENT
                | ===========================================================
                --}}
                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'admin.payouts.index', 'admin.payouts.disapproval-form'
                    ]) }}">
                    <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.payouts.index', 'admin.payouts.disapproval-form'
                    ]) }}">

                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Payouts Management
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.payouts.index') }}" class="nav-link {{ $checker->route->areOnRoutes(['admin.payouts.index' ,'admin.payouts.disapproval-form']) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Payout Requests</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--
                | ===========================================================
                | REFUNDS MANAGEMENT
                | ===========================================================
                --}}
                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'admin.refunds.index', 'admin.refunds.disapproval-form', 'admin.refunds.show'
                    ]) }}">
                    <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.refunds.index', 'admin.refunds.disapproval-form', 'admin.refunds.show'
                    ]) }}">

                        <i class="nav-icon fas fa-money-bill-alt"></i>
                        <p>
                            Refunds Management
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.refunds.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'admin.refunds.index', 'admin.refunds.disapproval-form', 'admin.refunds.show'
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Refund Requests</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--
                | ===========================================================
                | DOCTOR REVIEWS MANAGEMENT
                | ===========================================================
                --}}

                <li class="nav-item">
                    <a href="{{ route('admin.doctor-reviews.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.doctor-reviews.index',
                    ]) }}">
                        <i class="nav-icon far fa-thumbs-up"></i>
                        <p>Doctors Review</p>
                    </a>
                </li>   

                {{--
                | ===========================================================
                | LOCATIONS
                | ===========================================================
                --}}
                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'admin.provinces.index','admin.provinces.create','admin.provinces.show',
                        'admin.cities.index','admin.cities.create','admin.cities.show',
                        'admin.areas.index','admin.areas.create','admin.areas.show',
                        'admin.shipping-matrixes.index','admin.shipping-matrixes.create','admin.shipping-matrixes.show',
                    ]) }}">
                    <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.provinces.index','admin.provinces.create','admin.provinces.show',
                        'admin.cities.index','admin.cities.create','admin.cities.show',
                        'admin.areas.index','admin.areas.create','admin.areas.show',
                        'admin.shipping-matrixes.index','admin.shipping-matrixes.create','admin.shipping-matrixes.show',
                    ]) }}">
                        <i class="nav-icon fa fa-map-marked-alt"></i>
                        <p>
                            Locations
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.provinces.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'admin.provinces.index','admin.provinces.create','admin.provinces.show',
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Provinces</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cities.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'admin.cities.index','admin.cities.create','admin.cities.show',
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>City</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.areas.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'admin.areas.index','admin.areas.create','admin.areas.show',
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.shipping-matrixes.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'admin.shipping-matrixes.index','admin.shipping-matrixes.create','admin.shipping-matrixes.show',
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Shipping Matrix</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--
                | ===========================================================
                | Vouchers
                | ===========================================================
                --}}

                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                    'admin.vouchers.index', 'admin.vouchers.create', 'admin.vouchers.show',
                    'admin.request-claim-referrals.index', 'admin.request-claim-referrals.approve', 'admin.request-claim-referrals.reject',
                    'admin.used-vouchers.index'
                ]) }}">
                    <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.vouchers.index', 'admin.vouchers.create', 'admin.vouchers.show',
                        'admin.request-claim-referrals.index', 'admin.request-claim-referrals.approve', 'admin.request-claim-referrals.reject',
                        'admin.used-vouchers.index'
                    ]) }}">
                        <i class="nav-icon fas fa-ticket-alt"></i>
                        <p>
                            Voucher Management
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if ($self->hasAnyPermission(['admin.vouchers.crud']))
                            <li class="nav-item">
                                <a href="{{ route('admin.vouchers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                    'admin.vouchers.index', 'admin.vouchers.create', 'admin.vouchers.show'
                                ]) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Vouchers
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if ($self->hasAnyPermission(['admin.vouchers.crud']))
                            <li class="nav-item">
                                <a href="{{ route('admin.used-vouchers.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                    'admin.used-vouchers.index'
                                ]) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Used Vouchers
                                    </p>
                                </a>
                            </li>
                        @endif

                        {{--
                        | ===========================================================
                        | Request Claim Referrals
                        | ===========================================================
                        --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.request-claim-referrals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'admin.request-claim-referrals.index', 'admin.request-claim-referrals.approve', 'admin.request-claim-referrals.reject'
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Request Claim Referrals
                                </p>
                            </a>
                        </li>

                    </ul>

                </li>


                {{--
                | ===========================================================
                | BANK DETAILS
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission(['admin.bank-details.crud']))
                    <li class="nav-item">
                        <a href="{{ route('admin.bank-details.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.bank-details.index', 'admin.bank-details.create', 'admin.bank-details.show'
                        ]) }}">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>
                                Bank Details
                            </p>
                        </a>
                    </li>
                @endif



                {{--
                | ===========================================================
                | UPLOADED PRESCRIPTIONS
                | ===========================================================
                --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.prescriptions.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.prescriptions.index' 
                    ]) }}">
                        <i class="nav-icon fas fa-prescription"></i>
                        <p>
                            Uploaded Prescriptions
                        </p>
                    </a>
                </li> --}}



                {{--
                | ===========================================================
                | SPECIALIZATIONS
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission(['admin.specializations.crud']))
                    <li class="nav-item">
                        <a href="{{ route('admin.specializations.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.specializations.index', 'admin.specializations.create', 'admin.specializations.show'
                        ]) }}">
                            <i class="nav-icon fas fa-certificate"></i>
                            <p>
                                Specializations
                            </p>
                        </a>
                    </li>
                @endif



                {{--
                | ===========================================================
                | ARTICLES
                | ===========================================================
                --}}

                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'admin.articles.index', 'admin.articles.create', 'admin.articles.show',
                        'admin.article-categories.index','admin.article-categories.create','admin.article-categories.show',
                        ]) }}">

                        <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.articles.index', 'admin.articles.create', 'admin.articles.show',
                            'admin.article-categories.index','admin.article-categories.create','admin.article-categories.show',
                        ]) }}">
                            <i class="nav-icon fa fa-newspaper"></i>
                            <p>
                                Articles
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                    <a href="{{ route('admin.article-categories.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.article-categories.index','admin.article-categories.create','admin.article-categories.show',
                                    ]) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Category
                                    </p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('admin.articles.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                    'admin.articles.index', 'admin.articles.create', 'admin.articles.show' 
                                ]) }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Article
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>

                {{--
                | ===========================================================
                | Page Items
                | ===========================================================
                --}}
                <li class="nav-item">
                    <a href="{{ route('admin.page-items.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.page-items.index'
                    ]) }}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Terms and Condition & Privacy
                        </p>
                    </a>
                </li>
                        
                <li class="nav-item">
                    <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.faqs.index'
                    ]) }}">
                        <i class="nav-icon fas fa-question"></i>
                        <p>
                            FAQ
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.faq-categories.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.faq-categories.index'
                    ]) }}">
                        <i class="nav-icon fas fa-question"></i>
                        <p>
                            FAQ Category
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'admin.announcement-types.index','admin.announcement-types.create','admin.announcement-types.show',
                        'admin.announcements.index','admin.announcements.create','admin.announcements.show',
                        ]) }}">
                        <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.announcement-types.index','admin.announcement-types.create','admin.announcement-types.show',
                            'admin.announcements.index','admin.announcements.create','admin.announcements.show',
                        ]) }}">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>
                                Announcements
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- @if ($self->hasAnyPermission(['admin.users.crud'])) --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.announcement-types.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.announcement-types.index','admin.announcement-types.create','admin.announcement-types.show',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Type
                                        </p>
                                    </a>
                                </li>
                            {{-- @endif --}}
                            {{-- @if ($self->hasAnyPermission(['admin.users.crud'])) --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.announcements.index','admin.announcements.create','admin.announcements.show',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Announcement
                                        </p>
                                    </a>
                                </li>
                            {{-- @endif --}}
                        </ul>
                    </li>


                    @if ($self->hasAnyPermission(['admin.pages.crud', 'admin.page-items.crud', 'admin.articles.crud']))
                    <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                            'admin.pages.index','admin.pages.create','admin.pages.show',
                            'admin.page-items.index','admin.page-items.create','admin.page-items.show',
                        ]) }}">
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fas fa-feather"></i>
                            <p>
                                Content Management
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($self->hasAnyPermission(['admin.pages.crud']))
                                <li class="nav-item">
                                    <a href="{{ route('admin.pages.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.pages.index','admin.pages.create','admin.pages.show',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Pages
                                        </p>
                                    </a>
                                </li>
                            @endif
                            
                           @if ($self->hasAnyPermission(['admin.page-items.crud']))
                                <li class="nav-item">
                                    <a href="{{ route('admin.page-items.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.page-items.index','admin.page-items.create','admin.page-items.show',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Page Items
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <li class="nav-header">Admin Management</li>

                {{--
                | ===========================================================
                | ADMIN MANAGEMENT
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission(['admin.admin-users.crud', 'admin.roles.crud']))
                    <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                            'admin.admin-users.index','admin.admin-users.create','admin.admin-users.show',
                            'admin.roles.index', 'admin.roles.create', 'admin.roles.show',
                        ]) }}">
                        <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.admin-users.index','admin.admin-users.create','admin.admin-users.show',
                            'admin.roles.index', 'admin.roles.create', 'admin.roles.show',
                        ]) }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>
                                Admin Management
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($self->hasAnyPermission(['admin.admin-users.crud']))
                                <li class="nav-item">
                                    <a href="{{ route('admin.admin-users.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.admin-users.index','admin.admin-users.create','admin.admin-users.show',
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Admins
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if ($self->hasAnyPermission(['admin.roles.crud']))
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                        'admin.roles.index', 'admin.roles.create', 'admin.roles.show'
                                    ]) }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Roles & Permissions
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{--
                | ===========================================================
                | REPORTS
                | ===========================================================
                --}}
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'admin.reports.index'
                    ]) }}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Reports
                        </p>
                    </a>
                </li>



                {{--
                | ===========================================================
                | ACTIVITY LOGS
                | ===========================================================
                --}}
                @if ($self->hasAnyPermission(['admin.activity-logs.crud']))
                    <li class="nav-item">
                        <a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                            'admin.activity-logs.index',
                        ]) }}">
                            <i class="nav-icon fa fa-file-alt"></i>
                            <p>
                                Activity Logs
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

    </div>
</aside>