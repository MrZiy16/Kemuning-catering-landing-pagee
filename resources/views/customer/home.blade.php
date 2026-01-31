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
   HERO SECTION - RESTAURANT THEME
   ========================================================================== */

.hero {
    min-height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    margin-top: 4rem;
}

/* Background Image with Overlay */
.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    z-index: 0;
}

/* Gradient Overlay */
.hero::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        135deg,
        rgba(44, 44, 44, 0.85) 0%,
        rgba(77, 62, 62, 0.75) 50%,
        rgba(196, 127, 96, 0.65) 100%
    );
    z-index: 1;
}

/* Animated Food Icons Pattern */
.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
    opacity: 0.05;
    background-image: 
        repeating-linear-gradient(45deg, transparent, transparent 50px, rgba(255,255,255,0.03) 50px, rgba(255,255,255,0.03) 100px);
}

/* Floating Elements */
.floating-element {
    position: absolute;
    z-index: 2;
    animation: float 6s ease-in-out infinite;
    opacity: 0.1;
    color: white;
    font-size: 3rem;
}

.floating-element:nth-child(1) {
    top: 15%;
    left: 10%;
    animation-delay: 0s;
}

.floating-element:nth-child(2) {
    top: 25%;
    right: 15%;
    animation-delay: 1.5s;
    font-size: 2.5rem;
}

.floating-element:nth-child(3) {
    bottom: 20%;
    left: 15%;
    animation-delay: 3s;
    font-size: 3.5rem;
}

.floating-element:nth-child(4) {
    bottom: 30%;
    right: 10%;
    animation-delay: 4.5s;
    font-size: 2rem;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(5deg);
    }
}

/* Sparkle Effect */
.sparkle {
    position: absolute;
    width: 3px;
    height: 3px;
    background: white;
    border-radius: 50%;
    animation: sparkle 3s ease-in-out infinite;
    z-index: 2;
}

@keyframes sparkle {
    0%, 100% {
        opacity: 0;
        transform: scale(0);
    }
    50% {
        opacity: 1;
        transform: scale(1);
    }
}

.sparkle:nth-child(1) { top: 20%; left: 20%; animation-delay: 0s; }
.sparkle:nth-child(2) { top: 40%; left: 80%; animation-delay: 1s; }
.sparkle:nth-child(3) { top: 60%; left: 30%; animation-delay: 2s; }
.sparkle:nth-child(4) { top: 80%; left: 70%; animation-delay: 1.5s; }
.sparkle:nth-child(5) { top: 30%; left: 60%; animation-delay: 2.5s; }

/* Glow Effect */
.glow-circle {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(212, 165, 116, 0.15) 0%, transparent 70%);
    filter: blur(40px);
    z-index: 1;
    animation: pulse 4s ease-in-out infinite;
}

.glow-circle:nth-child(1) {
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.glow-circle:nth-child(2) {
    bottom: 10%;
    right: 10%;
    animation-delay: 2s;
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.3;
        transform: scale(1);
    }
    50% {
        opacity: 0.6;
        transform: scale(1.1);
    }
}

/* Hero Container */
.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    position: relative;
    z-index: 3;
}

.hero-content {
    z-index: 3;
    position: relative;
    color: white;
}

.hero-title {
    font-size: 3.8rem;
    font-weight: 800;
    color: white;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    animation: fadeInUp 0.8s ease;
}

.hero-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    margin-top: 1rem;
    border-radius: 2px;
    box-shadow: 0 2px 10px rgba(212, 165, 116, 0.5);
}

.hero-subtitle {
    font-size: 1.3rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2.5rem;
    line-height: 1.7;
    font-weight: 400;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    animation: fadeInUp 0.8s ease 0.2s backwards;
}

.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    color: white;
    padding: 18px 40px;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    letter-spacing: 1px;
    transition: all 0.4s ease;
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.8s ease 0.4s backwards;
}

.cta-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.cta-button:hover::before {
    left: 100%;
}

.cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(212, 165, 116, 0.6);
}

.hero-image {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    animation: fadeIn 1s ease 0.3s backwards;
}

.food-placeholder {
    width: 100%;
    max-width: 550px;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 
        0 30px 60px rgba(0, 0, 0, 0.4),
        0 10px 25px rgba(0, 0, 0, 0.3);
    position: relative;
    transform: rotate(-2deg);
    transition: transform 0.3s ease;
    border: 3px solid rgba(212, 165, 116, 0.3);
}

.food-placeholder:hover {
    transform: rotate(0deg) scale(1.02);
}

