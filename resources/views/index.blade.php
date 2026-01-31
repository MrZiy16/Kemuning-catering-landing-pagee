<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Kemuning Catering - Catering Service Kabupaten Pekalongan | Catering Terbaik</title>
    <meta name="title" content="Kemuning Catering - Catering Service Kabupaten Pekalongan | Catering Terbaik">
    <meta name="description" content="Kemuning Catering menyediakan layanan catering di Kabupaten Batang dengan pengalaman 10+ tahun. Spesialis gudeg premium, ayam bakar madu, dan soto betawi. Hubungi +62 812-3456-7890">
    <meta name="keywords" content="catering jakarta, catering premium, gudeg premium, ayam bakar madu, soto betawi, catering pernikahan, catering corporate, catering halal kualitas, catering terbaik, catering murah, catering online, catering delivery, catering event, catering keluarga, catering kantor, catering spesial">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="author" content="Kemuning Catering">
    <meta name="copyright" content="© 2026 Kemuning Catering">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Navigation -->


    <!-- Hero Section -->
    <main id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">NIKMATI<br>RASA TERBAIK<br>BERSAMA KEMUNING CATERING</h1>
                <p class="hero-subtitle">
                    Membuat momen spesial dengan cita rasa terbaik.<br>
                    Setiap saat menjadi kenangan yang tak terlupakan.
                </p>
                <a href="" class="cta-button">RESERVE NOW</a>
            </div>
            <div class="hero-image">
                <div class="food-placeholder">
                    <img src="https://www.lalamove.com/hubfs/catering%20lunch%20box%20%284%29.jpg" alt="Kemuning Catering Food">
                </div>
            </div>
        </div>
    </main>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="about-container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="about-title">About Kemuning Catering</h2>
                    <div class="about-description">
                        <p>Dengan pengalaman lebih dari 10 tahun di industri kuliner, Kemuning Catering telah menjadi pilihan utama untuk berbagai acara istimewa. Kami menghadirkan cita rasa autentik dengan sentuhan modern yang memukau.</p>
                        <p>Dipimpin oleh Chef berpengalaman dengan keahlian internasional, setiap hidangan dibuat dengan passion dan dedikasi tinggi. Kami memahami bahwa setiap acara memiliki keunikan tersendiri.</p>
                        <p>Filosofi kami sederhana: "Menciptakan kenangan melalui kelezatan". Setiap sajian adalah karya seni yang akan membekas di hati tamu Anda.</p>
                    </div>
                    <div class="about-stats">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Events Served</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">10+</div>
                            <div class="stat-label">Years Experience</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Client Satisfaction</div>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="https://w7.pngwing.com/pngs/289/292/png-transparent-chef-logo.png" alt="Chef Logo">
                </div>
            </div>
        </div>
    </section>

    <!-- Service Section -->
    <section class="service-section" id="services">
        <div class="service-container">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Layanan premium untuk setiap acara spesial Anda</p>
            
            <div class="service-grid">
                <div class="service-card">
                    <div class="service-icon">🎂</div>
                    <h3>Wedding Catering</h3>
                    <p>Layanan catering pernikahan dengan menu premium dan dekorasi meja yang elegan</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🏢</div>
                    <h3>Corporate Event</h3>
                    <p>Solusi catering profesional untuk seminar, meeting, dan acara perusahaan</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🎉</div>
                    <h3>Birthday Party</h3>
                    <p>Paket catering khusus untuk perayaan ulang tahun yang berkesan</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">👨‍👩‍👦</div>
                    <h3>Family Gathering</h3>
                    <p>Menu variatif untuk acara kumpul keluarga dan silaturahmi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Seller Section -->
  <section class="bestseller-section" id="bestseller">
    <div class="bestseller-container">
        <h2 class="section-title">Our Best Seller</h2>
        <p class="section-subtitle">Produk pilihan terbaik yang paling digemari pelanggan</p>
        
        <div class="bestseller-grid">
            @if(isset($orders) && count($orders) > 0)
                @foreach ($orders as $order)
                    <div class="product-card">
                        <div class="product-image">
                            <div class="product-placeholder">
                                @if ($order->paketMenu && $order->paketMenu->foto)
                                    <img src="{{ asset('storage/' . $order->paketMenu->foto) }}" 
                                         alt="{{ $order->paketMenu->nama }}" 
                                         class="product-icon-img">
                                @else
                                    <div class="product-icon">🍽️</div>
                                @endif
                            </div>
                            <div class="product-badge">Best Seller</div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">{{ $order->paketMenu->nama ?? '-' }}</h3>
                            <p class="product-description">{{ $order->paketMenu->deskripsi ?? '-' }}</p>
                            <div class="product-price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-amount">
                                    Rp {{ number_format($order->paketMenu->harga ?? 0, 0, ',', '.') }}
                                </span>
                                <span class="price-unit">/porsi</span>
                            </div>
                    
                        </div>
                    </div>
                @endforeach
            @else
                <p>Belum ada data best seller.</p>
            @endif
        </div>
    </div>
