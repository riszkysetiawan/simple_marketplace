@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
    <style>
        /* ========================================
                                       PROFILE BANNER
                                    ======================================== */
        .profile-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0 40px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .profile-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        /* Avatar Styles */
        .avatar-wrapper {
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .profile-avatar-placeholder {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            color: white;
            border: 5px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 3px solid white;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .avatar-upload-btn:hover {
            transform: scale(1.1);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Badge Custom */
        .badge-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        /* ========================================
                                       SIDEBAR
                                    ======================================== */
        .profile-sidebar {
            position: sticky;
            top: 100px;
        }

        /* Mini Stats */
        .stats-mini {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .stat-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-info h4 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-info p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }

        /* Navigation Menu */
        .nav-menu {
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .nav-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 8px;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }

        .nav-menu-item i:first-child {
            font-size: 20px;
            width: 24px;
        }

        .nav-menu-item:hover {
            background: #f3f4f6;
            color: #667eea;
            padding-left: 20px;
        }

        .nav-menu-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .nav-menu-item.active:hover {
            padding-left: 16px;
        }

        /* ========================================
                                       STAT CARDS
                                    ======================================== */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .stat-card-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            flex-shrink: 0;
        }

        .stat-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-primary .stat-card-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .stat-warning .stat-card-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .stat-success .stat-card-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-info {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .stat-info .stat-card-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-card-content h3 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }

        .stat-card-content h6 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .stat-card-content p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        /* ========================================
                                       PROFILE CARDS
                                    ======================================== */
        .profile-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .profile-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
        }

        .profile-card-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .profile-card-body {
            padding: 30px 25px;
        }

        /* Info Items */
        .info-item {
            margin-bottom: 20px;
        }

        .info-item label {
            display: block;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item p {
            margin: 0;
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 3px solid #667eea;
        }

        /* ========================================
                                       QUICK ACTION CARDS
                                    ======================================== */
        .quick-action-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .quick-action-card i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
            display: block;
        }

        .quick-action-card h6 {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .quick-action-card p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* ========================================
                                       FORM STYLES
                                    ======================================== */
        .form-floating>label {
            color: #6b7280;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            color: #667eea;
        }

        /* ========================================
                                       NOTIFICATION ITEMS
                                    ======================================== */
        .notification-item {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item h6 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-input {
            width: 50px;
            height: 26px;
            cursor: pointer;
        }

        /* ========================================
                                       BUTTONS
                                    ======================================== */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid #e5e7eb;
            color: #6b7280;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #4b5563;
        }

        .btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4);
        }

        /* ========================================
                                       RESPONSIVE
                                    ======================================== */
        @media (max-width: 991px) {
            .profile-banner {
                padding: 40px 0 30px;
            }

            .profile-avatar,
            .profile-avatar-placeholder {
                width: 100px;
                height: 100px;
            }

            .profile-sidebar {
                position: relative;
                top: 0;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-card-icon {
                width: 60px;
                height: 60px;
                font-size: 28px;
            }

            .stat-card-content h3 {
                font-size: 24px;
            }
        }

        @media (max-width: 575px) {

            .profile-avatar,
            .profile-avatar-placeholder {
                width: 80px;
                height: 80px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-card-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
        }

        /* ========================================
                                       ANIMATIONS
                                    ======================================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-pane.active {
            animation: fadeInUp 0.5s ease;
        }

        /* Print Styles */
        @media print {

            .profile-sidebar,
            .avatar-upload-btn,
            .btn,
            .nav-menu {
                display: none !important;
            }

            .profile-banner {
                background: #667eea !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Profile Header Banner -->
    <div class="profile-banner">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <!-- Avatar -->
                        <div class="position-relative avatar-wrapper">
                            @if ($user->avatar)
                                @if (str_starts_with($user->avatar, 'http'))
                                    <img src="{{ $user->avatar }}" class="profile-avatar" alt="Avatar" id="profileAvatar">
                                @else
                                    <img src="{{ Storage::url($user->avatar) }}" class="profile-avatar" alt="Avatar"
                                        id="profileAvatar">
                                @endif
                            @else
                                <div class="profile-avatar-placeholder">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif

                            <button class="avatar-upload-btn" onclick="document.getElementById('avatarInput').click()"
                                title="Change Avatar">
                                <i class="bi bi-camera-fill"></i>
                            </button>
                            <input type="file" id="avatarInput" class="d-none" accept="image/*"
                                onchange="uploadAvatar(this)">
                        </div>

                        <!-- User Info -->
                        <div class="ms-4">
                            <h2 class="mb-1 text-white fw-bold">{{ $user->name }}</h2>
                            <p class="mb-2 text-white-50">{{ $user->email }}</p>
                            <div class="d-flex gap-2">
                                @if ($user->roles->isNotEmpty())
                                    <span class="badge badge-custom">
                                        <i class="bi bi-shield-check me-1"></i>
                                        {{ ucfirst($user->roles->first()->name) }}
                                    </span>
                                @endif
                                <span class="badge badge-custom">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Member since {{ $user->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button class="btn btn-light" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i> Print Profile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <div class="profile-sidebar">
                    <!-- Stats Cards -->
                    <div class="stats-mini mb-4">
                        <div class="stat-item">
                            <div class="stat-icon bg-primary">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div class="stat-info">
                                <h4>{{ $stats['total_orders'] }}</h4>
                                <p>Total Orders</p>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon bg-success">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div class="stat-info">
                                <h4>Rp {{ number_format($stats['total_spent'] / 1000, 0) }}K</h4>
                                <p>Total Spent</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="nav-menu">
                        <a href="#overview" class="nav-menu-item active" data-bs-toggle="pill">
                            <i class="bi bi-grid-1x2"></i>
                            <span>Overview</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="#edit-profile" class="nav-menu-item" data-bs-toggle="pill">
                            <i class="bi bi-person-gear"></i>
                            <span>Edit Profile</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="#security" class="nav-menu-item" data-bs-toggle="pill">
                            <i class="bi bi-shield-lock"></i>
                            <span>Security</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <a href="#orders" class="nav-menu-item" data-bs-toggle="pill">
                            <i class="bi bi-box-seam"></i>
                            <span>My Orders</span>
                            <span class="badge bg-primary ms-auto">{{ $stats['pending_orders'] }}</span>
                        </a>
                        <a href="#notifications" class="nav-menu-item" data-bs-toggle="pill">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                        <hr class="my-3">
                        <a href="{{ route('logout') }}" class="nav-menu-item text-danger"
                            onclick="event.preventDefault(); confirmLogout(event)">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="tab-content">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview">
                        <!-- Stats Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="stat-card stat-primary">
                                    <div class="stat-card-icon">
                                        <i class="bi bi-bag-check"></i>
                                    </div>
                                    <div class="stat-card-content">
                                        <h3>{{ $stats['total_orders'] }}</h3>
                                        <p>Total Orders</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card stat-warning">
                                    <div class="stat-card-icon">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="stat-card-content">
                                        <h3>{{ $stats['pending_orders'] }}</h3>
                                        <p>Pending</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card stat-success">
                                    <div class="stat-card-icon">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="stat-card-content">
                                        <h3>{{ $stats['completed_orders'] }}</h3>
                                        <p>Completed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card stat-info">
                                    <div class="stat-card-icon">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                    <div class="stat-card-content">
                                        <h6>Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h6>
                                        <p>Total Spent</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5><i class="bi bi-person-vcard me-2"></i> Account Information</h5>
                            </div>
                            <div class="profile-card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label><i class="bi bi-person me-2 text-primary"></i> Full Name</label>
                                            <p>{{ $user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label><i class="bi bi-envelope me-2 text-primary"></i> Email Address</label>
                                            <p>{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label><i class="bi bi-phone me-2 text-primary"></i> Phone Number</label>
                                            <p>{{ $user->phone ?? 'Not set' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label><i class="bi bi-calendar3 me-2 text-primary"></i> Member Since</label>
                                            <p>{{ $user->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="info-item">
                                            <label><i class="bi bi-geo-alt me-2 text-primary"></i> Address</label>
                                            <p>{{ $user->address ?? 'Not set' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <div class="quick-action-card">
                                    <i class="bi bi-bag-plus"></i>
                                    <h6>Start Shopping</h6>
                                    <p>Browse our latest products</p>
                                    <a href="{{ route('shop.index') }}" class="btn btn-sm btn-primary">Shop Now</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="quick-action-card">
                                    <i class="bi bi-box-seam"></i>
                                    <h6>Track Orders</h6>
                                    <p>View your order history</p>
                                    <a href="#orders" class="btn btn-sm btn-primary" data-bs-toggle="pill">View
                                        Orders</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="quick-action-card">
                                    <i class="bi bi-headset"></i>
                                    <h6>Need Help?</h6>
                                    <p>Contact our support team</p>
                                    <a href="#" class="btn btn-sm btn-primary">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Tab -->
                    <div class="tab-pane fade" id="edit-profile">
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5><i class="bi bi-pencil-square me-2"></i> Edit Profile Information</h5>
                            </div>
                            <div class="profile-card-body">
                                <form id="updateProfileForm">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="name" class="form-control" id="name"
                                                    value="{{ $user->name }}" required>
                                                <label for="name"><i class="bi bi-person me-2"></i> Full Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="email" name="email" class="form-control" id="email"
                                                    value="{{ $user->email }}" required>
                                                <label for="email"><i class="bi bi-envelope me-2"></i> Email
                                                    Address</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="tel" name="phone" class="form-control" id="phone"
                                                    value="{{ $user->phone }}" placeholder="08123456789">
                                                <label for="phone"><i class="bi bi-phone me-2"></i> Phone
                                                    Number</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <textarea name="address" class="form-control" id="address" style="height: 100px"
                                                    placeholder="Enter your complete address">{{ $user->address }}</textarea>
                                                <label for="address"><i class="bi bi-geo-alt me-2"></i> Complete
                                                    Address</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                                <i class="bi bi-check-circle me-2"></i> Save Changes
                                            </button>
                                            <button type="reset" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                                                <i class="bi bi-x-circle me-2"></i> Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security">
                        <!-- Change Password -->
                        <div class="profile-card mb-4">
                            <div class="profile-card-header">
                                <h5><i class="bi bi-key me-2"></i> Change Password</h5>
                            </div>
                            <div class="profile-card-body">
                                <form id="changePasswordForm">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="password" name="current_password" class="form-control"
                                                    id="currentPassword" required>
                                                <label for="currentPassword"><i class="bi bi-lock me-2"></i> Current
                                                    Password</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="password" name="password" class="form-control"
                                                    id="newPassword" required>
                                                <label for="newPassword"><i class="bi bi-lock-fill me-2"></i> New
                                                    Password</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    id="confirmPassword" required>
                                                <label for="confirmPassword"><i class="bi bi-lock-fill me-2"></i> Confirm
                                                    Password</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>Password Requirements:</strong>
                                                <ul class="mb-0 mt-2">
                                                    <li>Minimum 8 characters</li>
                                                    <li>At least one uppercase letter</li>
                                                    <li>At least one number</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                                <i class="bi bi-shield-check me-2"></i> Update Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="profile-card border-danger">
                            <div class="profile-card-header bg-danger text-white">
                                <h5><i class="bi bi-exclamation-triangle me-2"></i> Danger Zone</h5>
                            </div>
                            <div class="profile-card-body">
                                <h6 class="text-danger fw-bold">Delete Account Permanently</h6>
                                <p class="text-muted">Once you delete your account, there is no going back.All your data,
                                    orders, and information will be permanently removed.</p>
                                <button class="btn btn-danger btn-lg" onclick="confirmDeleteAccount()">
                                    <i class="bi bi-trash me-2"></i> Delete My Account
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="orders">
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5><i class="bi bi-box-seam me-2"></i> Order History</h5>
                            </div>
                            <div class="profile-card-body">
                                @if ($stats['total_orders'] > 0)
                                    <div class="text-center py-4">
                                        <i class="bi bi-box-seam fs-1 text-primary mb-3"></i>
                                        <p class="text-muted">You have {{ $stats['total_orders'] }} orders</p>
                                        <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                                            View All Orders
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                                        <h5 class="text-muted">No orders yet</h5>
                                        <p class="text-muted">Start shopping to see your orders here</p>
                                        <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg mt-3">
                                            <i class="bi bi-shop me-2"></i> Start Shopping
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Tab -->
                    <div class="tab-pane fade" id="notifications">
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <h5><i class="bi bi-bell me-2"></i> Notification Settings</h5>
                            </div>
                            <div class="profile-card-body">
                                <div class="notification-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>Order Updates</h6>
                                            <p class="text-muted small mb-0">Get notified about order status changes</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>Promotional Emails</h6>
                                            <p class="text-muted small mb-0">Receive offers and promotions</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>New Products</h6>
                                            <p class="text-muted small mb-0">Get notified about new arrivals</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ========================================
        // UPLOAD AVATAR
        // ========================================
        async function uploadAvatar(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];

            // Validate file size (max 2MB)
            if (file.size > 2048000) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    html: '<p>Maximum file size is <strong>2MB</strong></p>',
                    confirmButtonColor: '#667eea',
                    confirmButtonText: 'OK'
                });
                input.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    html: '<p>Please select an image file<br><small>(JPG, PNG, GIF, WebP)</small></p>',
                    confirmButtonColor: '#667eea'
                });
                input.value = '';
                return;
            }

            // Show loading with progress
            let timerInterval;
            Swal.fire({
                title: 'Uploading Avatar...',
                html: '<div class="progress mt-3"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    const progressBar = Swal.getHtmlContainer().querySelector('.progress-bar');
                    let progress = 0;
                    timerInterval = setInterval(() => {
                        progress += 10;
                        if (progress <= 90) {
                            progressBar.style.width = progress + '%';
                        }
                    }, 100);
                }
            });

            // Prepare form data
            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route('profile.updateAvatar') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                clearInterval(timerInterval);
                const data = await response.json();

                if (response.ok && data.success) {
                    // Update avatar image
                    const avatarImg = document.getElementById('profileAvatar');
                    if (avatarImg) {
                        avatarImg.src = data.avatar_url + '?t=' + new Date().getTime();
                    }

                    // Update navbar avatar if exists
                    const navbarAvatar = document.querySelector('.navbar img[alt="Avatar"]');
                    if (navbarAvatar) {
                        navbarAvatar.src = data.avatar_url + '?t=' + new Date().getTime();
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Avatar Updated!',
                        html: '<p>Your profile picture has been updated successfully</p>',
                        confirmButtonColor: '#667eea',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            } catch (error) {
                clearInterval(timerInterval);
                console.error('Upload error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    html: `<p>${error.message || 'Something went wrong'}</p><small class="text-muted">Please try again</small>`,
                    confirmButtonColor: '#667eea'
                });
            } finally {
                input.value = '';
            }
        }

        // ========================================
        // UPDATE PROFILE
        // ========================================
        document.getElementById('updateProfileForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Updating...';

            // Show loading
            Swal.fire({
                title: 'Updating Profile...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            try {
                const response = await fetch('{{ route('profile.update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Profile Updated!',
                        html: '<p>Your profile information has been updated successfully</p>',
                        confirmButtonColor: '#667eea',
                        confirmButtonText: 'Great!'
                    });

                    // Reload page to show updated info
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            } catch (error) {
                console.error('Update error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    html: `<p>${error.message || 'Something went wrong'}</p>`,
                    confirmButtonColor: '#667eea'
                });

                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // ========================================
        // CHANGE PASSWORD
        // ========================================
        document.getElementById('changePasswordForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const password = formData.get('password');
            const confirmPassword = formData.get('password_confirmation');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Validate password match
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    html: '<p>New password and confirmation password do not match</p>',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            // Validate password strength
            if (password.length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Weak Password',
                    html: '<p>Password must be at least <strong>8 characters</strong></p>',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Updating...';

            // Show loading
            Swal.fire({
                title: 'Updating Password...',
                html: '<div class="spinner-border text-primary" role="status"></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            try {
                const response = await fetch('{{ route('profile.updatePassword') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Password Updated!',
                        html: '<p>Your password has been changed successfully</p><small class="text-muted">Please use your new password for future logins</small>',
                        confirmButtonColor: '#667eea',
                        confirmButtonText: 'OK'
                    });

                    // Reset form
                    this.reset();
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                } else {
                    throw new Error(data.message || 'Password update failed');
                }
            } catch (error) {
                console.error('Password update error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    html: `<p>${error.message || 'Current password is incorrect'}</p>`,
                    confirmButtonColor: '#667eea'
                });

                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // ========================================
        // CONFIRM DELETE ACCOUNT
        // ========================================
        async function confirmDeleteAccount() {
            // Step 1: Show warning
            const warningResult = await Swal.fire({
                title: '⚠️ Warning! ',
                html: `
                <div class="text-start">
                    <p class="text-danger fw-bold mb-3">This action is <u>permanent</u> and <u>irreversible</u>! </p>
                    <p>Deleting your account will:</p>
                    <ul class="text-muted">
                        <li>Remove all your personal information</li>
                        <li>Delete your order history</li>
                        <li>Cancel any pending orders</li>
                        <li>Remove you from our mailing list</li>
                    </ul>
                    <p class="text-danger fw-bold mt-3">Are you sure you want to continue?</p>
                </div>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });

            if (!warningResult.isConfirmed) return;

            // Step 2: Ask for password
            const {
                value: password
            } = await Swal.fire({
                title: 'Enter Your Password',
                html: `
                <p class="text-muted mb-3">Please enter your password to confirm account deletion</p>
                <input type="password" id="deletePassword" class="form-control form-control-lg" placeholder="Enter your password" autofocus>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Verify & Continue',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const password = document.getElementById('deletePassword').value;
                    if (!password) {
                        Swal.showValidationMessage('Password is required');
                        return false;
                    }
                    return password;
                }
            });

            if (!password) return;

            // Step 3: Final confirmation
            const finalConfirm = await Swal.fire({
                title: 'Last Chance!',
                html: `
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 64px;"></i>
                    <h5 class="mt-3 text-danger fw-bold">This is your final warning!</h5>
                    <p class="text-muted">Once deleted, your account cannot be recovered.</p>
                    <p class="fw-bold">Type <span class="badge bg-danger">DELETE</span> to confirm:</p>
                    <input type="text" id="confirmText" class="form-control form-control-lg text-center" placeholder="Type DELETE">
                </div>
            `,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Delete My Account Forever',
                cancelButtonText: 'No, Keep My Account',
                reverseButtons: true,
                preConfirm: () => {
                    const confirmText = document.getElementById('confirmText').value;
                    if (confirmText !== 'DELETE') {
                        Swal.showValidationMessage('Please type DELETE to confirm');
                        return false;
                    }
                    return true;
                }
            });

            if (finalConfirm.isConfirmed) {
                deleteAccount(password);
            }
        }

        // ========================================
        // DELETE ACCOUNT
        // ========================================
        async function deleteAccount(password) {
            // Show deleting animation
            Swal.fire({
                title: 'Deleting Account...',
                html: `
                <div class="text-center">
                    <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <p class="text-muted">Please wait while we process your request</p>
                </div>
            `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            try {
                const response = await fetch('{{ route('profile.delete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        password
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Account Deleted',
                        html: '<p>Your account has been permanently deleted</p><p class="text-muted small">We\'re sorry to see you go.Thank you for being with us.</p>',
                        confirmButtonColor: '#667eea',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    // Redirect to home
                    window.location.href = data.redirect || '{{ route('home') }}';
                } else {
                    throw new Error(data.message || 'Delete failed');
                }
            } catch (error) {
                console.error('Delete error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    html: `<p>${error.message || 'Incorrect password or server error'}</p>`,
                    confirmButtonColor: '#667eea'
                });
            }
        }

        // ========================================
        // LOGOUT FUNCTION
        // ========================================
        function confirmLogout(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Logout Confirmation',
                html: '<p>Are you sure you want to logout?</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-box-arrow-right me-2"></i> Yes, Logout',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i> Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Logging out...',
                        html: '<div class="spinner-border text-primary" role="status"></div>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });

                    // Submit logout form
                    setTimeout(() => {
                        document.getElementById('logout-form').submit();
                    }, 500);
                }
            });
        }

        // ========================================
        // SESSION MESSAGES
        // ========================================
        // Show success message if redirected with success
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#667eea',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        @endif

        // Show error message if any
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#667eea'
            });
        @endif

        // ========================================
        // UTILITY FUNCTIONS
        // ========================================

        // Auto-save form on input (debounced)
        let saveTimeout;
        document.querySelectorAll('#updateProfileForm input, #updateProfileForm textarea').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    // Show auto-save indicator
                    const indicator = document.createElement('small');
                    indicator.className = 'text-muted ms-2';
                    indicator.innerHTML =
                        '<i class="bi bi-check-circle text-success"></i> Auto-saved';
                    this.parentElement.appendChild(indicator);
                    setTimeout(() => indicator.remove(), 2000);
                }, 2000);
            });
        });

        // Password strength indicator
        const passwordInput = document.getElementById('newPassword');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const strength = checkPasswordStrength(this.value);
                const indicator = document.createElement('div');
                indicator.className = 'progress mt-2';
                indicator.style.height = '5px';

                let color = 'danger';
                let width = '33%';
                let text = 'Weak';

                if (strength > 60) {
                    color = 'success';
                    width = '100%';
                    text = 'Strong';
                } else if (strength > 30) {
                    color = 'warning';
                    width = '66%';
                    text = 'Medium';
                }

                indicator.innerHTML = `<div class="progress-bar bg-${color}" style="width: ${width}"></div>`;

                // Remove old indicator
                const oldIndicator = this.parentElement.querySelector('.progress');
                if (oldIndicator) oldIndicator.remove();

                // Add new indicator
                this.parentElement.appendChild(indicator);

                // Add text
                const textElem = document.createElement('small');
                textElem.className = `text-${color} mt-1`;
                textElem.textContent = `Password strength: ${text}`;
                this.parentElement.appendChild(textElem);
            });
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 25;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            if (/\d/.test(password)) strength += 15;
            if (/[^a-zA-Z\d]/.test(password)) strength += 10;
            return strength;
        }

        // Smooth scroll to active tab
        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            });
        });

        // Add loading state to all buttons
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.form.checkValidity()) return;

                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = 'bi bi-hourglass-split me-2';
                }
            });
        });

        console.log('✅ Profile page loaded successfully!');
    </script>
@endpush
