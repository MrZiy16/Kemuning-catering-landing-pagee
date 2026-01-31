<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemuning Catering - Layanan Catering Premium</title>
    <meta name="title" content="Kemuning Catering - Catering Service Kabupaten Pekalongan | Catering Terbaik">
    <meta name="description" content="Kemuning Catering menyediakan layanan catering premium di Kabupaten Pekalongan dengan pengalaman 10+ tahun. Spesialis gudeg premium, ayam bakar madu, dan soto betawi.">
    <meta name="keywords" content="catering, catering premium, catering pernikahan, catering corporate, catering halal, catering terbaik, catering online, catering event">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="author" content="Kemuning Catering">
    <meta name="copyright" content="© 2025 Kemuning Catering">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
    /* ==========================================================================
    KEMUNING CATERING - STYLES
    ========================================================================== */

/* Reset & Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: 'Poppins', sans-serif;
    line-height: 1.6;
    color: #4D3E3E;
    background-color: #F5F5DC;
    overflow-x: hidden;
}

/* ==========================================================================
    HERO SECTION
    ========================================================================== */

.hero {
    min-height: 100vh;
    background-color: #F5F5DC;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.hero-content {
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: #4D3E3E;
    line-height: 1.1;
    margin-bottom: 1.5rem;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 2.5rem;
    line-height: 1.6;
}

.cta-button {
    display: inline-block;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    color: white;
    padding: 15px 35px;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(196, 127, 96, 0.3);
}

.cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(196, 127, 96, 0.4);
}

.hero-image {
    display: flex;
    justify-content: center;
    align-items: center;
}

.food-placeholder {
    width: 100%;
    max-width: 500px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.food-placeholder img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

/* ==========================================================================
    ABOUT SECTION
    ========================================================================== */

.about-section {
    padding: 100px 0;
    background: #ffffff;
}

.about-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.about-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 4rem;
    align-items: center;
}

.about-title {
    font-size: 2.5rem;
    color: #4D3E3E;
    margin-bottom: 2rem;
    font-weight: 600;
}

.about-description {
    margin-bottom: 2rem;
}

.about-description p {
    margin-bottom: 1rem;
    color: #666;
    line-height: 1.8;
}

.about-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    background: #F5F5DC;
    border-radius: 15px;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #C47F60;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-weight: 500;
}

.about-image img {
    width: 100%;
    height: auto;
    border-radius: 15px;
    opacity: 0.8;
}

/* ==========================================================================
    SERVICE SECTION
    ========================================================================== */

.service-section {
    padding: 100px 0;
    background: #F5F5DC;
}

.service-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-title {
    font-size: 2.5rem;
    text-align: center;
    color: #4D3E3E;
    margin-bottom: 1rem;
    font-weight: 600;
}

.section-subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 3rem;
    font-size: 1.1rem;
}

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.service-card {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.service-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: #C47F60;
}

.service-card h3 {
    font-size: 1.3rem;
    color: #4D3E3E;
    margin-bottom: 1rem;
    font-weight: 600;
}

.service-card p {
    color: #666;
    line-height: 1.6;
}

/* ==========================================================================
    BESTSELLER SECTION
    ========================================================================== */

.bestseller-section {
    padding: 100px 0;
    background: #ffffff;
}

.bestseller-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.bestseller-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
}

.product-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.product-image {
    position: relative;
    height: 250px;
    background: #F5F5DC;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #D4A574;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-info {
    padding: 2rem;
}

.product-title {
    font-size: 1.3rem;
    color: #4D3E3E;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.product-description {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.product-price {
    margin-bottom: 1.5rem;
}

.price-label {
    color: #666;
    font-size: 0.9rem;
}

.price-amount {
    color: #C47F60;
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 5px;
}

.price-unit {
    color: #666;
    font-size: 0.9rem;
}

.product-button {
    width: 100%;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.product-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(196, 127, 96, 0.3);
}

/* ==========================================================================
    TESTIMONIAL SECTION
    ========================================================================== */

.testimonial-section {
    padding: 100px 0;
    background: #F5F5DC;
}

.testimonial-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.testimonial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.testimonial-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.testimonial-stars {
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
    color: #FFC107; /* Warna bintang kuning */
}

.testimonial-text {
    color: #666;
    line-height: 1.6;
    margin-bottom: 2rem;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #C47F60;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-placeholder {
    color: white;
    font-weight: 600;
    font-size: 1.2rem;
}

.author-info {
    text-align: left;
}

.author-name {
    font-weight: 600;
    color: #4D3E3E;
}