</section>


    <!-- Testimonial Section -->
 <section class="testimonial-section" id="testimonial">
    <div class="testimonial-container">
        <h2 class="section-title">What Our Clients Say</h2>
        <p class="section-subtitle">Kepuasan pelanggan adalah prioritas utama kami</p>
        
        <div class="testimonial-grid">
            @forelse($testimoni as $item)
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <span>{{ str_repeat('⭐', $item->rating) }}</span>
                    </div>
                    <p class="testimonial-text">
                        "{{ $item->komentar }}"
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($item->pengguna->nama, 0, 1)) }}
                            </div>
                        </div>
                        <div class="author-info">
                            <div class="author-name">{{ $item->pengguna->nama }}</div>
                            <div class="author-role">
                                {{-- kalau mau bisa tambahin tipe produk --}}
                                {{ $item->paket_box_id ? 'Paket Box' : ($item->paket_prasmanan_id ? 'Paket Prasmanan' : 'Menu Prasmanan') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Belum ada testimoni pelanggan.</p>
            @endforelse
        </div>
    </div>
</section>


    <!-- Contact Section -->
   <section class="contact-section" id="contact">
    <div class="contact-container">
        <h2 class="section-title">Get In Touch</h2>
        <p class="section-subtitle">Hubungi kami untuk konsultasi dan pemesanan</p>
        
        <div class="contact-content">
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon">📱</div>
                    <div class="contact-details">
                        <h3>Phone</h3>
                        <p><a href="tel:+6282217463605">082217463605</a></p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon">✉️</div>
                    <div class="contact-details">
                        <h3>Email</h3>
                        <p><a href="mailto:kemuningcatering7@gmail.com">kemuningcatering7@gmail.com</a></p>
    
                    </div>
                </div>
                
              
            <div class="contact-item">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31686.713762424522!2d109.69106931083985!3d-6.909820000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7025d5fbc36813%3A0xe3ed9a16f7fe4d2c!2sKemuning%20Catering!5e0!3m2!1sid!2sid!4v1757475992305!5m2!1sid!2sid"
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>Kemuning Catering</h3>
                    <p>Premium Catering Service</p>
                </div>
                <div class="footer-links">
                    <a href="#home">Home</a>
                    <a href="#about">About</a>
                    <a href="#services">Services</a>
                    <a href="#bestseller">Best Seller</a>
                    <a href="#testimonial">Testimonial</a>
                    <a href="#contact">Contact</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Kemuning Catering. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Order Product Function
        function orderProduct(productName) {
            const phone = '+6281234567890';
            const message = `Halo, saya tertarik untuk memesan ${productName}. Bisa berikan informasi lebih lanjut?`;
            const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', () => {
            const elementsToAnimate = document.querySelectorAll('.section-title, .section-subtitle');
            elementsToAnimate.forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
