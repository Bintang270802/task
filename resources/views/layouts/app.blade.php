<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Management System</title>
    
    <!-- Google Fonts - Classic Serif -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700;900&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #c0392b;
            --light-bg: #ecf0f1;
            --border-color: #bdc3c7;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Open Sans', sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Navbar - Classic Style */
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
            border-bottom: 3px solid var(--accent-color);
        }

        .navbar-brand {
            font-family: 'Merriweather', serif;
            font-weight: 900;
            font-size: 1.5rem;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .navbar-brand i {
            color: var(--accent-color);
        }

        .nav-link-custom {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1.25rem;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-block;
            border: 2px solid transparent;
        }

        .nav-link-custom:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-link-custom.active {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Container */
        .container-main {
            max-width: 1000px;
            margin-top: 2rem;
            margin-bottom: 3rem;
        }

        /* Alert Styles */
        .alert {
            border-radius: 4px;
            border-left: 4px solid;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            border-left-color: var(--success-color);
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-left-color: var(--danger-color);
            color: #721c24;
        }

        /* Card Styles */
        .card {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            background: white;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem 1.5rem;
            font-family: 'Merriweather', serif;
            font-weight: 700;
            color: var(--primary-color);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Page Header */
        .page-header {
            background: white;
            border: 1px solid #dee2e6;
            border-left: 4px solid var(--accent-color);
            border-radius: 4px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .page-title {
            font-family: 'Merriweather', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: var(--accent-color);
        }

        /* Task Card */
        .task-card {
            background: white;
            border: 1px solid #dee2e6;
            border-left: 4px solid var(--accent-color);
            border-radius: 4px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
            cursor: move;
        }

        .task-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left-color: var(--primary-color);
        }

        .task-card.dragging {
            opacity: 0.5;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .drag-handle {
            color: #bdc3c7;
            cursor: grab;
            padding: 0.25rem;
        }

        .drag-handle:hover {
            color: var(--accent-color);
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .task-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        /* Badge Styles */
        .badge-priority {
            background-color: var(--accent-color);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .project-badge {
            background-color: var(--success-color);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Button Styles */
        .btn {
            border-radius: 4px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-secondary {
            background-color: #95a5a6;
            border-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
            border-color: #7f8c8d;
        }

        .btn-outline-primary {
            border-color: var(--accent-color);
            color: var(--accent-color);
            background: white;
        }

        .btn-outline-primary:hover {
            background-color: var(--accent-color);
            color: white;
        }

        .btn-outline-danger {
            border-color: var(--danger-color);
            color: var(--danger-color);
            background: white;
        }

        .btn-outline-danger:hover {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-sm {
            padding: 0.35rem 0.85rem;
            font-size: 0.875rem;
        }

        /* Form Styles */
        .form-control, .form-select {
            border: 2px solid #dee2e6;
            border-radius: 4px;
            padding: 0.65rem 1rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 4px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        /* Table-like layout for projects */
        .project-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .project-item:hover {
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            border-color: var(--accent-color);
        }

        .project-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1.05rem;
        }

        .project-meta {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Info Box */
        .info-box {
            background-color: #e8f4f8;
            border: 1px solid #bee5eb;
            border-left: 4px solid var(--accent-color);
            border-radius: 4px;
            padding: 1rem 1.25rem;
        }

        .info-box h6 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .info-box ul {
            margin-bottom: 0;
            padding-left: 1.25rem;
        }

        .info-box li {
            color: var(--text-secondary);
            margin-bottom: 0.35rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                text-align: center;
            }

            .page-title {
                font-size: 1.5rem;
                justify-content: center;
            }

            .task-card {
                padding: 1rem;
            }

            .navbar-brand {
                font-size: 1.25rem;
            }
        }

        /* Drag & Drop Instruction Banner */
        .drag-instruction-banner {
            background: linear-gradient(135deg, #e8f4f8 0%, #d4e9f7 100%);
            border: 2px solid #3498db;
            border-radius: 4px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            animation: slideDown 0.4s ease;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.15);
        }

        .instruction-icon {
            width: 40px;
            height: 40px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .btn-close-instruction {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-close-instruction:hover {
            color: var(--danger-color);
            transform: scale(1.1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        /* Drag Handle with Hint */
        .drag-handle {
            position: relative;
        }

        .drag-hint {
            position: absolute;
            left: -180px;
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10;
        }

        .drag-hint::after {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid var(--primary-color);
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }

        .drag-hint i {
            font-size: 1rem;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-3px);
            }
        }

        /* Saving Indicator */
        .saving-indicator {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            animation: slideInRight 0.3s ease;
        }

        .saving-indicator.success {
            background: var(--success-color);
        }

        .saving-indicator.error {
            background: var(--danger-color);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Enhanced drag state */
        .task-card.dragging {
            opacity: 0.5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            transform: rotate(2deg);
        }

        /* Drop zone indicator */
        .task-card.drag-over {
            border-top: 3px solid var(--accent-color);
            margin-top: 1rem;
        }

        /* Mobile adjustments for drag hints */
        @media (max-width: 768px) {
            .drag-hint {
                left: auto;
                right: -10px;
                transform: translateY(-50%) translateX(100%);
            }

            .drag-hint::after {
                left: -8px;
                right: auto;
                border-left: none;
                border-right: 8px solid var(--primary-color);
            }

            .drag-instruction-banner {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .instruction-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }

        /* Enhanced Form Styles */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }

        .breadcrumb-item a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
        }

        .progress-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .step.active .step-number {
            background: var(--accent-color);
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .step.active .step-label {
            color: var(--accent-color);
        }

        .step-line {
            width: 100px;
            height: 2px;
            background: #e2e8f0;
            margin: 0 1rem;
        }

        .form-card {
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        .required-badge {
            float: right;
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: normal;
        }

        .form-group-enhanced {
            position: relative;
        }

        .form-control-enhanced {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .form-control-enhanced:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        .form-helper {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }

        .char-counter {
            font-weight: 600;
        }

        .btn-enhanced {
            padding: 0.65rem 1.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-enhanced:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .action-buttons {
            margin-top: 1rem;
        }

        .help-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 1.25rem;
            height: 100%;
        }

        .help-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .help-card h6 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
        }

        .help-card ul {
            padding-left: 1.25rem;
            margin-bottom: 0;
        }

        .help-card li {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }

        kbd {
            background: var(--primary-color);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Modal Enhancements */
        .modal-content {
            border: none;
            border-radius: 4px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: #f8f9fa;
        }

        .modal-title {
            font-weight: 700;
            color: var(--primary-color);
        }

        .task-preview {
            background: #f8f9fa;
            border-left: 4px solid var(--accent-color);
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }

        .task-preview strong {
            color: var(--primary-color);
        }

        /* Stats Card */
        .stat-item {
            padding: 0.5rem 0;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent-color);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
            font-weight: 600;
        }

        /* Delete Button in Form */
        .btn-outline-danger:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }

        /* Responsive Form */
        @media (max-width: 768px) {
            .progress-steps {
                padding: 1rem;
            }

            .step-line {
                width: 50px;
            }

            .step-label {
                font-size: 0.8rem;
            }

            .help-card {
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }

            .action-buttons .ms-auto {
                margin-left: 0 !important;
            }
        }

        /* Multi-Step Wizard Styles */
        .wizard-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .wizard-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .wizard-step-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e2e8f0;
            border: 3px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .wizard-step.active .wizard-step-circle {
            background: var(--accent-color);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.2);
        }

        .wizard-step.completed .wizard-step-circle {
            background: var(--success-color);
            border-color: var(--success-color);
        }

        .wizard-step-circle i {
            font-size: 1.5rem;
            color: #95a5a6;
            display: none;
        }

        .wizard-step.active .wizard-step-circle i,
        .wizard-step.completed .wizard-step-circle i {
            display: block;
            color: white;
        }

        .wizard-step-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #7f8c8d;
        }

        .wizard-step.active .wizard-step-number,
        .wizard-step.completed .wizard-step-number {
            display: none;
        }

        .wizard-step-label {
            margin-top: 0.75rem;
            font-weight: 600;
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        .wizard-step.active .wizard-step-label {
            color: var(--accent-color);
        }

        .wizard-step.completed .wizard-step-label {
            color: var(--success-color);
        }

        .wizard-step-line {
            width: 120px;
            height: 3px;
            background: #dee2e6;
            margin: 0 1rem;
            position: relative;
            top: -30px;
        }

        .wizard-step.completed + .wizard-step-line {
            background: var(--success-color);
        }

        /* Review Section */
        .review-section {
            padding: 1rem 0;
        }

        .review-item {
            padding: 1.25rem;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-left: 4px solid var(--accent-color);
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .review-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .review-value {
            font-size: 1.1rem;
            color: var(--text-primary);
        }

        .review-value strong {
            color: var(--primary-color);
        }

        /* Slide Animations */
        @keyframes slideOutLeft {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(-50px);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(50px);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Mobile Responsive for Wizard */
        @media (max-width: 768px) {
            .wizard-steps {
                padding: 1.5rem 1rem;
            }

            .wizard-step-circle {
                width: 50px;
                height: 50px;
            }

            .wizard-step-circle i {
                font-size: 1.25rem;
            }

            .wizard-step-number {
                font-size: 1.25rem;
            }

            .wizard-step-label {
                font-size: 0.85rem;
            }

            .wizard-step-line {
                width: 60px;
                top: -25px;
            }

            .review-item {
                padding: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('tasks.index') }}">
                <i class="bi bi-check2-square"></i> Task Manager
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('tasks.index') }}" class="nav-link-custom {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="bi bi-list-task"></i> Tasks
                </a>
                <a href="{{ route('projects.index') }}" class="nav-link-custom {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    <i class="bi bi-folder"></i> Projects
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container container-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> 
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
