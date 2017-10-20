<?php
session_start();
$baseurl = 'https://growtheye.com/yellowslate/app/';
?>
<!DOCTYPE html>
<head>

    <!-- Basic Page Needs
    ================================================== -->
    <title>YellowSlate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/colors/main.css" id="colors">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>

</head>

<body>

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Header Container
        ================================================== -->
        <header id="header-container" class="fixed fullwidth dashboard">

            <!-- Header -->
            <div id="header" class="not-sticky">
                <div class="container">

                    <!-- Left Side Content -->
                    <div class="left-side">

                        <!-- Logo -->
                        <div id="logo">
                            <a href="index.php"><img src="images/logo.png" alt=""></a>
                            <a href="index.php" class="dashboard-logo"><img src="images/logo.png" alt=""></a>
                        </div>

                        <!-- Mobile Navigation -->
                        <div class="menu-responsive">
                            <i class="fa fa-reorder menu-trigger"></i>
                        </div>



                    </div>
                    <!-- Left Side Content / End -->

                    <!-- Right Side Content / End -->
                    <div class="right-side">
                        <!-- Header Widget -->
                        <div class="header-widget">

                            <!-- User Menu -->
                            <div class="user-menu">
                                <div class="user-name"><span><img src="images/dashboard-avatar.jpg" alt=""></span>
                                    <?php echo $_SESSION['user']['user_name'];?>
                                </div>
                                <ul>
                                    <li><a href="dashboard.php"><i class="sl sl-icon-settings"></i> Dashboard</a></li>
                                    <li><a href="profile.php"><i class="sl sl-icon-user"></i> My Profile</a></li>
                                    <li><a href="index.php"><i class="sl sl-icon-power"></i> Logout</a></li>
                                </ul>
                            </div>

                            <a href="dashboard-add-listing.php" class="button border with-icon">Add Listing <i class="sl sl-icon-plus"></i></a>
                        </div>
                        <!-- Header Widget / End -->
                    </div>
                    <!-- Right Side Content / End -->

                </div>
            </div>
            <!-- Header / End -->

        </header>
        <div class="clearfix"></div>
        <!-- Header Container / End -->
        <!-- Dashboard -->
        <div id="dashboard">
            <!-- Navigation
            ================================================== -->
            <!-- Responsive Navigation Trigger -->
            <a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i> Dashboard Navigation</a>
            <div class="dashboard-nav">
                <div class="dashboard-nav-inner">
                    <ul data-submenu-title="Super Admin">
                        <li><a href="all-listings.php"><i class="sl sl-icon-layers"></i> All Listings</a></li>
                        <li><a href="users.php"><i class="sl sl-icon-people"></i> Users</a></li>
                        <li><a href="reviews-all.php"><i class="sl sl-icon-people"></i> Reviews</a></li>
                        <li><a href="all-leads.php"><i class="sl sl-icon-star"></i>All Leads</a></li>
                        <li><a href="all-articles.php"><i class="sl sl-icon-people"></i> All Articles</a></li>
                    </ul>
                    <ul data-submenu-title="Main">
                        <li><a href="index.php"><i class="sl sl-icon-settings"></i> Dashboard</a></li>
                        <li><a href="leads.php"><i class="sl sl-icon-star"></i> Leads</a></li>
                    </ul>
                    <ul data-submenu-title="Listings">
                        <li><a href="my-listings.php"><i class="sl sl-icon-layers"></i>Listings</a></li>
                        <li><a href="enquiries.php"><i class="sl sl-icon-star"></i> Enquires</a></li>
                        <li><a href="wishlist.php"><i class="sl sl-icon-heart"></i> Wishlist</a></li>
                        <li><a href="reviews.php"><i class="sl sl-icon-people"></i> Reviews</a></li>
                        <li><a href="my-articles.php"><i class="sl sl-icon-people"></i> My Articles</a></li>
                    </ul>
                    <ul data-submenu-title="Account">
                        <li><a href="my-profile.php"><i class="sl sl-icon-user"></i> My Profile</a></li>
                        <li><a href=""><i class="sl sl-icon-power"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
            <!-- Navigation / End -->
            <!-- Content
            ================================================== -->
            <div class="dashboard-content">
