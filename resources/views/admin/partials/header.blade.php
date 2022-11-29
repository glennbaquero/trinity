<nav class="main-header navbar navbar-expand border-bottom trinity__primary-color">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-light" data-widget="pushmenu" href="javascript:void(0)"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto d-flex align-items-center justify-content-between">
        <li class="nav-item">
            <a class="nav-link text-light" href="{{ route('admin.notifications.index') }}">
                <i class="fa fa-bell mr-2"></i>

                <count-listener
                class="badge-warning navbar-badge"
                fetch-url="{{ route('admin.counts.fetch.notifications') }}"
                event="update-notification-count"
                ></count-listener>
            </a>
        </li>

        <li>
            <a class="btn text-light" role="button" href="{{ route('admin.logout') }}">Logout</a>
        </li>

    </ul>
</nav>
