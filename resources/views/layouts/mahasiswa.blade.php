<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan LP3I')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Averia Serif Libre';
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            overflow: hidden;
            background-color:#00328E;
        }

        .sidebar {
            width: 280px;
            background-color: white;
            color: #00328E;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .sidebar-header img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .sidebar-header h6 {
            margin: 0;
            font-size: 14px;
            line-height: 1.2;
        }

        .sidebar a {
            color: #00328E;
            display: flex;
            align-items: center;
            padding: 8px 20px;
            text-decoration: none;
            margin-top: 8px;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin-right: 20px;
        }

        .sidebar a.active {
            background-color: #00328E;
            color: white;
        }

        .sidebar a.active img {
            filter: brightness(0) invert(1);
        }

        .sidebar.show {
            transform: translateX(0);
          
        }

        .sidebarrr a img {
            width: 22px;
            height: 22px;
            margin-right: 15px;
        }
        .sidebar a:hover {
            background-color: #e6eeff;
        }

        .sidebar a.active:hover {
            background-color: #002a75;
        }

        .sidebarrr a img {
            width: 22px;
            height: 22px;
            margin-right: 15px;
            transition: filter 0.3s ease;
        }

        .content {
            padding-top: 60px; /* Adjust this value based on your navbar height */
            margin-left: 280px;
            flex: 1;
            transition: margin-left 0.3s ease;
            position: relative;
            height: 100vh;
        }

        .content.expanded {
            margin-left: 0;
        }

        
