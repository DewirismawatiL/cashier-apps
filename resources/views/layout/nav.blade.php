<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('dashboard') }}" aria-expanded="false"><i
                            class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('products.index') }}" aria-expanded="false"><i class="mdi mdi-store"></i><span
                            class="hide-menu">Produk</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('pembelians.index') }}" aria-expanded="false"><i
                            class="mdi mdi-cart"></i><span class="hide-menu">Pembelian</span></a></li>
                @if (Auth::user()->role == 'admin')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('users.index') }}" aria-expanded="false"><i
                            class="mdi mdi-account-network"></i><span class="hide-menu">User</span></a></li>
                @endif
                <!-- Logout Button -->
                {{-- <li class="sidebar-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="sidebar-link waves-effect waves-dark sidebar-link btn w-100 text-start"
                            style="border: none; background: none; color: #808080;">
                            <i class="mdi mdi-logout"></i>
                            <span class="hide-menu">Logout</span>
                        </button>
                    </form>
                </li> --}}

            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
