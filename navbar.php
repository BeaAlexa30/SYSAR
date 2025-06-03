<style>
    /* Sidebar */
    .sidebar {
        height: 100vh;
        width: 200px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #00205b;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    /* Sidebar Header */
    .sidebar-header {
        background-color: #00205b;
        color: white;
        padding: 15px;
        font-size: 18px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Sidebar Links */
    .sidebar a {
        padding: 15px 20px;
        text-decoration: none;
        font-size: 16px;
        color: white;
        display: block;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .sidebar a.active {
        color: red;
        font-weight: bold;
    }

    /* Navbar */
    .navbar {
        position: fixed;
        top: 0;
        z-index: 1050;
        background-color: rgba(226, 233, 240, 0.2);
        backdrop-filter: blur(2px);
        width: 100%;
    }

    /* Active Navbar Link */
    .navbar-nav .nav-item .nav-link.active {
        font-weight: bold;
        color: blue !important;
    }

    /* Adjust layout for logged-in users */
    <?php if (isset($_SESSION['username'])) : ?>
        .navbar { width: calc(100% - 200px); margin-left: 200px; }
        .main-content { margin-left: 200px; }
    <?php else : ?>
        .navbar { width: 100%; margin-left: 0; }
        .main-content { margin-left: 0; }
    <?php endif; ?>
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<body>
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>

    <!-- Sidebar (Only visible when logged in) -->
    <?php if (isset($_SESSION['username'])) : ?>
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-bars"></i> <span class="header-title">Menu</span>
            </div>
            <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
            <a href="youth_manage.php" class="<?= $current_page == 'youth_manage.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> <span>Youth Manage</span>
            </a>
            <a href="doc_to_print.php" class="<?= $current_page == 'doc_to_print.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i> <span>Document to Print</span>
            </a>
            <a href="accepted_assistance.php" class="<?= $current_page == 'accepted_assistance.php' ? 'active' : ''; ?>">
                <i class="fas fa-graduation-cap"></i> <span>Educational Assistance</span>
            </a>
        </div>
    <?php endif; ?>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                <img src="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" alt="Logo" style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
                <span>
                    <span style="font-weight: bold; color: red;">SANGGUNIANG KABATAAN</span><br>
                    <span style="color: blue;">BARANGAY 252 ZONE 23</span>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (!isset($_SESSION['username'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page == 'home.php' ? 'active' : ''; ?>" href="home.php">HOME</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= in_array($current_page, ['request_residentID.php', 'docreq_form.php', 'educ_assistance_form.php', 'request.php']) ? 'active' : ''; ?>" 
                                id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                REQUEST
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item <?= $current_page == 'request_residentID.php' ? 'active' : ''; ?>" href="request_residentID.php">Resident ID Request</a></li>
                                <li><a class="dropdown-item <?= $current_page == 'docreq_form.php' ? 'active' : ''; ?>" href="docreq_form.php">Print Document Request</a></li>
                                <li><a class="dropdown-item <?= $current_page == 'educ_assistance_form.php' ? 'active' : ''; ?>" href="educ_assistance_form.php">Educational Assistance</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page == 'contact.php' ? 'active' : ''; ?>" href="#footer">CONTACT US</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page == 'login.php' ? 'active' : ''; ?>" href="login.php">LOGIN</a>
                        </li>


                        <!-- YOUTH MANAGE -->
                    <?php else : ?>
                        <?php if ($current_page == 'dashboard.php') : ?>
                        <?php elseif (in_array($current_page, ['youth_manage.php', 'pending_reqID.php', 'youth_archive.php','archive-youth.php'])) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'youth_manage.php' ? 'fw-bold text-primary' : ''; ?>" href="youth_manage.php">MASTERLIST</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'pending_reqID.php' ? 'fw-bold text-primary' : ''; ?>" href="pending_reqID.php">PENDING</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'archive-youth.php' ? 'fw-bold text-primary' : ''; ?>" href="archive-youth.php">ARCHIVE</a>
                            </li>



                        <?php elseif (in_array($current_page, ['doc_to_print.php', 'history_docs.php'])) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'doc_to_print.php' ? 'fw-bold text-primary' : ''; ?>" href="doc_to_print.php">PENDING</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'history_docs.php' ? 'fw-bold text-primary' : ''; ?>" href="history_docs.php">BACKLOGS</a>
                            </li>


                            <?php elseif (in_array($current_page, ['accepted_assistance.php', 'pending_assistance.php', 'archive-assistance.php'])) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'accepted_assistance.php' ? 'fw-bold text-primary' : ''; ?>" href="accepted_assistance.php">MASTERLIST</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'pending_assistance.php' ? 'fw-bold text-primary' : ''; ?>" href="pending_assistance.php">PENDING</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page == 'archive-assistance.php' ? 'fw-bold text-primary' : ''; ?>" href="archive-assistance.php">ARCHIVE</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger fw-bold" href="logout.php">LOGOUT</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
