<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    @include('includes_admin.head')
    @yield('styles')
    <style>
        :root {
            --randevou-primary: #3557a8;
            --randevou-primary-dark: #274487;
            --randevou-primary-soft: #eef3ff;
            --randevou-accent: #ed1c2e;
            --randevou-accent-soft: #fff0f2;
            --randevou-border: #e8edf7;
            --randevou-shadow: rgba(53, 87, 168, 0.12);
        }
        body.app-type-2 .sidebar,
        body.app-type-1 .sidebar,
        body.app-type-7 .sidebar {
            background: linear-gradient(180deg, #dce6f5 0%, #c8d6eb 40%, #b8c9e0 100%) !important;
            border-right: 1px solid rgba(53, 87, 168, 0.12) !important;
            box-shadow: 2px 0 20px rgba(53, 87, 168, 0.1) !important;
        }
        body.app-type-2 .sidebar .sidebar-menu li a,
        body.app-type-1 .sidebar .sidebar-menu li a,
        body.app-type-7 .sidebar .sidebar-menu li a {
            color: #2d3748 !important;
        }
        body.app-type-2 .sidebar .sidebar-menu li a:hover,
        body.app-type-1 .sidebar .sidebar-menu li a:hover,
        body.app-type-7 .sidebar .sidebar-menu li a:hover {
            color: #2E37A4 !important;
            background: rgba(46, 55, 164, 0.08) !important;
        }
        body.app-type-2 .sidebar .sidebar-menu > ul > li > a.is-current,
        body.app-type-2 .sidebar .sidebar-menu > ul > li > a.active,
        body.app-type-1 .sidebar .sidebar-menu > ul > li > a.is-current,
        body.app-type-1 .sidebar .sidebar-menu > ul > li > a.active,
        body.app-type-7 .sidebar .sidebar-menu > ul > li > a.is-current,
        body.app-type-7 .sidebar .sidebar-menu > ul > li > a.active {
            background: rgba(46, 55, 164, 0.12) !important;
            color: #2E37A4 !important;
        }
        body.app-type-2 .sidebar .sidebar-menu > ul > li > a.is-current .clinic-admin-menu-icon,
        body.app-type-2 .sidebar .sidebar-menu > ul > li > a.active .clinic-admin-menu-icon,
        body.app-type-1 .sidebar .sidebar-menu > ul > li > a.is-current .clinic-admin-menu-icon,
        body.app-type-1 .sidebar .sidebar-menu > ul > li > a.active .clinic-admin-menu-icon,
        body.app-type-7 .sidebar .sidebar-menu > ul > li > a.is-current .clinic-admin-menu-icon,
        body.app-type-7 .sidebar .sidebar-menu > ul > li > a.active .clinic-admin-menu-icon {
            background: linear-gradient(135deg, #2E37A4, #3557a8) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(46, 55, 164, 0.25) !important;
        }
        body.app-type-2 .sidebar .sidebar-menu > ul > li > a:hover .clinic-admin-menu-icon,
        body.app-type-1 .sidebar .sidebar-menu > ul > li > a:hover .clinic-admin-menu-icon,
        body.app-type-7 .sidebar .sidebar-menu > ul > li > a:hover .clinic-admin-menu-icon {
            background: linear-gradient(135deg, #2E37A4, #3557a8) !important;
            color: #fff !important;
        }
        body.app-type-2 .sidebar .sidebar-menu .clinic-admin-menu-icon,
        body.app-type-1 .sidebar .sidebar-menu .clinic-admin-menu-icon,
        body.app-type-7 .sidebar .sidebar-menu .clinic-admin-menu-icon {
            background: rgba(46, 55, 164, 0.1) !important;
            color: #2E37A4 !important;
            box-shadow: none !important;
        }
        body.app-type-2 .sidebar .sidebar-menu > ul > li > a.is-current::before,
        body.app-type-2 .sidebar .sidebar-menu > ul > li > a.active::before,
        body.app-type-1 .sidebar .sidebar-menu > ul > li > a.is-current::before,
        body.app-type-1 .sidebar .sidebar-menu > ul > li > a.active::before,
        body.app-type-7 .sidebar .sidebar-menu > ul > li > a.is-current::before,
        body.app-type-7 .sidebar .sidebar-menu > ul > li > a.active::before {
            background: linear-gradient(180deg, #2E37A4, #3557a8) !important;
            width: 4px !important;
        }
        body.app-type-2 .logout-btn a,
        body.app-type-1 .logout-btn a,
        body.app-type-7 .logout-btn a {
            color: #2d3748 !important;
        }
        body.app-type-2 .logout-btn a:hover,
        body.app-type-1 .logout-btn a:hover,
        body.app-type-7 .logout-btn a:hover {
            color: #2E37A4 !important;
        }
        body.app-type-2 .logout-btn a span.menu-side,
        body.app-type-1 .logout-btn a span.menu-side,
        body.app-type-7 .logout-btn a span.menu-side {
            background: rgba(46, 55, 164, 0.1) !important;
        }
        body.app-type-2 .sidebar .menu-arrow:before,
        body.app-type-1 .sidebar .menu-arrow:before,
        body.app-type-7 .sidebar .menu-arrow:before {
            color: rgba(46, 55, 164, 0.5) !important;
        }
        body.app-type-2 .header .logo,
        body.app-type-1 .header .logo,
        body.app-type-7 .header .logo {
            background: linear-gradient(135deg, #dce6f5, #c8d6eb) !important;
            padding: 8px 16px !important;
            border-radius: 12px !important;
            border: 1px solid rgba(53, 87, 168, 0.12) !important;
            box-shadow: 0 2px 8px rgba(53, 87, 168, 0.08) !important;
        }
        body.app-type-2 .header .logo span,
        body.app-type-1 .header .logo span,
        body.app-type-7 .header .logo span {
            background: linear-gradient(135deg, #1a2a6c, #2E37A4) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            font-weight: 800 !important;
        }

        .main-wrapper > .page-body {
            min-height: 100vh;
            padding: 100px 30px 30px;
            background:
                radial-gradient(ellipse at 0% 0%, rgba(53, 87, 168, 0.04) 0%, transparent 60%),
                radial-gradient(ellipse at 100% 100%, rgba(46, 55, 164, 0.03) 0%, transparent 50%),
                linear-gradient(180deg, #f7f9ff 0%, #f4f7fb 100%);
            width: calc(100% - 250px);
            max-width: none;
            box-sizing: border-box;
            overflow-x: hidden;
            animation: adminContentEnter 180ms ease-out both;
            will-change: opacity, transform;
            position: relative;
        }
        .main-wrapper > .page-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2E37A4, #3557a8, #5a7fc0, #3557a8, #2E37A4);
            opacity: 0.6;
        }

        .main-wrapper > .page-body,
        .main-wrapper > .page-body * {
            letter-spacing: 0;
        }

        html {
            scrollbar-gutter: stable;
            overflow-x: hidden;
        }

        body {
            overflow-x: hidden;
            overflow-y: auto;
        }

        .main-wrapper > .page-body,
        .main-wrapper > .page-body .card,
        .main-wrapper > .page-body .table,
        .main-wrapper > .page-body .btn,
        .main-wrapper > .page-body .form-control {
            transition: none !important;
        }

        .page-wrapper,
        .sidebar,
        .main-wrapper,
        .header,
        .sidebar-overlay {
            transition: none !important;
            transform: none;
        }

        .sidebar,
        .header {
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .header.no-print {
            height: 78px;
            padding: 0 28px !important;
            background: #fff;
            border-bottom: 1px solid var(--randevou-border);
            box-shadow: 0 4px 18px rgba(53, 87, 168, 0.06);
            gap: 20px;
        }

        .header .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 180px;
            color: var(--randevou-primary);
            font-size: 21px;
            font-weight: 700;
            white-space: nowrap;
        }

        .header .logo span {
            display: block;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header .logo img {
            width: 54px !important;
            height: 42px !important;
            object-fit: contain;
            border-radius: 8px;
        }

        .header #toggle_btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--randevou-primary-soft);
            margin: 0 auto;
            flex: 0 0 auto;
        }

        .header #toggle_btn img {
            width: 20px;
            height: 20px;
        }

        .header .top-nav-search {
            flex: 0 1 360px;
            max-width: 360px;
            margin: 0 !important;
        }

        .header .top-nav-search form {
            width: 100%;
            margin: 0;
        }

        .header .top-nav-search .form-control {
            height: 46px;
            border-radius: 14px;
            background: #f7f9ff;
            border: 1px solid var(--randevou-border);
            padding-inline: 46px 18px;
        }

        .header .top-nav-search .btn {
            top: 0;
            height: 46px;
            min-height: 46px;
            width: 46px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .header .user-menu {
            align-items: center;
            gap: 12px;
            margin: 0;
            flex: 0 0 auto;
        }

        .header .user-profile-list .user-link {
            background: #fff;
            border: 1px solid var(--randevou-border);
            border-radius: 14px;
            padding: 7px 10px !important;
            min-height: 54px;
            max-width: 190px;
            min-width: 150px;
        }

        .header .user-names {
            padding: 0;
            text-align: end;
            min-width: 0;
            overflow: hidden;
        }

        .header .user-names h5 {
            font-size: 15px;
            margin-bottom: 2px;
            color: var(--randevou-primary);
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header .user-names span {
            color: #7d89a6;
            font-size: 12px;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header .user-img img,
        .header .switchLang,
        .header .mobile-language-switch img {
            border-radius: 12px;
            object-fit: cover;
        }

        .header .desktop-language-switch .nav-link,
        .header .mobile-language-switch .nav-link {
            width: auto;
            border-radius: 999px;
            background: var(--randevou-primary-soft);
            color: var(--randevou-primary);
            border: 1px solid rgba(53, 87, 168, 0.14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
            box-shadow: 0 8px 18px rgba(53, 87, 168, 0.08);
        }

        .header .desktop-language-switch .nav-link {
            min-width: 86px;
            height: 42px;
            padding: 6px 12px !important;
            font-size: 13px;
        }

        .header .desktop-language-switch .nav-link:hover,
        .header .mobile-language-switch .nav-link:hover {
            background: var(--randevou-primary);
            border-color: var(--randevou-primary);
            color: #fff;
        }

        .header .desktop-language-switch img,
        .header .mobile-language-switch img {
            width: 24px !important;
            height: 24px !important;
            border-radius: 50% !important;
        }

        .header .desktop-language-switch i,
        .header .mobile-language-switch i {
            font-size: 14px;
        }

        .header .mobile-language-switch {
            align-items: center;
            flex: 0 0 auto;
        }

        .header .mobile-language-switch .nav-link {
            min-width: 58px;
            height: 42px;
            padding: 5px 9px !important;
            gap: 6px;
            font-size: 12px;
        }

        @media (max-width: 1199.98px) {
            .header .top-nav-search {
                display: none;
            }
        }

        @keyframes adminContentEnter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .main-wrapper > .page-body {
                animation: none;
            }
        }

        .main-wrapper > .page-body.is-leaving {
            opacity: 0.65;
            transition: opacity 120ms ease-out !important;
        }

        .sidebar a.loading-link {
            pointer-events: none;
        }

        .sidebar .sidebar-menu .clinic-admin-menu-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 10px;
            background: var(--randevou-primary-soft);
            color: var(--randevou-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-inline-end: 12px;
            box-shadow: inset 0 0 0 1px rgba(53, 87, 168, 0.10);
        }

        .sidebar .sidebar-menu .clinic-admin-menu-icon i {
            font-size: 18px;
            line-height: 1;
        }

        .sidebar .sidebar-menu > ul > li > a:hover .clinic-admin-menu-icon,
        .sidebar .sidebar-menu > ul > li > a.active .clinic-admin-menu-icon {
            background: var(--randevou-primary);
            color: #fff;
        }

        /* ===== Sidebar active-tab highlight ===== */
        .sidebar .sidebar-menu > ul > li > a {
            position: relative;
            transition: background-color .18s ease, color .18s ease;
            border-radius: 10px;
        }

        .sidebar .sidebar-menu > ul > li > a:hover {
            background: rgba(53, 87, 168, 0.07);
        }

        .sidebar .sidebar-menu > ul > li > a.is-current,
        .sidebar .sidebar-menu > ul > li > a.active {
            background: linear-gradient(90deg, rgba(53, 87, 168, 0.14) 0%, rgba(53, 87, 168, 0.04) 100%) !important;
            color: var(--randevou-primary) !important;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(53, 87, 168, 0.10);
        }

        .sidebar .sidebar-menu > ul > li > a.is-current span:last-child,
        .sidebar .sidebar-menu > ul > li > a.active span:last-child {
            color: var(--randevou-primary) !important;
            font-weight: 700;
        }

        .sidebar .sidebar-menu > ul > li > a.is-current::before,
        .sidebar .sidebar-menu > ul > li > a.active::before {
            content: "";
            position: absolute;
            top: 8px;
            bottom: 8px;
            width: 4px;
            border-radius: 4px;
            background: linear-gradient(180deg, #2E37A4 0%, #0E9384 100%);
        }
        [dir="rtl"] .sidebar .sidebar-menu > ul > li > a.is-current::before,
        [dir="rtl"] .sidebar .sidebar-menu > ul > li > a.active::before {
            right: 0;
        }
        [dir="ltr"] .sidebar .sidebar-menu > ul > li > a.is-current::before,
        [dir="ltr"] .sidebar .sidebar-menu > ul > li > a.active::before {
            left: 0;
        }

        .sidebar .sidebar-menu > ul > li > a.is-current .clinic-admin-menu-icon {
            background: var(--randevou-primary);
            color: #fff;
        }

        .sidebar .sidebar-menu > ul > li > a.is-clicking {
            background: rgba(53, 87, 168, 0.18) !important;
            transform: scale(0.985);
            transition: background-color .12s, transform .12s;
        }

        [dir="ltr"] .main-wrapper > .page-body {
            margin-left: 250px;
        }

        [dir="rtl"] .main-wrapper > .page-body {
            margin-right: 250px;
        }

        .main-wrapper > .page-body > .container-fluid {
            padding-left: 0;
            padding-right: 0;
            max-width: 100%;
        }

        .page-body .page-header {
            margin-bottom: 24px;
        }

        .page-body .page-header-left {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        [dir="rtl"] .page-body .page-header-left {
            align-items: flex-end;
            text-align: right;
        }

        .page-body .page-header {
            position: relative;
        }
        .page-body .page-header h3,
        .page-body .page-header h4 {
            color: #1a2a6c;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            display: inline-block;
        }
        .page-body .page-header h3::after,
        .page-body .page-header h4::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #2E37A4, #3557a8);
            border-radius: 2px;
        }
        [dir="rtl"] .page-body .page-header h3::after,
        [dir="rtl"] .page-body .page-header h4::after {
            left: auto;
            right: 0;
        }

        .page-body .breadcrumb {
            background: transparent;
            margin-bottom: 0;
            padding: 0;
            column-gap: 14px;
            row-gap: 6px;
            justify-content: flex-start;
        }

        [dir="rtl"] .page-body .breadcrumb {
            justify-content: flex-end;
        }

        .page-body .breadcrumb .breadcrumb-item,
        .page-body .breadcrumb .breadcrumb-item a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            line-height: 1.4;
            white-space: nowrap;
        }

        .page-body .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-inline-start: 10px;
            margin-inline-start: 4px;
        }

        .page-body .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            color: #a7b0c4;
            padding-inline-end: 10px;
            line-height: 1;
        }

        [dir="rtl"] .page-body .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-inline-start: 0;
            padding-inline-end: 10px;
            margin-inline-start: 0;
            margin-inline-end: 4px;
        }

        [dir="rtl"] .page-body .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            float: none;
            padding-inline-end: 0;
            padding-inline-start: 10px;
        }

        .page-body .breadcrumb i[data-feather="home"],
        .page-body .breadcrumb svg {
            width: 15px;
            height: 15px;
            flex: 0 0 15px;
        }

        .page-body .card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(53, 87, 168, 0.06), 0 8px 32px rgba(53, 87, 168, 0.04);
            margin-bottom: 24px;
            overflow: visible;
            animation: adminCardEnter 220ms ease-out both;
            transition: box-shadow 0.2s ease;
        }
        .page-body .card:hover {
            box-shadow: 0 4px 20px rgba(53, 87, 168, 0.1), 0 12px 40px rgba(53, 87, 168, 0.06);
        }
        .page-body .card .card-header {
            border-radius: 12px 12px 0 0;
        }

        .page-body .card:nth-of-type(2) {
            animation-delay: 30ms;
        }

        .page-body .card:nth-of-type(3) {
            animation-delay: 60ms;
        }

        .page-body .dash-widget {
            border: 1px solid rgba(53, 87, 168, 0.08);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(53, 87, 168, 0.04), 0 8px 24px rgba(53, 87, 168, 0.06);
            min-height: 120px;
            padding: 20px 22px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .page-body .dash-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2E37A4, #3557a8, #5a7fc0);
            opacity: 0.5;
        }
        .page-body .dash-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(53, 87, 168, 0.1), 0 12px 32px rgba(53, 87, 168, 0.08);
        }

        .page-body .dash-widget .dash-boxs {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--randevou-primary-soft);
            color: var(--randevou-primary);
            box-shadow: inset 0 0 0 1px rgba(53, 87, 168, 0.10);
        }

        .page-body .dash-widget:nth-child(4n + 2) .dash-boxs,
        .page-body .row > [class*="col-"]:nth-child(4n + 2) .dash-widget .dash-boxs {
            background: var(--randevou-accent-soft);
            color: var(--randevou-accent);
            box-shadow: inset 0 0 0 1px rgba(237, 28, 46, 0.10);
        }

        .page-body .dash-widget .dash-boxs i {
            font-size: 22px;
        }

        .page-body .dash-widget .dash-content h4,
        .page-body .dash-widget .dash-content h4 a {
            color: #5f6b84;
            font-weight: 600;
        }

        .page-body .dash-widget .dash-content h2 {
            color: var(--randevou-primary-dark);
            font-weight: 800;
        }

        @keyframes adminCardEnter {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-body .card-header {
            background: #fff;
            border-bottom: 1px solid var(--randevou-border);
            padding: 18px 24px;
        }

        .page-body .card-body {
            padding: 24px;
        }

        .page-body .table-responsive {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
        }

        .page-body table {
            width: 100% !important;
            margin-bottom: 0 !important;
            border-collapse: separate;
            border-spacing: 0;
        }

        .page-body table thead th {
            background: #f8fbff;
            color: var(--randevou-primary-dark);
            font-size: 15px;
            font-weight: 700;
            border-bottom: 1px solid var(--randevou-border) !important;
            padding: 14px 16px;
            white-space: nowrap;
        }

        .page-body table tbody td {
            color: #111827;
            font-size: 14px;
            padding: 13px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f7;
        }

        .page-body table tbody tr:last-child td {
            border-bottom: 0;
        }

        .page-body .dataTables_wrapper {
            width: 100%;
        }

        .page-body .select2-container {
            width: 100% !important;
        }

        .page-body .select2-container--default .select2-selection--multiple {
            min-height: 44px;
            border-color: var(--randevou-border);
            border-radius: 8px;
            padding: 4px 8px;
        }

        .page-body .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--randevou-primary-soft);
            border-color: rgba(53, 87, 168, 0.18);
            color: var(--randevou-primary-dark);
            border-radius: 6px;
            padding: 3px 8px;
            line-height: 1.5;
        }

        .page-body .datatable-top,
        .page-body .datatable-bottom {
            gap: 12px;
        }

        .page-body .dataTables_length select,
        .page-body .dataTables_filter input {
            height: 44px;
            border: 1px solid var(--randevou-border);
            border-radius: 8px;
            box-shadow: none;
        }

        .page-body .dataTables_filter {
            text-align: end;
        }

        [dir="rtl"] .page-body .datatable-top .dataTables_length,
        [dir="rtl"] .page-body .datatable-bottom .dataTables_info {
            text-align: right;
        }

        [dir="rtl"] .page-body .datatable-top .dataTables_filter,
        [dir="rtl"] .page-body .datatable-bottom .dataTables_paginate {
            text-align: left;
            justify-content: flex-start;
        }

        [dir="ltr"] .page-body .datatable-bottom .dataTables_paginate {
            justify-content: flex-end;
        }

        .page-body .dataTables_filter input {
            min-width: 220px;
            margin: 0;
        }

        .page-body .dataTables_info {
            color: #344054;
            padding-top: 8px;
        }

        .page-body .dataTables_paginate {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 4px;
        }

        .page-body .dataTables_paginate .paginate_button,
        .page-body .pagination .page-link {
            border: 1px solid #dde2eb !important;
            border-radius: 6px !important;
            color: var(--randevou-primary) !important;
            padding: 8px 13px !important;
            margin: 0 2px !important;
            background: #fff !important;
        }

        .page-body .dataTables_paginate .paginate_button.current,
        .page-body .pagination .page-item.active .page-link {
            background: var(--randevou-primary) !important;
            border-color: var(--randevou-primary) !important;
            color: #fff !important;
        }

        .page-body .btn {
            border-radius: 6px;
            min-height: 38px;
        }

        .page-body .btn-primary {
            background: var(--randevou-primary);
            border-color: var(--randevou-primary);
            box-shadow: 0 8px 18px rgba(53, 87, 168, 0.18);
        }

        .page-body .btn-primary:hover,
        .page-body .btn-primary:focus {
            background: var(--randevou-primary-dark);
            border-color: var(--randevou-primary-dark);
        }

        .page-body .btn-danger {
            background: var(--randevou-accent);
            border-color: var(--randevou-accent);
        }

        .page-body .modal-content {
            border: 0;
            border-radius: 8px;
        }

        .modal {
            z-index: 2055 !important;
        }

        .modal-backdrop {
            z-index: 2050 !important;
        }

        .modal-dialog {
            pointer-events: auto;
        }

        .page-body .modal-header,
        .page-body .modal-footer {
            border-color: var(--randevou-border);
        }

        .page-body .icon-state {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 58px;
        }

        .page-body label.switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
            margin: 0;
            vertical-align: middle;
        }

        .page-body label.switch input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .page-body label.switch .switch-state {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: #d7dce8 !important;
            border-radius: 999px;
            transition: background-color 140ms ease-out !important;
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.06);
        }

        .page-body label.switch .switch-state:before {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 3px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
            transition: transform 140ms ease-out !important;
        }

        [dir="ltr"] .page-body label.switch .switch-state:before {
            left: 3px;
        }

        [dir="rtl"] .page-body label.switch .switch-state:before {
            right: 3px;
        }

        .page-body label.switch input:checked + .switch-state {
            background: var(--randevou-primary) !important;
        }

        [dir="ltr"] .page-body label.switch input:checked + .switch-state:before {
            transform: translateX(22px);
        }

        [dir="rtl"] .page-body label.switch input:checked + .switch-state:before {
            transform: translateX(-22px);
        }

        .page-body label.switch input:focus + .switch-state {
            outline: 3px solid rgba(53, 87, 168, 0.16);
            outline-offset: 2px;
        }

        @media (max-width: 991.98px) {
            .header.no-print {
                height: 72px;
                padding: 0 14px !important;
                gap: 8px;
            }

            .header .logo {
                min-width: 0;
                max-width: 170px;
                gap: 8px;
                font-size: 18px;
                overflow: hidden;
            }

            .header .logo img {
                width: 48px !important;
                height: 38px !important;
                flex: 0 0 auto;
            }

            .header .logo span {
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .header #toggle_btn,
            .header #mobile_btn,
            .header .mobile-user-menu > a {
                width: 42px;
                height: 42px;
                min-width: 42px;
                border-radius: 11px;
                background: var(--randevou-primary-soft);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: var(--randevou-primary);
                margin: 0;
            }

            .header #toggle_btn img,
            .header #mobile_btn img {
                width: 20px;
                height: 20px;
            }

            .header #toggle_btn {
                display: none !important;
            }

            .header .user-menu {
                display: none !important;
            }

            .mobile-user-menu {
                display: inline-flex !important;
                align-items: center;
                flex: 0 0 auto;
            }

            .mobile-user-menu .dropdown-toggle:after {
                display: none;
            }

            .sidebar {
                width: min(82vw, 320px) !important;
                max-width: 320px;
            }

            .sidebar .sidebar-menu > ul > li > a {
                min-height: 62px;
                padding: 10px 18px;
                gap: 12px;
            }

            .sidebar .sidebar-menu > ul > li > a span:last-child {
                white-space: normal;
                overflow-wrap: anywhere;
                line-height: 1.35;
            }

            .sidebar .sidebar-menu .clinic-admin-menu-icon {
                width: 40px;
                height: 40px;
                min-width: 40px;
                margin-inline-end: 10px;
            }

            [dir="ltr"] .main-wrapper > .page-body,
            [dir="rtl"] .main-wrapper > .page-body {
                margin-left: 0;
                margin-right: 0;
            }

            .main-wrapper > .page-body {
                width: 100%;
                padding: 88px 12px 20px;
                max-width: 100%;
            }

            /* ===== Mobile sidebar: hide off-screen properly in RTL & LTR ===== */
            .sidebar {
                top: 78px !important;
                border-radius: 0 !important;
                box-shadow: 0 18px 38px rgba(15, 23, 42, 0.18) !important;
                height: calc(100vh - 78px) !important;
                overflow-y: auto !important;
                z-index: 1045 !important;
                transition: transform .28s ease, margin .28s ease !important;
            }
            [dir="rtl"] .sidebar {
                right: 0 !important;
                left: auto !important;
                margin-right: -100% !important;
                margin-left: 0 !important;
                transform: translateX(0) !important;
            }
            [dir="ltr"] .sidebar {
                left: 0 !important;
                right: auto !important;
                margin-left: -100% !important;
                margin-right: 0 !important;
                transform: translateX(0) !important;
            }
            [dir="rtl"] .slide-nav .sidebar,
            [dir="rtl"] html.menu-opened .sidebar,
            [dir="rtl"] body.menu-opened .sidebar {
                margin-right: 0 !important;
            }
            [dir="ltr"] .slide-nav .sidebar,
            [dir="ltr"] html.menu-opened .sidebar,
            [dir="ltr"] body.menu-opened .sidebar {
                margin-left: 0 !important;
            }

            /* Dim overlay when sidebar is open on mobile */
            .sidebar-overlay {
                position: fixed !important;
                inset: 0 !important;
                background: rgba(15, 23, 42, 0.5) !important;
                z-index: 1044 !important;
                display: none !important;
            }
            .slide-nav .sidebar-overlay,
            html.menu-opened .sidebar-overlay,
            body.menu-opened .sidebar-overlay {
                display: block !important;
            }

            /* Avoid body scroll while drawer is open */
            html.menu-opened,
            html.menu-opened body,
            body.menu-opened {
                overflow: hidden !important;
            }

            /* ===== Mobile: breadcrumb separator & wrapping ===== */
            .page-body .breadcrumb {
                flex-wrap: wrap;
                row-gap: 4px;
                column-gap: 4px;
                font-size: 13px;
                align-items: center;
            }
            .page-body .breadcrumb .breadcrumb-item + .breadcrumb-item {
                padding-inline-start: 8px;
            }
            .page-body .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
                content: ">";
                color: #b6bdcc;
                font-size: 16px;
                line-height: 1;
                padding-inline-end: 8px;
            }
            [dir="rtl"] .page-body .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
                content: "<";
            }
            .page-body .breadcrumb i[data-feather="home"] {
                width: 14px;
                height: 14px;
                vertical-align: -2px;
            }

            /* ===== Mobile: logo can show more text ===== */
            .header .logo {
                max-width: none;
            }
            .header .logo span {
                white-space: nowrap;
            }

            /* ===== Mobile: keep mobile toggle visible ===== */
            #mobile_btn {
                display: inline-flex !important;
            }
        }

        @media (max-width: 420px) {
            .header .logo {
                max-width: none;
                font-size: 15px;
            }

            .header .logo span {
                display: none;
            }

            .header .logo img {
                width: 38px !important;
                height: 32px !important;
            }

            .header #toggle_btn,
            .header #mobile_btn,
            .header .mobile-user-menu > a {
                width: 38px;
                height: 38px;
                min-width: 38px;
            }

            .header .mobile-language-switch .nav-link {
                min-width: 52px;
                height: 38px;
                padding: 5px 7px !important;
            }
        }
    </style>
