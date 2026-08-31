<!DOCTYPE html>
<html lang="en" data-theme="light">
@php
    if ($adminAvatarSess = session('admin_avatar')) {
        $adminAvatar = $adminAvatarSess ? asset('storage/' . $adminAvatarSess) : asset('assets/images/default-avatar.png');
    } elseif ($adminEmail = session('admin_email')) {
        $adminAvatar = asset('assets/images/default-avatar.png');
        $adminUser = \App\Models\User::where('email', $adminEmail)->first();
        if ($adminUser && $adminUser->avatar) {
            $adminAvatar = asset('storage/' . $adminUser->avatar);
        }
    } else {
        $adminAvatar = asset('assets/images/default-avatar.png');
    }
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - NEC South Sudan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    @yield('extra_css')
</head>
<body>
    {{-- Sidebar --}}
    <nav class="sidebar" id="adminmenu">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <span class="sidebar-logo">
                <img src="{{ \App\Helpers\NecHelper::setting_get('logo', asset('assets/images/logos/neclogo.jpeg')) }}" alt="NEC">
            </span>
            <span class="sidebar-brand-text">
                <span class="brand-name">NEC</span>
                <span class="brand-sub">South Sudan</span>
            </span>
        </a>

        <div class="sidebar-nav">
            @php
                $perms = $adminPermissions ?? [];
                $role = $adminRole ?? 'viewer';
                $has = fn(string $slug) => $role === 'super_admin' || in_array($slug, $perms);
            @endphp

            <div class="menu-sep"></div>
            <div class="sidebar-section-title">Main</div>
            @if($has('dashboard.view'))
            <div class="menu-top{{ request()->routeIs('admin.dashboard') ? ' current' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </div>
            @endif

            <div class="sidebar-section-title">Elections</div>
            @if($has('parties.view'))
            <div class="menu-top{{ request()->routeIs('admin.parties.*') ? ' current' : '' }}">
                <a href="{{ route('admin.parties.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-flag"></i></span>
                    <span class="menu-text">Political Parties</span>
                </a>
            </div>
            @endif
            @if($has('constituencies.view'))
            <div class="menu-top{{ request()->routeIs('admin.constituencies.*') ? ' current' : '' }}">
                <a href="{{ route('admin.constituencies.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <span class="menu-text">Constituencies</span>
                </a>
            </div>
            @endif
            @if($has('candidates.view'))
            <div class="menu-top{{ request()->routeIs('admin.candidates.*') ? ' current' : '' }}">
                <a href="{{ route('admin.candidates.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-user-tie"></i></span>
                    <span class="menu-text">Candidates</span>
                </a>
            </div>
            @endif
            @if($has('results.view'))
            <div class="menu-top{{ request()->routeIs('admin.results.*') ? ' current' : '' }}">
                <a href="{{ route('admin.results.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-poll"></i></span>
                    <span class="menu-text">Results</span>
                </a>
            </div>
            @endif
            @if($has('voters.view'))
            <div class="menu-top{{ request()->routeIs('admin.voters.*') ? ' current' : '' }}">
                <a href="{{ route('admin.voters.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text">Voters</span>
                </a>
            </div>
            @endif
            @if($has('agents.view'))
            <div class="menu-top{{ request()->routeIs('admin.agents.*') ? ' current' : '' }}">
                <a href="{{ route('admin.agents.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-user-check"></i></span>
                    <span class="menu-text">Registration Agents</span>
                </a>
            </div>
            @endif
            @if($has('observers.view'))
            <div class="menu-top{{ request()->routeIs('admin.observers.*') ? ' current' : '' }}">
                <a href="{{ route('admin.observers.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-eye"></i></span>
                    <span class="menu-text">Observers</span>
                </a>
            </div>
            @endif
            @if($has('commissioners.view'))
            <div class="menu-top{{ request()->routeIs('admin.commissioners.*') ? ' current' : '' }}">
                <a href="{{ route('admin.commissioners.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-user-tie"></i></span>
                    <span class="menu-text">Commissioners</span>
                </a>
            </div>
            @endif
            @if($has('polling-stations.view'))
            <div class="menu-top{{ request()->routeIs('admin.polling-stations.*') ? ' current' : '' }}">
                <a href="{{ route('admin.polling-stations.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-vote-yea"></i></span>
                    <span class="menu-text">Polling Stations</span>
                </a>
            </div>
            <div class="menu-top{{ request()->routeIs('admin.geographic.*') ? ' current' : '' }}">
                <a href="{{ route('admin.geographic.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-map-marked-alt"></i></span>
                    <span class="menu-text">Geographic</span>
                </a>
            </div>
            @endif

            <div class="sidebar-section-title">Content</div>
            @if($has('news.view'))
            <div class="menu-top{{ request()->routeIs('admin.news.*') ? ' current' : '' }}">
                <a href="{{ route('admin.news.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-newspaper"></i></span>
                    <span class="menu-text">News</span>
                </a>
            </div>
            @endif
            @if($has('announcements.view'))
            <div class="menu-top{{ request()->routeIs('admin.announcements.*') ? ' current' : '' }}">
                <a href="{{ route('admin.announcements.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-bullhorn"></i></span>
                    <span class="menu-text">Announcements</span>
                </a>
            </div>
            @endif
            @if($has('events.view'))
            <div class="menu-top{{ request()->routeIs('admin.events.*') ? ' current' : '' }}">
                <a href="{{ route('admin.events.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-calendar-alt"></i></span>
                    <span class="menu-text">Events</span>
                </a>
            </div>
            @endif
            @if($has('gallery.view'))
            <div class="menu-top{{ request()->routeIs('admin.gallery.*') ? ' current' : '' }}">
                <a href="{{ route('admin.gallery.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-images"></i></span>
                    <span class="menu-text">Gallery</span>
                </a>
            </div>
            @endif
            @if($has('videos.view'))
            <div class="menu-top{{ request()->routeIs('admin.videos.*') ? ' current' : '' }}">
                <a href="{{ route('admin.videos.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-video"></i></span>
                    <span class="menu-text">Videos</span>
                </a>
            </div>
            @endif
            @if($has('speeches.view'))
            <div class="menu-top{{ request()->routeIs('admin.speeches.*') ? ' current' : '' }}">
                <a href="{{ route('admin.speeches.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-comment-dots"></i></span>
                    <span class="menu-text">Speeches</span>
                </a>
            </div>
            @endif
            @if($has('faqs.view'))
            <div class="menu-top{{ request()->routeIs('admin.faqs.*') ? ' current' : '' }}">
                <a href="{{ route('admin.faqs.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-question-circle"></i></span>
                    <span class="menu-text">FAQs</span>
                </a>
            </div>
            @endif
            @if($has('education.view'))
            <div class="menu-top{{ request()->routeIs('admin.education.*') ? ' current' : '' }}">
                <a href="{{ route('admin.education.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="menu-text">Education</span>
                </a>
            </div>
            @endif
            @if($has('subscribers.view'))
            <div class="menu-top{{ request()->routeIs('admin.subscribers.*') ? ' current' : '' }}">
                <a href="{{ route('admin.subscribers.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-envelope-open-text"></i></span>
                    <span class="menu-text">Subscribers</span>
                </a>
            </div>
            @endif

            <div class="sidebar-section-title">Data Management</div>
            @if($has('voter-transfers.view'))
            <div class="menu-top{{ request()->routeIs('admin.voter-transfers.*') ? ' current' : '' }}">
                <a href="{{ route('admin.voter-transfers.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-exchange-alt"></i></span>
                    <span class="menu-text">Voter Transfers</span>
                </a>
            </div>
            @endif
            @if($has('contacts.view'))
            <div class="menu-top{{ request()->routeIs('admin.contacts.*') ? ' current' : '' }}">
                <a href="{{ route('admin.contacts.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-envelope"></i></span>
                    <span class="menu-text">Contact Messages</span>
                </a>
            </div>
            @endif
            <div class="menu-top{{ request()->routeIs('admin.complaints.*') ? ' current' : '' }}">
                <a href="{{ route('admin.complaints.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span class="menu-text">Complaints</span>
                </a>
            </div>
            <div class="menu-top{{ request()->routeIs('admin.reports.*') ? ' current' : '' }}">
                <a href="{{ route('admin.reports.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-file-alt"></i></span>
                    <span class="menu-text">Reports</span>
                </a>
            </div>
            <div class="menu-top{{ request()->routeIs('admin.downloads.*') ? ' current' : '' }}">
                <a href="{{ route('admin.downloads.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-download"></i></span>
                    <span class="menu-text">Downloads</span>
                </a>
            </div>

            <div class="sidebar-section-title">Election Operations</div>
            <div class="menu-top{{ request()->routeIs('admin.polling-staff.*') ? ' current' : '' }}">
                <a href="{{ route('admin.polling-staff.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-users-cog"></i></span>
                    <span class="menu-text">Polling Staff</span>
                </a>
            </div>
            <div class="menu-top{{ request()->routeIs('admin.ballots.*') ? ' current' : '' }}">
                <a href="{{ route('admin.ballots.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-box-open"></i></span>
                    <span class="menu-text">Ballot Management</span>
                </a>
            </div>
            <div class="menu-top{{ request()->routeIs('admin.petitions.*') ? ' current' : '' }}">
                <a href="{{ route('admin.petitions.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-gavel"></i></span>
                    <span class="menu-text">Election Petitions</span>
                </a>
            </div>

            <div class="sidebar-section-title">System</div>
            @if($has('users.view'))
            <div class="menu-top{{ request()->routeIs('admin.users.*') ? ' current' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-user-shield"></i></span>
                    <span class="menu-text">User Management</span>
                </a>
            </div>
            @endif
            @if($has('staff.view'))
            <div class="menu-top{{ request()->routeIs('admin.staff.*') ? ' current' : '' }}">
                <a href="{{ route('admin.staff.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-hard-hat"></i></span>
                    <span class="menu-text">Staff Management</span>
                </a>
            </div>
            @endif
            @if($has('permissions.view'))
            <div class="menu-top{{ request()->routeIs('admin.permissions.*') ? ' current' : '' }}">
                <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-key"></i></span>
                    <span class="menu-text">Permissions</span>
                </a>
            </div>
            @endif
            @if($has('activity-logs.view'))
            <div class="menu-top{{ request()->routeIs('admin.activity-logs.*') ? ' current' : '' }}">
                <a href="{{ route('admin.activity-logs.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-history"></i></span>
                    <span class="menu-text">Activity Logs</span>
                </a>
            </div>
            @endif
            <div class="menu-top{{ request()->routeIs('admin.security-logs.*') ? ' current' : '' }}">
                <a href="{{ route('admin.security-logs.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-shield-alt"></i></span>
                    <span class="menu-text">Security Logs</span>
                </a>
            </div>
            @if($has('settings.view'))
            <div class="menu-top{{ request()->routeIs('admin.settings.*') ? ' current' : '' }}">
                <a href="{{ route('admin.settings.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-cog"></i></span>
                    <span class="menu-text">Settings</span>
                </a>
            </div>
            @endif
            <div class="menu-top">
                <a href="{{ route('home') }}" target="_blank" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-external-link-alt"></i></span>
                    <span class="menu-text">View Website</span>
                </a>
            </div>
        </div>

    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Top Bar --}}
        <div class="topbar">
            <div class="topbar-left">
                <button class="toggle-btn" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="topbar-center">
                <div class="nec-clock">
                    <span class="time" id="clockTime"></span>
                    <span class="date" id="clockDate"></span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="dropdown">
                    <a href="#" class="topbar-icon-btn" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @php
                            $notificationCount = session('admin_notification_count', 0);
                        @endphp
                        @if($notificationCount > 0)
                            <span class="badge-count">{{ $notificationCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="min-width: 280px;">
                        <h6 class="dropdown-header">Notifications</h6>
                        @if(session('admin_notifications') && count(session('admin_notifications', [])) > 0)
                            @foreach(session('admin_notifications', []) as $notification)
                                <a class="dropdown-item" href="{{ $notification['url'] ?? '#' }}">
                                    <i class="fas fa-{{ $notification['icon'] ?? 'bell' }} text-{{ $notification['color'] ?? 'primary' }} me-2"></i>{{ $notification['message'] ?? '' }}
                                </a>
                            @endforeach
                        @else
                            <a class="dropdown-item text-muted" href="#">No new notifications</a>
                        @endif
                    </div>
                </div>
                <div class="topbar-divider"></div>
                <div class="dropdown topbar-user-dropdown">
                    <a href="#" class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <img src="{{ $adminAvatar }}" alt="Admin" style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                            <span class="online-dot"></span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                        <div class="dropdown-header d-flex align-items-center gap-2 px-3 py-2">
                            <img src="{{ $adminAvatar }}" alt="Avatar" class="rounded-circle" width="36" height="36">
                            <div>
                                <div class="fw-bold small">{{ session('admin_user_name', 'Admin') }}</div>
                                <div class="text-muted" style="font-size:11px">{{ session('admin_email', '') }}</div>
                            </div>
                        </div>
                        <div class="dropdown-divider my-1"></div>
                        <a class="dropdown-item" href="{{ route('admin.settings.index', ['tab' => 'profile']) }}"><i class="fas fa-user-circle fa-fw me-2"></i>My Profile</a>
                        <a class="dropdown-item" href="{{ route('admin.settings.index', ['tab' => 'login-logs']) }}"><i class="fas fa-history fa-fw me-2"></i>Login History</a>
                        <a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog fa-fw me-2"></i>Settings</a>
                        <div class="dropdown-divider my-1"></div>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="container-fluid">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="mb-0">&copy; {{ date('Y') }} National Election Commission South Sudan. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Sidebar toggle
        $('#sidebarToggle').on('click', function () {
            $('body').toggleClass('sidebar-collapsed');
        });

        // Live clock (update every 60s to avoid ResizeObserver loops with Chart.js)
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('clockTime').textContent = hours + ':' + minutes;
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById('clockDate').textContent = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
        }
        updateClock();
        setInterval(updateClock, 60000);

        // Dark mode toggle
        function toggleDarkMode() {
            const html = document.documentElement;
            const theme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', theme);
            localStorage.setItem('nec-admin-theme', theme);
        }
        (function() {
            const saved = localStorage.getItem('nec-admin-theme');
            if (saved) document.documentElement.setAttribute('data-theme', saved);
        })();

        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?', text: 'This action cannot be undone!', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({ url: url, type: 'DELETE', success: function () { Swal.fire('Deleted!', 'Record has been deleted.', 'success').then(function () { location.reload(); }); }, error: function (xhr) { Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete record.', 'error'); } });
                }
            });
        }

        function toggleStatus(url, enable) {
            Swal.fire({
                title: enable ? 'Activate this record?' : 'Deactivate this record?',
                icon: 'question',
                showCancelButton: true, confirmButtonColor: '#2E8B57', cancelButtonColor: '#6c757d',
                confirmButtonText: enable ? 'Yes, activate' : 'Yes, deactivate'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({ url: url, type: 'PATCH', data: { is_active: enable ? 1 : 0 }, success: function () { Swal.fire('Done!', 'Status updated.', 'success').then(function () { location.reload(); }); } });
                }
            });
        }
    </script>
    @yield('extra_scripts')
</body>
</html>
