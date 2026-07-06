<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin | QS Marketing</title>
    <link rel="shortcut icon" href="/assets/img/favicon.jpeg" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/animate.css" />
    <link rel="stylesheet" href="../assets/css/magnific-popup.css" />
    <link rel="stylesheet" href="../assets/css/meanmenu.css" />
    <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../assets/css/nice-select.css" />
    <link rel="stylesheet" href="../assets/css/color.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <style>
        .admin-card {
            background: rgba(10, 17, 40, 0.7);
            border: 1px solid rgba(14, 165, 233, 0.2);
            border-radius: 12px;
            padding: 24px;
        }

        .admin-table th,
        .admin-table td {
            color: #dbe6ff;
            vertical-align: middle;
        }

        .admin-table thead th {
            border-bottom: 1px solid rgba(14, 165, 233, 0.2);
        }

        .admin-table tbody td {
            border-top: 1px solid rgba(14, 165, 233, 0.15);
        }

        .admin-input {
            background: rgba(4, 10, 25, 0.8);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: #dbe6ff;
        }

        .admin-input:focus {
            background: rgba(4, 10, 25, 0.9);
            border-color: #A30F71;
            box-shadow: none;
            color: #dbe6ff;
        }
    </style>
</head>

<body class="body-bg">
    <header class="header-1 header-4 header-5">
        <div class="container-fluid">
            <div class="mega-menu-wrapper">
                <div class="header-main style-2">
                    <div class="logo">
                        <a href="../index.html" class="header-logo">
                            <img width="200" src="assets/img/logo/QS Logo.png" alt="logo-img" />
                        </a>
                    </div>
                    <div class="mean__menu-wrapper">
                        <div class="main-menu">
                            <nav id="mobile-menu">
                                <ul>
                                    <li><a href="index.php">Dashboard</a></li>
                                    <li><a href="category_create.php">Add Category</a></li>
                                    <li><a href="item_create.php">Add Item</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="section-padding">
        <div class="container">