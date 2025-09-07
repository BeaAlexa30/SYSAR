<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<!-- Add this CSS to ensure footer stays below content -->
<style>
    footer {
        position: relative;
        width: 100%;
        padding: 10px 20px;
        background-color: rgba(226, 233, 240, 0.8);
        backdrop-filter: blur(2px);
        bottom: 0%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap; /* Allows wrapping for smaller screens */
    }

    .footer-left {
        display: flex;
        align-items: center;
    }

    .footer-right {
        text-align: right;
        color: black;
    }

    .footer-center {
        text-align: center;
        width: 100%; /* Ensures it spans the full width */
        margin-top: 10px;
        font-size: 0.9rem;
        color: gray;
    }
</style>

<footer id="footer" class="py-3 mt-5">
    <!-- Left Side: Logo and Name -->
    <div class="footer-left">
        <a class="navbar-brand d-flex align-items-center" href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
            <img src="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" alt="Logo" 
                style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
            <span>
                <span style="font-weight: bold; color: red;">SANGGUNIANG KABATAAN</span><br>
                <span style="color: blue;">BARANGAY 252 ZONE 23</span>
            </span>
        </a>
    </div>

    <!-- Right Side: Links and Contact Info -->
    <div class="footer-right">
        <h5 style="margin-right: 20%; color: blue;">Contact Us</h5>
        <div>
            <p style="text-decoration: none; color: black; font-size: 1.1rem;">0951-918-0809 or <a href="mailto:barangay252bnb@gmail.com" style="text-decoration: none; color: black;">barangay252bnb@gmail.com</a></p>
        </div>
        <!-- Social Icons Using Bootstrap Icons -->
        <div style="align-items: center;">
            <a href="https://www.facebook.com/profile.php?id=61566414504036" style="text-decoration: none; color: blue; margin-right: 20px; font-size: 1.1rem;">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="mailto:barangay252bnb@gmail.com" style="text-decoration: none; color: black; margin-right: 70px; font-size: 1.1rem;">
                <i class="bi bi-envelope"></i>
            </a>
        </div>
        <div>
            <a href="#" style="text-decoration: none; color: black; margin-right: 15px;">TERMS OF SERVICE</a> |
            <a href="#" style="text-decoration: none; color: black; margin-left: 15px;">PRIVACY POLICY</a>
        </div>
    </div>

    <!-- Center Section: Copyright -->
    <div class="footer-center">
        &copy; 2025 Sangguniang Kabataan Barangay 252 Zone 23. All Rights Reserved.
    </div>
</footer>
