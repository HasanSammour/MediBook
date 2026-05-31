<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-brand">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="MediBook Logo">
                    <h3>MediBook</h3>
                </div>
                <p>Integrated Medical Appointment Management System</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('features') }}">Features</a></li>
                    <li><a href="{{ route('hospitals') }}">Hospitals</a></li>
                    <li><a href="{{ route('doctors') }}">Doctors</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Account</h4>
                <ul>
                    @auth
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-footer').submit();">Logout</a></li>
                        <form id="logout-form-footer" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register (Patients Only)</a></li>
                    @endauth
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact Us</h4>
                <ul>
                    <li><i class="fas fa-phone"></i> +1 234 567 890</li>
                    <li><i class="fas fa-envelope"></i> info@medibook.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Healthcare St, Medical City</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} MediBook. All rights reserved.</p>
        </div>
    </div>
</footer>