</head>
<body dir="{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}" lang="{{app()->getLocale() == 'ar' ? 'ar' : 'en'}}" class="app-type-{{ auth()->user()->app_type ?? '0' }}">
<div class="main-wrapper">
    @include('includes_admin.header')
    @include('includes_admin.nav')
    @yield('content')
    @include('includes_admin.footer-scripts')
    @include('includes_admin.realtime-notification-sound')
    @if(auth()->user()->app_type == 5)
        @include('includes_admin.modals.edit')
        @yield('includes')
    @endif

    <div class="sidebar-overlay no-print" data-reff=""></div>
</div>

@yield('scripts')
<script>
    $(function () {
        $('.sidebar a[href]:not([href="#"]):not([target])').on('click', function () {
            var currentPath = window.location.pathname + window.location.search;
            var nextUrl = new URL(this.href, window.location.origin);
            var nextPath = nextUrl.pathname + nextUrl.search;

            if (nextPath === currentPath) {
                return;
            }

            $('.main-wrapper > .page-body').addClass('is-leaving');
            $(this).addClass('loading-link');
        });
    });

    // ===== Auto-detect & highlight the sidebar tab of the current page =====
    (function () {
        function normalize(url) {
            try {
                var u = new URL(url, window.location.origin);
                return (u.pathname.replace(/\/+$/, '') || '/').toLowerCase();
            } catch (e) { return url; }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var current = normalize(window.location.href);
            var links = document.querySelectorAll('.sidebar a[href]');
            var bestMatch = null;
            var bestLen = -1;

            links.forEach(function (a) {
                var href = a.getAttribute('href');
                if (!href || href === '#' || href.indexOf('javascript') === 0) return;
                var path = normalize(href);
                if (!path || path === '/') return;

                if (current === path || current.indexOf(path + '/') === 0) {
                    if (path.length > bestLen) {
                        bestLen = path.length;
                        bestMatch = a;
                    }
                }
            });

            // Drop any hardcoded "active" so only the real current tab wins
            links.forEach(function (a) { a.classList.remove('active'); });

            if (bestMatch) {
                bestMatch.classList.add('is-current');
                if (typeof bestMatch.scrollIntoView === 'function') {
                    bestMatch.scrollIntoView({ block: 'nearest' });
                }
            }

            // Pressed feedback on click
            links.forEach(function (a) {
                a.addEventListener('click', function () {
                    var href = a.getAttribute('href');
                    if (!href || href === '#' || href.indexOf('javascript') === 0) return;
                    a.classList.add('is-clicking');
                });
            });
        });
    })();
</script>
@if(auth()->user()->app_type == 5)
    <script>
        $('body').on('click', '.update-modal', function (event) {
            event.preventDefault();
            var url, targetModal;
            url = $(this).attr('href');
            targetModal = $('#update-modal');

            // Get contents
            $.ajax({
                method: 'GET',
                url: url,
                success: function (data) {
                    targetModal.find('#updateModalLabel').text(data.title);
                    targetModal.find('.modal-body').html(data.view);

                    // Initialize datetimepicker after loading the content
                    $('.datetimepicker').datetimepicker({
                        format: 'MM-DD-YYYY'
                    });

                },
                error: function () {

                }
            });

            // Show modal
            targetModal.modal();
        });
    </script>
@endif
</body>
</html>
