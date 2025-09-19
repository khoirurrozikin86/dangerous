<!-- partial:partials/_sidebar.html -->
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            DT-<span>System</span>
        </a>

        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            @if (Auth::user()->can('permission.menu'))
                <li class="nav-item nav-category">Role & Permission</li>
                <li class="nav-item {{ request()->is('*/permission') || request()->is('*/roles') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#uiComponents" role="button"
                        aria-expanded="false" aria-controls="uiComponents">
                        <i class="link-icon" data-feather="feather"></i>
                        <span class="link-title">Role & Permission</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>
                    <div class="{{ request()->is('edit/permission/*') || request()->is('all/permission') || request()->is('add/permission') || request()->is('add/roles/permission') || request()->is('all/roles/permission') || request()->is('*/roles') || request()->is('admin/edit/roles/*') || request()->is('edit/roles/*') ? 'show' : 'collapse' }}"
                        id="uiComponents">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('all.permission') }}"
                                    class="nav-link {{ request()->is('all/permission') || request()->is('edit/permission/*') ? 'active' : '' }}">All
                                    Permission</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('all.roles') }}"
                                    class="nav-link {{ request()->is('*/roles') || request()->is('edit/roles/*') ? 'active' : '' }}">All
                                    Roles</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('add.roles.permission') }}"
                                    class="nav-link {{ request()->is('add/roles/permission') ? 'active' : '' }}">Add
                                    Role in Permission</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('all.roles.permission') }}"
                                    class="nav-link {{ request()->is('all/roles/permission') || request()->is('admin/edit/roles/*') ? 'active' : '' }}">All
                                    Role in Permission</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('admin.menu'))
                <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">

                    <a class="nav-link" data-bs-toggle="collapse" href="#forms" role="button" aria-expanded="false"
                        aria-controls="forms">
                        <i class="link-icon" data-feather="inbox"></i>
                        <span class="link-title">Manage Admin</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="{{ request()->is('*/admin') | request()->is('*/admin/*') ? 'show' : 'collapse' }}"
                        id="forms">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('all.admin') }}"
                                    class="nav-link {{ request()->is('*/admin') || 'edit/admin/*' ? 'active' : '' }}">All
                                    Admin</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('employee.menu'))
                <li class="nav-item nav-category">Master</li>
            @endif

            @if (Auth::user()->can('basicoperation.menu'))
                <li class="nav-item {{ request()->is('*/basicoperation') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#emailszex0" role="button"
                        aria-expanded="false" aria-controls="emailszex0">
                        <i class="link-icon" data-feather="user"></i>
                        <span class="link-title">Basic Operation</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="{{ request()->is('*/basicoperation') || request()->is('*/basicoperation/*') || request()->is('*/basicoperation') ? 'show' : 'collapse' }}"
                        id="emailszex0">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('all.basicoperation'))
                                <li class="nav-item">
                                    <a href="{{ route('all.basicoperation') }}"
                                        class="nav-link {{ request()->is('all/basicoperation') || request()->is('edit/basicoperation/*') ? 'active' : '' }}">All
                                        Basic Operation</a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('category.menu'))
                <li class="nav-item {{ request()->is('*/category') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#emailszeo" role="button" aria-expanded="false"
                        aria-controls="emailsze">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Category</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="{{ request()->is('*/category') || request()->is('*/category/*') || request()->is('*/category') ? 'show' : 'collapse' }}"
                        id="emailszeo">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('all.category'))
                                <li class="nav-item">
                                    <a href="{{ route('all.category') }}"
                                        class="nav-link {{ request()->is('all/category') || request()->is('edit/category/*') ? 'active' : '' }}">All
                                        Category</a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('employee.menu'))
                <li class="nav-item {{ request()->is('*/employee') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#emailsze" role="button"
                        aria-expanded="false" aria-controls="emailsze">
                        <i class="link-icon" data-feather="user"></i>
                        <span class="link-title">Employee</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="{{ request()->is('*/employee') || request()->is('*/employee/*') || request()->is('*/employee') ? 'show' : 'collapse' }}"
                        id="emailsze">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('all.employee'))
                                <li class="nav-item">
                                    <a href="{{ route('all.employee') }}"
                                        class="nav-link {{ request()->is('all/employee') || request()->is('edit/employee/*') ? 'active' : '' }}">All
                                        Employee</a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('item.menu'))
                <li class="nav-item {{ request()->is('*/item') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#emailszec" role="button"
                        aria-expanded="false" aria-controls="emailsze">
                        <i class="link-icon" data-feather="book"></i>
                        <span class="link-title">Item</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="{{ request()->is('*/item') || request()->is('*/item/*') || request()->is('*/item') ? 'show' : 'collapse' }}"
                        id="emailszec">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('all.item'))
                                <li class="nav-item">
                                    <a href="{{ route('all.item') }}"
                                        class="nav-link {{ request()->is('all/item') || request()->is('edit/item/*') ? 'active' : '' }}">All
                                        Item</a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            <li class="nav-item nav-category">Transaction</li>
            @if (Auth::user()->can('transaction.menu'))

                <li class="nav-item {{ request()->is('*/transaction') ? 'active' : '' }}">
                    @if (Auth::user()->can('all.transaction'))
                        <a class="nav-link" data-bs-toggle="collapse" href="#emailsyout" role="button"
                            aria-expanded="false" aria-controls="emailsyout">
                            <i class="link-icon" data-feather="check-circle"></i>
                            <span class="link-title">Transaction</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                    @endif
                    <div class="{{ request()->is('*/transaction') || request()->is('*/transaction/*') || request()->is('*/transaction') ? 'show' : 'collapse' }}"
                        id="emailsyout">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('all.transaction'))
                                <li class="nav-item">
                                    <a href="{{ route('add.transaction') }}"
                                        class="nav-link {{ request()->is('add/transaction') ? 'active' : '' }}">Add
                                        Trasaction IN</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('all.transaction'))
                                <li class="nav-item">
                                    <a href="{{ route('add.transactionout') }}"
                                        class="nav-link {{ request()->is('add/transactionout') ? 'active' : '' }}">Add
                                        Trasaction OUT</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('all.transaction'))
                                <li class="nav-item">
                                    <a href="{{ route('all.transaction') }}"
                                        class="nav-link {{ request()->is('all/transaction') ? 'active' : '' }}">All
                                        Transaction</a>
                                </li>
                            @endif

                        </ul>
                    </div>

                </li>

                <li
                    class="nav-item {{ request()->is('*/peminjaman') || request()->is('*/peminjaman') || request()->is('*/photoreturn') || request()->is('*/serahterima') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#emailsyout1" role="button"
                        aria-expanded="false" aria-controls="emailsyout1">
                        <i class="link-icon" data-feather="check-circle"></i>
                        <span class="link-title">Dangerous Tool</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="{{ request()->is('*/peminjaman') || request()->is('*/peminjaman/*') || request()->is('*/peminjaman') || request()->is('*/photoreturn') || request()->is('*/serahterima') ? 'show' : 'collapse' }}"
                        id="emailsyout1">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('peminjaman.menu'))
                                <li class="nav-item">
                                    <a href="{{ route('add.peminjaman') }}"
                                        class="nav-link {{ request()->is('add/peminjaman') ? 'active' : '' }}">Peminjaman</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('peminjaman.menu'))
                                <li class="nav-item">
                                    <a href="{{ route('add.peminjamanrt') }}"
                                        class="nav-link {{ request()->is('add/peminjamanrt') ? 'active' : '' }}">Pengembalian</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('peminjaman.menu'))
                                <li class="nav-item">
                                    <a href="{{ route('all.peminjaman') }}"
                                        class="nav-link {{ request()->is('all/peminjaman') ? 'active' : '' }}">All
                                        peminjaman</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('photoreturn.menu'))
                                <li class="nav-item">
                                    <a href="{{ route('add.photoreturn') }}"
                                        class="nav-link {{ request()->is('add/photoreturn') ? 'active' : '' }}">Upload
                                        Pengembalian</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('photoreturn.menu'))
                                <li class="nav-item">
                                    <a href="{{ route('all.photoreturn') }}"
                                        class="nav-link {{ request()->is('all/photoreturn') ? 'active' : '' }}">All
                                        Photo</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('serahterima.menu'))
                                <li class="nav-item">
                                    <a href="{{ route('all.serahterima') }}"
                                        class="nav-link {{ request()->is('all/serahterima') ? 'active' : '' }}">Serah
                                        Terima</a>
                                </li>
                            @endif

                        </ul>
                    </div>

                </li>

                @if (Auth::user()->can('training.menu'))
                    <li class="nav-item {{ request()->is('*/training') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#emailszex0z1" role="button"
                            aria-expanded="false" aria-controls="emailszex0z1">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">Training Sewing</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="{{ request()->is('*/training') || request()->is('*/training/*') || request()->is('*/training') ? 'show' : 'collapse' }}"
                            id="emailszex0z1">
                            <ul class="nav sub-menu">

                                @if (Auth::user()->can('all.training'))
                                    <li class="nav-item">
                                        <a href="{{ route('all.training') }}"
                                            class="nav-link {{ request()->is('all/training') || request()->is('edit/training/*') ? 'active' : '' }}">All
                                            Training</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('add.training') }}"
                                            class="nav-link {{ request()->is('add/training/*') ? 'active' : '' }}">Add
                                            Training</a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif

            @endif

        </ul>
    </div>
</nav>