.food-placeholder::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(196, 127, 96, 0.1) 0%, transparent 50%);
    z-index: 1;
    border-radius: 25px;
}

.food-placeholder img {
    width: 100%;
    height: auto;
    object-fit: cover;
    display: block;
    position: relative;
    z-index: 0;
}

/* ==========================================================================
    ABOUT SECTION
    ========================================================================== */

.about-section {
    padding: 100px 0;
    background: #ffffff;
    position: relative;
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
    position: relative;
}

.about-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    margin-top: 1rem;
    border-radius: 2px;
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
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
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

.about-image {
    position: relative;
}

.about-image img {
    width: 100%;
    height: auto;
    border-radius: 15px;
    opacity: 0.9;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

/* ==========================================================================
    SERVICE SECTION
    ========================================================================== */

.service-section {
    padding: 100px 0;
    background: #F5F5DC;
    position: relative;
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
    position: relative;
}

.section-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    margin: 1rem auto;
    border-radius: 2px;
}

.section-subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 3rem;
    font-size: 1.1rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
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
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.service-card:hover::before {
    transform: scaleX(1);
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
    position: relative;
}

.bestseller-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 200px;
    background: linear-gradient(180deg, #F5F5DC 0%, #ffffff 100%);
    z-index: 0;
}

.bestseller-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 1;
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
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.product-image {
    height: 280px;
    position: relative;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(196, 127, 96, 0.2);
}

.product-info {
    padding: 2rem;
    background: white;
}

.product-title {
    font-size: 1.4rem;
    color: #4D3E3E;
    margin-bottom: 1rem;
    font-weight: 600;
    line-height: 1.3;
}

.product-description {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
    font-size: 0.95rem;
}

.product-price {
    display: flex;
    align-items: baseline;
    margin-bottom: 1.5rem;
    gap: 0.5rem;
}

.price-amount {
    color: #C47F60;
    font-size: 1.5rem;
    font-weight: 700;
}