.notification-item {
    transition: background-color 0.3s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.mark-as-read {
    padding: 0;
    color: #6c757d;
    visibility: hidden;
}

.notification-item:hover .mark-as-read {
    visibility: visible;
}

.dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}



     #logout{
          background-color: #00328E;
          background-color: white; 
          border: 1px solid #00328E;
           color: #00328E; 
           width: 150px;
         border-radius: 8px;
            height: 40px; 
        }

        #logout:hover{
            transform: scale(1.1);
        }


        .navbar {
            background-color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            z-index: 1000;
            height: 60px; /* Adjust this value if needed */
            transition: left 0.3s ease;
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .notification-icon {
            font-size: 20px;
            color: #00328E;
            margin-right: 20px;
        }

        .profile {
            display: flex;
            align-items: center;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .profile-name {
            color: #00328E;
        }

        .sidebar-toggler {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: 100vh;
                transform: translateX(-100%);
            }

            .sidebar-header img {
               margin-top: 60px;
               max-width: 150px;
            }
            
            .content {
                margin-left: 0;
            }
            .navbar {
                left: 0;
            }
            .sidebar-toggler {
                display: block;
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1001;
                cursor: pointer;
                font-size: 24px;
                color: #333;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 5px 10px;
            }
            .content.expanded {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header mt-2 ms-5">
            <img src="{{ asset('images/logolp31.png') }}" alt="Logo" style="width: 80%; height: auto;">
        </div>
        <div class="sidebarrr ms-5 mt-4">
            <a href="{{ route('mahasiswa.books') }}" class="{{ request()->routeIs('mahasiswa.books') ? 'active' : '' }}">
                <img src="{{ asset('images/open-book (1).png') }}" alt="">Book
            </a>
       
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" id="logout" class="btn-logout mt-4 ms-3" ><img src="{{ asset('images/logout.png') }}" style="width: 22px; height: 22px; margin-right: 13px; " alt="">Logout</button>
            </form>
        </div>
    </div>

    <div class="navbar" id="navbar">
        <div class="navbar-right">
           
            <div class="profile">
                <a href="{{ route('mahasiswa.profile.show') }}" class="text-decoration-none">
                    <img src="{{ Auth::user()->profile_picture_url }}"
                    alt="Profile" class="profile-image">
                    <span class="profile-name me-2 mt-1">{{ Auth::user()->name }}</span>
                </a>
            </div>
            @if(auth()->check())
            <div class="dropdown">
                <button class="btn dropdown-toggle position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('images/notification.png') }}" alt="" width="20">
                    @php
                        $unreadCount = auth()->user()->userNotifications()->whereNull('read_at')->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end p-0" style="width: 320px; max-height: 480px; overflow-y: auto;">
                    <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Notifikasi</h6>
                        @if($unreadCount > 0)
                            <button class="btn btn-sm btn-link text-decoration-none mark-all-read">Tandai semua dibaca</button>
                        @endif
                    </div>
                    <div class="notifications-list">
                        @forelse(auth()->user()->userNotifications as $notification)
                            <div class="notification-item p-2 border-bottom {{ !$notification->read_at ? 'bg-light' : '' }}" 
                                 data-notification-id="{{ $notification->id }}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    @if(!$notification->read_at)
                                        <button class="btn btn-sm mark-as-read" data-notification-id="{{ $notification->id }}">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="notification-content">
                                    {{ $notification->data['message'] }}
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted">
                                Tidak ada notifikasi
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
       
        </div>
    </div>

    <div class="content" id="content">
        @yield('content')
    </div>

    <div class="sidebar-toggler" id="sidebar-toggler">
        ☰
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script>
        document.getElementById('sidebar-toggler').addEventListener('click', function(){
            var sidebar = document.getElementById('sidebar');
            var content = document.getElementById('content');
            var navbar = document.getElementById('navbar');
            sidebar.classList.toggle('show');
            content.classList.toggle('expanded');
        });

        document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menandai notifikasi sebagai dibaca
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                notificationItem.classList.remove('bg-light');
                const markAsReadBtn = notificationItem.querySelector('.mark-as-read');
                if (markAsReadBtn) {
                    markAsReadBtn.remove();
                }
                updateNotificationBadge();
            }
        });
    }

    // Fungsi untuk menandai semua notifikasi sebagai dibaca
    function markAllAsRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-light');
                    const markAsReadBtn = item.querySelector('.mark-as-read');
                    if (markAsReadBtn) {
                        markAsReadBtn.remove();
                    }
                });
                document.querySelector('.mark-all-read')?.remove();
                updateNotificationBadge();
            }
        });
    }

    // Fungsi untuk memperbarui badge notifikasi
    function updateNotificationBadge() {
        const badge = document.querySelector('#notificationDropdown .badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) - 1;
            if (currentCount <= 0) {
                badge.remove();
            } else {
                badge.textContent = currentCount;
            }
        }
    }

    // Event listener untuk tombol "Tandai dibaca"
    document.querySelectorAll('.mark-as-read').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const notificationId = button.dataset.notificationId;
            markAsRead(notificationId);
        });
    });

    // Event listener untuk tombol "Tandai semua dibaca"
    document.querySelector('.mark-all-read')?.addEventListener('click', (e) => {
        e.preventDefault();
        markAllAsRead();
    });

    function updateNotificationBadge() {
    fetch('/notifications/unread-count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const badge = document.querySelector('#notificationDropdown .badge');
        if (data.unread_count > 0) {
            if (!badge) {
                const newBadge = document.createElement('span');
                newBadge.classList.add('position-absolute', 'top-0', 'start-100', 'translate-middle', 'badge', 'rounded-pill', 'bg-danger');
                newBadge.textContent = data.unread_count;
                document.querySelector('#notificationDropdown').appendChild(newBadge);
            } else {
                badge.textContent = data.unread_count;
            }
        } else if (badge) {
            badge.remove();
        }
    });
}

// Panggil fungsi ini setelah setiap perubahan pada notifikasi
document.querySelectorAll('.mark-as-read').forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation();
        const notificationId = button.dataset.notificationId;
        markAsRead(notificationId);
        updateNotificationBadge(); // Panggil di sini
    });
});

document.querySelector('.mark-all-read')?.addEventListener('click', (e) => {
    e.preventDefault();
    markAllAsRead();
    updateNotificationBadge(); // Panggil di sini juga
});

});
    </script>
</body>
</html>
