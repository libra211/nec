<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'NEC Admin' }} - NEC South Sudan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    <style>
        :root { --nec-green: #2E8B57; --nec-gold: #D4AF37; --nec-blue: #1a3c8f; --nec-red: #8B0000; --sidebar-width: 260px; }
    </style>
    @yield('extra_css')
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <nav class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    <img src="{{ asset('assets/images/nec-logo-white.png') }}" alt="NEC" height="35">
                    <span>NEC Admin</span>
                </a>
            </div>

            <ul class="sidebar-nav">
                @php
                    $perms = $adminPermissions ?? [];
                    $role = $adminRole ?? 'viewer';
                    $has = fn(string $slug) => $role === 'super_admin' || in_array($slug, $perms);
                @endphp

                <li class="sidebar-section">Main</li>
                @if($has('dashboard.view'))
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-section">Elections</li>
                @if($has('parties.view'))
                <li>
                    <a href="{{ route('admin.parties.index') }}" class="{{ request()->routeIs('admin.parties.*') ? 'active' : '' }}">
                        <i class="fas fa-flag"></i><span>Political Parties</span>
                    </a>
                </li>
                @endif
                @if($has('constituencies.view'))
                <li>
                    <a href="{{ route('admin.constituencies.index') }}" class="{{ request()->routeIs('admin.constituencies.*') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt"></i><span>Constituencies</span>
                    </a>
                </li>
                @endif
                @if($has('candidates.view'))
                <li>
                    <a href="{{ route('admin.candidates.index') }}" class="{{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i><span>Candidates</span>
                    </a>
                </li>
                @endif
                @if($has('results.view'))
                <li>
                    <a href="{{ route('admin.results.index') }}" class="{{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                        <i class="fas fa-poll"></i><span>Results</span>
                    </a>
                </li>
                @endif
                @if($has('voters.view'))
                <li>
                    <a href="{{ route('admin.voters.index') }}" class="{{ request()->routeIs('admin.voters.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i><span>Voters</span>
                    </a>
                </li>
                @endif
                @if($has('agents.view'))
                <li>
                    <a href="{{ route('admin.agents.index') }}" class="{{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i><span>Registration Agents</span>
                    </a>
                </li>
                @endif
                @if($has('observers.view'))
                <li>
                    <a href="{{ route('admin.observers.index') }}" class="{{ request()->routeIs('admin.observers.*') ? 'active' : '' }}">
                        <i class="fas fa-eye"></i><span>Observers</span>
                    </a>
                </li>
                @endif
                @if($has('commissioners.view'))
                <li>
                    <a href="{{ route('admin.commissioners.index') }}" class="{{ request()->routeIs('admin.commissioners.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i><span>Commissioners</span>
                    </a>
                </li>
                @endif
                @if($has('polling-stations.view'))
                <li>
                    <a href="{{ route('admin.polling-stations.index') }}" class="{{ request()->routeIs('admin.polling-stations.*') ? 'active' : '' }}">
                        <i class="fas fa-vote-yea"></i><span>Polling Stations</span>
                    </a>
                </li>
                @endif
                @if($has('polling-stations.view'))
                <li>
                    <a href="{{ route('admin.geographic.index') }}" class="{{ request()->routeIs('admin.geographic.*') ? 'active' : '' }}">
                        <i class="fas fa-map-marked-alt"></i><span>Geographic</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-section">Content</li>
                @if($has('news.view'))
                <li>
                    <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i><span>News</span>
                    </a>
                </li>
                @endif
                @if($has('announcements.view'))
                <li>
                    <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i><span>Announcements</span>
                    </a>
                </li>
                @endif
                @if($has('gallery.view'))
                <li>
                    <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i><span>Gallery</span>
                    </a>
                </li>
                @endif
                @if($has('videos.view'))
                <li>
                    <a href="{{ route('admin.videos.index') }}" class="{{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                        <i class="fas fa-video"></i><span>Videos</span>
                    </a>
                </li>
                @endif
                @if($has('speeches.view'))
                <li>
                    <a href="{{ route('admin.speeches.index') }}" class="{{ request()->routeIs('admin.speeches.*') ? 'active' : '' }}">
                        <i class="fas fa-comment-dots"></i><span>Speeches</span>
                    </a>
                </li>
                @endif
                @if($has('faqs.view'))
                <li>
                    <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i><span>FAQs</span>
                    </a>
                </li>
                @endif
                @if($has('education.view'))
                <li>
                    <a href="{{ route('admin.education.index') }}" class="{{ request()->routeIs('admin.education.*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i><span>Education</span>
                    </a>
                </li>
                @endif
                @if($has('subscribers.view'))
                <li>
                    <a href="{{ route('admin.subscribers.index') }}" class="{{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope-open-text"></i><span>Subscribers</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-section">Data Management</li>
                @if($has('voter-transfers.view'))
                <li>
                    <a href="{{ route('admin.voter-transfers.index') }}" class="{{ request()->routeIs('admin.voter-transfers.*') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt"></i><span>Voter Transfers</span>
                    </a>
                </li>
                @endif
                @if($has('contacts.view'))
                <li>
                    <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i><span>Contact Messages</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('admin.complaints.index') }}" class="{{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i><span>Complaints</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i><span>Reports</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.downloads.index') }}" class="{{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}">
                        <i class="fas fa-download"></i><span>Downloads</span>
                    </a>
                </li>

                <li class="sidebar-section">Election Operations</li>
                <li>
                    <a href="{{ route('admin.polling-staff.index') }}" class="{{ request()->routeIs('admin.polling-staff.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i><span>Polling Staff</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ballots.index') }}" class="{{ request()->routeIs('admin.ballots.*') ? 'active' : '' }}">
                        <i class="fas fa-box-open"></i><span>Ballot Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.petitions.index') }}" class="{{ request()->routeIs('admin.petitions.*') ? 'active' : '' }}">
                        <i class="fas fa-gavel"></i><span>Election Petitions</span>
                    </a>
                </li>

                <li class="sidebar-section">System</li>
                @if($has('users.view'))
                <li>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i><span>User Management</span>
                    </a>
                </li>
                @endif
                @if($has('staff.view'))
                <li>
                    <a href="{{ route('admin.staff.index') }}" class="{{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fas fa-hard-hat"></i><span>Staff Management</span>
                    </a>
                </li>
                @endif
                @if($has('permissions.view'))
                <li>
                    <a href="{{ route('admin.permissions.index') }}" class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="fas fa-key"></i><span>Permissions</span>
                    </a>
                </li>
                @endif
                @if($has('activity-logs.view'))
                <li>
                    <a href="{{ route('admin.activity-logs.index') }}" class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i><span>Activity Logs</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('admin.security-logs.index') }}" class="{{ request()->routeIs('admin.security-logs.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i><span>Security Logs</span>
                    </a>
                </li>
                @if($has('settings.view'))
                <li>
                    <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i><span>Settings</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i><span>View Website</span>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Main Content --}}
        <div class="admin-content">
            {{-- Top Bar --}}
            <div class="admin-topbar">
                <button class="btn btn-sm" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-right">
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-bell me-1"></i>
                            @php
                                $notificationCount = session('admin_notification_count', 0);
                            @endphp
                            @if($notificationCount > 0)
                                <span class="badge bg-danger">{{ $notificationCount }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
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
                    <div class="dropdown ms-3">
                        <button class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Admin" class="rounded-circle" width="32" height="32">
                            <span class="ms-1">{{ session('admin_user_name', 'Admin') }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small"><i class="fas fa-id-badge me-1"></i>{{ ucfirst(str_replace('_', ' ', session('admin_role', 'Administrator'))) }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="admin-page-content">
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

        $('#sidebarToggle').on('click', function () {
            $('#adminSidebar').toggleClass('collapsed');
        });

        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?', text: 'This action cannot be undone!', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({ url: url, type: 'DELETE', success: function () { Swal.fire('Deleted!', 'Record has been deleted.', 'success').then(function () { location.reload(); }); } });
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