.author-role {
    color: #666;
    font-size: 0.9rem;
}

/* ==========================================================================
    CONTACT SECTION
    ========================================================================== */

.contact-section {
    padding: 100px 0;
    background: #ffffff;
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    text-align: center;
}

.contact-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.contact-item {
    background: #F5F5DC;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    text-align: center;
    transition: all 0.3s ease;
}

.contact-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.contact-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
}

.contact-details h3 {
    font-size: 1.2rem;
    color: #4D3E3E;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.contact-details p {
    color: #666;
    font-size: 1.1rem;
}

/* ==========================================================================
    FOOTER STYLES
    ========================================================================== */

.footer {
    background: #4D3E3E;
    color: white;
    padding: 3rem 0 1rem;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.footer-logo h3 {
    color: #D4A574;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.footer-logo p {
    color: #ccc;
}

.footer-links {
    display: flex;
    gap: 2rem;
}

.footer-links a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #D4A574;
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid #555;
    color: #999;
}

/* ==========================================================================
    RESPONSIVE DESIGN
    ========================================================================== */

@media (max-width: 768px) {
    .hero-container {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 2rem;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .about-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .about-stats {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .service-grid,
    .bestseller-grid,
    .testimonial-grid,
    .contact-info {
        grid-template-columns: 1fr;
    }
    
    .footer-content {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .footer-links {
        justify-content: center;
    }
    
    .section-title, .about-title {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .hero,
    .about-section,
    .service-section,
    .bestseller-section,
    .testimonial-section,
    .contact-section {
        padding: 60px 0;
    }
    
    .service-card,
    .testimonial-card,
    .contact-item {
        padding: 1.5rem;
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .footer-links {
        flex-wrap: wrap;
        gap: 1rem;
    }
}

/* ==========================================================================
    ANIMATIONS & EFFECTS
    ========================================================================== */

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-content,
.about-text,
.service-card,
.product-card,
.testimonial-card,
.contact-item {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
}

/* Stagger animation delays */
.service-card:nth-child(1) { animation-delay: 0.1s; }
.service-card:nth-child(2) { animation-delay: 0.2s; }
.service-card:nth-child(3) { animation-delay: 0.3s; }
.service-card:nth-child(4) { animation-delay: 0.4s; }

.product-card:nth-child(1) { animation-delay: 0.1s; }
.product-card:nth-child(2) { animation-delay: 0.2s; }
.product-card:nth-child(3) { animation-delay: 0.3s; }

.testimonial-card:nth-child(1) { animation-delay: 0.1s; }
.testimonial-card:nth-child(2) { animation-delay: 0.2s; }
.testimonial-card:nth-child(3) { animation-delay: 0.3s; }

.contact-item:nth-child(1) { animation-delay: 0.1s; }
.contact-item:nth-child(2) { animation-delay: 0.2s; }
.contact-item:nth-child(3) { animation-delay: 0.3s; }

/* Scroll animations */
.section-title,
.section-subtitle {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease;
}

.section-title.animate,
.section-subtitle.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #D4A574;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #C47F60;
}
</style>
<body>
    <main id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">CITA RASA PREMIUM<br>UNTUK MOMEN ANDA</h1>
                <p class="hero-subtitle">
                    Kami menciptakan pengalaman kuliner tak terlupakan.<br>
                    Kemuning Catering menghadirkan hidangan berkualitas, dibuat dengan bahan-bahan terbaik.
                </p>
                <a href="https://wa.me/6281234567890" class="cta-button" target="_blank">RESERVE NOW</a>
            </div>
            <div class="hero-image">
                <div class="food-placeholder">
                    <img src="https://images.unsplash.com/photo-1542180790-a24c1305dfa0?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Kemuning Catering Food">
                </div>
            </div>
        </div>
    </main>

    <section class="about-section" id="about">
        <div class="about-container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="about-title">Lebih Dari Sekadar Makanan</h2>
                    <div class="about-description">
                        <p>Dengan pengalaman lebih dari 10 tahun, Kemuning Catering telah menjadi pilihan utama untuk berbagai acara. Kami menghadirkan cita rasa autentik dengan sentuhan modern yang memukau.</p>
                        <p>Dipimpin oleh Chef berpengalaman, setiap hidangan dibuat dengan passion dan dedikasi tinggi. Kami memahami bahwa setiap acara memiliki keunikan tersendiri.</p>
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
                    <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=2832&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Chef Logo">
                </div>
            </div>
        </div>
    </section>

    <section class="service-section" id="services">
        <div class="service-container">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Layanan premium untuk setiap acara spesial Anda</p>
            
            <div class="service-grid">
                <div class="service-card">
                    <div class="service-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </div>
                    <h3>Wedding Catering</h3>
                    <p>Layanan catering pernikahan dengan menu premium dan dekorasi meja yang elegan</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    </div>
                    <h3>Corporate Event</h3>
                    <p>Solusi catering profesional untuk seminar, meeting, dan acara perusahaan</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 12 12 12 20"></polyline><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path><line x1="12" y1="2" x2="12" y2="7"></line><path d="M12 7c-4.42 0-8 2.24-8 5s3.58 5 8 5 8-2.24 8-5-3.58-5-8-5z"></path></svg>
                    </div>
                    <h3>Birthday Party</h3>
                    <p>Paket catering khusus untuk perayaan ulang tahun yang berkesan</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <h3>Family Gathering</h3>
                    <p>Menu variatif untuk acara kumpul keluarga dan silaturahmi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bestseller-section" id="bestseller">
        <div class="bestseller-container">
            <h2 class="section-title">Our Best Seller</h2>
            <p class="section-subtitle">Produk pilihan terbaik yang paling digemari pelanggan</p>
            
            <div class="bestseller-grid">
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1563829490159-43c2d4310574?q=80&w=2671&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Nasi Box">
                        <div class="product-badge">Best Seller</div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Paket Nasi Box Hemat</h3>
                        <p class="product-description">Nasi dengan lauk ayam goreng, telur balado, sayuran, dan sambal.</p>
                        <div class="product-price">
                            <span class="price-label">Mulai dari</span>
                            <span class="price-amount">Rp 25.000</span>
                            <span class="price-unit">/porsi</span>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1549488349-e58f0d86e082?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Prasmanan">
                        <div class="product-badge">Best Seller</div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Paket Prasmanan Komplit</h3>
                        <p class="product-description">Aneka hidangan prasmanan lengkap dengan dessert dan minuman segar.</p>
                        <div class="product-price">
                            <span class="price-label">Mulai dari</span>
                            <span class="price-amount">Rp 75.000</span>
                            <span class="price-unit">/porsi</span>
                        </div>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=2574&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Ayam Bakar Madu">
                        <div class="product-badge">Best Seller</div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Ayam Bakar Madu</h3>
                        <p class="product-description">Ayam bakar dengan bumbu khas dan madu murni, disajikan dengan nasi hangat.</p>
                        <div class="product-price">
                            <span class="price-label">Mulai dari</span>
                            <span class="price-amount">Rp 30.000</span>
                            <span class="price-unit">/porsi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonial-section" id="testimonial">
        <div class="testimonial-container">
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle">Kepuasan pelanggan adalah prioritas utama kami</p>
            
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <span>⭐⭐⭐⭐⭐</span>
                    </div>
                    <p class="testimonial-text">
                        "Menu prasmanannya enak sekali! Semua tamu memuji masakannya. Terima kasih Kemuning Catering!"
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <div class="avatar-placeholder">R</div>
                        </div>
                        <div class="author-info">
                            <div class="author-name">Rina W.</div>
                            <div class="author-role">Paket Prasmanan</div>
                        </div>
                    </div>
                </div>
                 <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <span>⭐⭐⭐⭐⭐</span>
                    </div>
                    <p class="testimonial-text">
                        "Pesanan nasi box untuk acara kantor datang tepat waktu dan rasanya lezat. Recommended!"
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <div class="avatar-placeholder">A</div>
                        </div>
                        <div class="author-info">
                            <div class="author-name">Agus P.</div>
                            <div class="author-role">Paket Box</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <span>⭐⭐⭐⭐⭐</span>
                    </div>
                    <p class="testimonial-text">
                        "Pesan menu custom untuk syukuran, rasanya tidak mengecewakan. Sesuai ekspektasi dan pelayanannya ramah!"
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <div class="avatar-placeholder">S</div>
                        </div>
                        <div class="author-info">
                            <div class="author-name">Siti M.</div>
                            <div class="author-role">Custom Menu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                            <p><a href="tel:+6281234567890">+62 812-3456-7890</a></p>
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
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.0000000000005!2d110.00000000000001!3d-7.000000000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMDAnMDAuMCJTIDExMMKwMDAnMDAuMCJF!5e0!3m2!1sid!2sid!4v1633512345678!5m2!1sid!2sid"
                            width="100%" 
                            height="200" 
                            style="border:0; border-radius:10px;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                <p>© 2025 Kemuning Catering. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>