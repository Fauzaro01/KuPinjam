<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{route('home')}}"><img style="width:120px; height:120px;user-select:none;"
                            src="/assets/static/images/logo/kupinjam.webp" alt="Logo" srcset="" /></a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                <li class="sidebar-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @if(Auth::user()->hasRole('karyawan'))
                <li class="sidebar-item {{ (request()->is('kendaraan')) ? 'active' : '' }}">
                    <a href="{{route('kendaraan.index')}}" class="sidebar-link">
                        <i class="bi bi-car-front"></i>
                        <span>Kendaraan</span>
                    </a>
                </li>
                <li class="sidebar-item {{ (request()->is('peminjaman')) ? 'active' : '' }}">
                    <a href="{{route('kendaraan.index')}}" class="sidebar-link">
                        <i class="bi bi-bicycle"></i>
                        <span>Riwayat Peminjaman</span>
                    </a>
                </li>
                @elseif (Auth::user()->hasRole('administrator'))
                <li class="sidebar-item {{ (request()->is('kendaraan*'))  ? 'active' : ''}} has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-stack"></i>
                        <span>Kendaraan</span>
                    </a>
                    <ul class="submenu {{ (request()->is('kendaraan*')) ? 'active' : ''}}">
                        <li class="submenu-item {{ (request()->is('kendaraan/statistic')) ? 'active' : ''}}">
                            <a href="{{route('kendaraan.index')}} " class="submenu-link">Statistik Kendaraan (Belum)</a>
                        </li>
                        <li class="submenu-item {{ (request()->is('kendaraan')) ? 'active' : ''}}">
                            <a href="{{route('kendaraan.index')}}" class="submenu-link">Daftar Kendaraan</a>
                        </li>
                        <li class="submenu-item {{ (request()->is('kendaraan/create')) ? 'active' : ''}}">
                            <a href="{{route('kendaraan.create')}} " class="submenu-link">Tambah Kendaraan</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-collection-fill"></i>
                        <span>Kelola Peminjaman</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="extra-component-avatar.html" class="submenu-link">Avatar</a>
                        </li>
                        <li class="submenu-item">
                            <a href="extra-component-comment.html" class="submenu-link">Comment</a>
                        </li>
                        <li class="submenu-item">
                            <a href="extra-component-divider.html" class="submenu-link">Divider</a>
                        </li>
                        <li class="submenu-item">
                            <a href="extra-component-date-picker.html" class="submenu-link">Date Picker</a>
                        </li>
                        <li class="submenu-item">
                            <a href="extra-component-flag.html" class="submenu-link">Flag</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{ (request()->is('admin/usermanagement*'))  ? 'active' : ''}} has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>Pengelola Pengguna</span>
                    </a>
                    <ul class="submenu {{ (request()->is('admin/usermanagement*'))  ? 'active' : ''}}">
                        <li class="submenu-item {{ (request()->is('admin/usermanagement'))  ? 'active' : ''}}">
                            <a href="{{route('usermanagement.index')}}" class="submenu-link">Daftar Pengguna</a>
                        </li>
                        <li class="submenu-item {{ (request()->is('admin/usermanagement/create'))  ? 'active' : ''}}">
                            <a href="{{route('usermanagement.create')}}" class="submenu-link">Registrasi Pengguna</a>
                        </li>
                        <li class="submenu-item {{ (request()->is('admin/usermanagement/bulkcreate'))  ? 'active' : ''}}">
                            <a href="{{route('usermanagement.bulkcreate')}}" class="submenu-link">Registrasi Pengguna
                                Masal</a>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="sidebar-title">Setelan</li>
                <li class="sidebar-item">
                    <a type="button" data-bs-toggle="modal" data-bs-target="#default" class="sidebar-link">
                        <i class="bi bi-life-preserver"></i>
                        <span>Developer</span>
                    </a>
                </li>
                <li class="sidebar-item {{ (request()->is('account-security')) ? 'active' : ''}}">
                    <a href="{{route('accountSecurity')}}" class="sidebar-link">
                        <i class="bi bi-person-gear"></i>
                        <span>Account Security</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i class="bi bi-x"></i>
                        <span>Logout</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="default" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Pengembang KuPinjam</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="avatar avatar-2xl">
                                        <img src="http://res.cloudinary.com/dtzcamtgb/image/upload/v1739243312/Project/faces/ennirisy3ale24p5favs.png" alt="Avatar">
                                    </div>

                                    <h3 class="mt-3">Muhamad Fauzaan</h3>
                                    <p class="text-small">FullStack Developer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="avatar avatar-2xl">
                                        <img src="http://res.cloudinary.com/dtzcamtgb/image/upload/v1739243329/Project/faces/iusxh0sixgubpagin6em.jpg" alt="Avatar">
                                    </div>

                                    <h3 class="mt-3">Maulana Rivqi</h3>
                                    <p class="text-small">Innovation Manager</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="avatar avatar-2xl">
                                        <img src="https://res.cloudinary.com/dtzcamtgb/image/upload/v1739243324/Project/faces/b2kmvsx5tksw0se3hbjq.jpg" alt="Avatar">
                                    </div>

                                    <h3 class="mt-3">Muhammad Haikan SyahPutra</h3>
                                    <p class="text-small">UX Designer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ms-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Accept</span>
                </button>
            </div>
        </div>
    </div>
</div>