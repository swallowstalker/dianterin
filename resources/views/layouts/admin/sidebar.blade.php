<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{ url("/") }}/admin/order">
                    <i class="fa fa-dashboard fa-fw"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-shopping-cart fa-fw"></i> Order
                </a>
                <ul class="nav nav-second-level collapse" aria-expanded="true">
                    <li>
                        <a href="{{ url("/") }}/admin/order">
                            Overall
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/") }}/admin/order/processed">
                            Delivered
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/") }}/admin/order/unreceived">
                            Unreceived
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ url("/") }}/admin/user">
                    <i class="fa fa-user fa-fw"></i> User
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-credit-card fa-fw"></i> Transaction
                </a>
                <ul class="nav nav-second-level collapse" aria-expanded="true">
                    <li>
                        <a href="{{ url("/") }}/admin/transaction">
                            Overall
                        </a>
                    </li>
                    <li>
                        <a href="{{ url("/") }}/admin/transaction/order">
                            Order
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ url("/") }}/admin/profit">
                    <i class="fa fa-line-chart fa-fw"></i> Profit Chart
                </a>
            </li>

            <li>
                <a href="{{ url("/") }}/admin/order/summary">
                    <i class="fa fa-briefcase fa-fw"></i> Order Summary
                </a>
            </li>
            <li>
                <a href="{{ url("/") }}/admin/message">
                    <i class="fa fa-bullhorn fa-fw"></i> Broadcast Message
                </a>
            </li>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>