.product-button {
    display: block;
    width: 100%;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    color: white;
    text-align: center;
    padding: 1rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.product-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.product-button:hover::before {
    left: 100%;
}

.product-button:hover {
    background: linear-gradient(135deg, #D4A574 0%, #C47F60 100%);
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
    position: relative;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.testimonial-stars {
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
    color: #FFC107;
}

.testimonial-text {
    color: #666;
    line-height: 1.6;
    margin-bottom: 2rem;
    font-style: italic;
    position: relative;
    padding-left: 1.5rem;
}

.testimonial-text::before {
    content: '"';
    position: absolute;
    left: 0;
    top: -10px;
    font-size: 3rem;
    color: #C47F60;
    opacity: 0.3;
    font-family: serif;
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
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
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
    position: relative;
    overflow: hidden;
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

.contact-details a {
    color: #666;
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-details a:hover {
    color: #C47F60;
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
    position: relative;
}

.footer-links a::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: #D4A574;
    transition: width 0.3s ease;
}

.footer-links a:hover {
    color: #D4A574;
}

.footer-links a:hover::after {
    width: 100%;
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

    .floating-element {
        display: none;
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

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
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

/* Back to top button */
.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(196, 127, 96, 0.3);
    transition: all 0.3s ease;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
}

.back-to-top.show {
    opacity: 1;
    visibility: visible;
}

.back-to-top:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(196, 127, 96, 0.4);
}
</style>
</head>
<body>
    <x-navbar></x-navbar>
    <main id="home" class="hero">
        <!-- Background Pattern -->
        <div class="hero-pattern"></div>
        
        <!-- Floating Food Icons -->
        <div class="floating-element">🍽️</div>
        <div class="floating-element">👨‍🍳</div>
        <div class="floating-element">🥘</div>
        <div class="floating-element">🍷</div>
        
        <!-- Sparkle Effects -->
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        
        <!-- Glow Effects -->
        <div class="glow-circle"></div>
        <div class="glow-circle"></div>
        
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">CITA RASA PREMIUM<br>UNTUK MOMEN ANDA</h1>
                <p class="hero-subtitle">
                    Kami menciptakan pengalaman kuliner tak terlupakan.<br>
                    Kemuning Catering menghadirkan hidangan berkualitas, dibuat dengan bahan-bahan terbaik.
                </p>
                <a href="{{ route('pemesanan.index') }}" class="cta-button">Klik Untuk Pesan</a>
            </div>
            <div class="hero-image">
                <div class="food-placeholder">
                    <img src="{{ asset('image-removebg-preview.png') }}" alt="Kemuning Catering Food" class="img-fluid">
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
                    <img src="https://images.unsplash.com/photo-1623475173140-ad2f0369ca92?q=80&w=1024&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Chef Logo">
                </div>
            </div>
        </div>
    </section>

    <section class="service-section" id="services">
        <div class="service-container">
            <h2 class="section-title">Layanan Kami</h2>
            <p class="section-subtitle">Layanan premium untuk setiap acara spesial Anda</p>
            
            <div class="service-grid">
             
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
    <!-- DELIVERY AREA SECTION - Tambahkan setelah service-section, sebelum bestseller-section -->

<section class="delivery-section" id="delivery">
    <div class="delivery-container">
        <h2 class="section-title">📦 Area Pengiriman Kami</h2>
        <p class="section-subtitle">Klik untuk melihat detail zona dan daftar kecamatan yang kami layani</p>
        
        <div class="zones-grid">
            <!-- Zona 1 -->
            <div class="zone-card">
                <div class="zone-header" onclick="toggleZone(this)">
                    <div class="zone-title">
                        <h3>Zona 1</h3>
                        <span>Area Batang Kota</span>
                    </div>
                
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="zone-content">
                    <div class="content-section">
                        <div class="content-title">📍 Daftar Area</div>
                        <ul class="area-list">
                            <li>Kauman</li>
                            <li>Proyonanggan</li>
                            <li>Sambong</li>
                            <li>Pasekaran</li>
                            <li>Karangasem Utara / Selatan</li>
                        </ul>
                    </div>
                  
                </div>
            </div>

            <!-- Zona 2 -->
            <div class="zone-card">
                <div class="zone-header" onclick="toggleZone(this)">
                    <div class="zone-title">
                        <h3>Zona 2</h3>
                        <span>Batang Kota & Sekitarnya</span>
                    </div>
                   
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="zone-content">
                    <div class="content-section">
                        <div class="content-title">📍 Daftar Kecamatan</div>
                        <ul class="area-list">
                       
                            <li>Warungasem</li>
                            <li>Kandeman</li>
                        </ul>
                    </div>
                  
                </div>
            </div>

            <!-- Zona 3 -->
            <div class="zone-card">
                <div class="zone-header" onclick="toggleZone(this)">
                    <div class="zone-title">
                        <h3>Zona 3</h3>
                        <span>Batang Luar Kota</span>
                    </div>
                
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="zone-content">
                    <div class="content-section">
                        <div class="content-title">📍 Daftar Kecamatan</div>
                        <ul class="area-list">
                            <li>Tulis</li>
                            <li>Subah</li>
                            <li>Gringsing</li>
                            <li>Limpung</li>
                            <li>Banyuputih</li>
                        </ul>
                    </div>
                    <div class="divider"></div>
                
                </div>
            </div>

            <!-- Zona 4 -->
            <div class="zone-card">
                <div class="zone-header" onclick="toggleZone(this)">
                    <div class="zone-title">
                        <h3>Zona 4</h3>
                        <span>Area Batang Pegunungan </span>
                    </div>
                  
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="zone-content">
                    <div class="content-section">
                        <div class="content-title">📍 Daftar Kecamatan</div>
                        <ul class="area-list">
                            <li>Bawang</li>
                            <li>Pecalungan</li>
                            <li>Wonotunggal</li>
                            <li>Reban</li>
                            <li>Blado</li>
                            <li>Bandar</li>
                            <li>Tersono</li>
                        </ul>
                    </div>
                   
                </div>
            </div>

            <!-- Zona 5 -->
            <div class="zone-card">
                <div class="zone-header" onclick="toggleZone(this)">
                    <div class="zone-title">
                        <h3>Zona 5</h3>
                        <span>Luar Batang</span>
                    </div>
                
                    <div class="toggle-icon">▼</div>
                </div>
                <div class="zone-content">
                    <div class="content-section">
                        <div class="content-title">📍 Daftar Area</div>
                        <ul class="area-list">
                            <li>Kota Pekalongan</li>
                            <li>Kab. Pekalongan</li>
                            <li>Kendal / Weleri</li>
                        </ul>
                    </div>
                 
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ==========================================================================
    DELIVERY ZONES SECTION
    ========================================================================== */

.delivery-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #F5F5DC 0%, #ffffff 100%);
    position: relative;
}

.delivery-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-title {
    font-size: 2.5rem;
    color: #4D3E3E;
    text-align: center;
    margin-bottom: 0.5rem;
    animation: fadeInDown 0.6s ease;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #666;
    text-align: center;
    margin-bottom: 3rem;
}

.zones-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    animation: fadeInUp 0.6s ease 0.2s backwards;
}

.zone-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.zone-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.zone-header {
    background: linear-gradient(135deg, #C47F60 0%, #D4A574 100%);
    color: white;
    padding: 1.5rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.zone-header:hover {
    background: linear-gradient(135deg, #B36B4D 0%, #C39461 100%);
}

.zone-title {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex: 1;
}

.zone-title h3 {
    font-size: 1.3rem;
    font-weight: 600;
}

.zone-title span {
    font-size: 0.9rem;
    opacity: 0.9;
}

.zone-price {
    font-size: 1.2rem;
    font-weight: bold;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    margin: 0 1rem;
}

.toggle-icon {
    font-size: 1.5rem;
    transition: transform 0.3s ease;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.zone-card.active .toggle-icon {
    transform: rotate(180deg);
}

.zone-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    padding: 0 1.5rem;
}

.zone-card.active .zone-content {
    max-height: 600px;
    padding: 1.5rem;
}

.content-section {
    margin-bottom: 1.5rem;
}

.content-section:last-child {
    margin-bottom: 0;
}

.content-title {
    color: #C47F60;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.8rem;
}

.area-list, .char-list {
    list-style: none;
}

.area-list li, .char-list li {
    padding: 0.5rem 0;
    color: #4D3E3E;
    padding-left: 1.5rem;
    position: relative;
    font-size: 0.95rem;
    line-height: 1.5;
}

.area-list li:before, .char-list li:before {
    content: "▸";
    position: absolute;
    left: 0;
    color: #C47F60;
    font-weight: bold;
}

.divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #C47F60, transparent);
    margin: 1rem 0;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

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

/* ==========================================================================
    RESPONSIVE ZONES SECTION
    ========================================================================== */

@media (max-width: 768px) {
    .section-title {
        font-size: 1.8rem;
    }

    .zones-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .zone-header {
        padding: 1.2rem;
    }

    .zone-title h3 {
        font-size: 1.1rem;
    }

    .zone-price {
        font-size: 1rem;
        margin: 0 0.5rem;
    }

    .toggle-icon {
        width: 25px;
        height: 25px;
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .section-title {
        font-size: 1.5rem;
    }

    .section-subtitle {
        font-size: 0.95rem;
    }

    .zone-header {
        padding: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .zone-title {
        width: 100%;
    }

    .zone-price {
        font-size: 0.9rem;
        margin: 0;
    }

    .toggle-icon {
        width: 24px;
        height: 24px;
    }

    .zone-card.active .zone-content {
        max-height: 800px;
    }
}
</style>

<script>
function toggleZone(element) {
    const card = element.closest('.zone-card');
    
    // Close other cards
    document.querySelectorAll('.zone-card.active').forEach(openCard => {
        if (openCard !== card) {
            openCard.classList.remove('active');
        }
    });
    
    // Toggle current card
    card.classList.toggle('active');
}

// Close card when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.zone-card')) {
        document.querySelectorAll('.zone-card.active').forEach(card => {
            card.classList.remove('active');
        });
    }
});
</script>
    <section class="bestseller-section" id="bestseller">
        <div class="bestseller-container">
            <h2 class="section-title">Produk Best Sellers</h2>
            <p class="section-subtitle">Produk terlaris pilihan pelanggan kami</p>
            
            <div class="bestseller-grid">
                @forelse($bestSellers as $product)
                <div class="product-card">
                    <div class="product-image">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?q=80&w=2080&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Product placeholder">
                        @endif
                        <div class="product-badge">Best Seller</div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->nama_produk }}</h3>
                        <p class="product-description">{{ Str::limit($product->deskripsi ?? 'Produk terlaris dengan cita rasa yang lezat dan berkualitas tinggi.', 100) }}</p>
                        <div class="product-price">
                            <span class="price-label">Harga</span>
                            <span class="price-amount">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="price-unit">/porsi</span>
                        </div>
                        <a href="{{ route('pemesanan.index') }}" class="product-button">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">Belum ada produk terlaris</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="testimonial-section" id="testimonial">
        <div class="testimonial-container">
            <h2 class="section-title">Testimoni</h2>
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
            <h2 class="section-title">Pusat Informasi</h2>
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

    <!-- Back to Top Button -->
    <a href="#home" class="back-to-top" id="backToTop">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>

    <script>
        // Back to top button functionality
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        // Scroll animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);

            // Observe section titles and subtitles
            const elementsToAnimate = document.querySelectorAll('.section-title, .section-subtitle');
            elementsToAnimate.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
