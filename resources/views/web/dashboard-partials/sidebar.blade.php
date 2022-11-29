<aside class="main-sidebar sidebar-dark-danger elevation-4">
    <a href="{{ route('web.dashboard') }}" class="brand-link bg-danger">
        @include('partials.brand')
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->renderImagePath() }}" class="img-circle elevation-2" style="width: 35px; height: 35px;">
            </div>
            <div class="info">
                <a href="{{ route('web.profiles.show') }}" class="d-block">
                    {{ auth()->user()->renderName() }}
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
                        'web.sample-items.index','web.sample-items.create','web.sample-items.show',
                    ]) }}">
                    <a href="javascript:void(0)" class="nav-link {{ $checker->route->areOnRoutes([
                        'web.sample-items.index','web.sample-items.create','web.sample-items.show',
                    ]) }}">
                        <i class="nav-icon fa fa-cubes"></i>
                        <p>
                            Sample
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('web.sample-items.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                                'web.sample-items.index','web.sample-items.create','web.sample-items.show',
                            ]) }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Sample Items
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('web.activity-logs.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
                        'web.activity-logs.index',
                    ]) }}">
                        <i class="nav-icon fa fa-file-alt"></i>
                        <p>
                            Activity Logs
                        </p>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</aside>