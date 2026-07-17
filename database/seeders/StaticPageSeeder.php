<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'             => 'about-us',
                'title'            => 'About Us',
                'sort_order'       => 1,
                'meta_description' => 'Learn about PathToSnow Nepal — your trusted partner for authentic Himalayan travel experiences.',
                'content'          => '<h2>Welcome to PathToSnow Nepal</h2>
<p>PathToSnow is Nepal\'s premier online travel platform, connecting adventurers from around the world with authentic, locally-guided experiences in the heart of the Himalayas.</p>

<h3>Our Story</h3>
<p>Founded in Kathmandu, PathToSnow was born from a passion for responsible tourism and a deep love for Nepal\'s incredible landscapes, culture, and people. We believe that the best travel experiences come from genuine local knowledge and meaningful connections.</p>

<h3>What We Offer</h3>
<ul>
  <li><strong>Trekking &amp; Adventure</strong> — From Everest Base Camp to remote Himalayan trails</li>
  <li><strong>Cultural Experiences</strong> — UNESCO World Heritage sites, festivals, and local homestays</li>
  <li><strong>Wildlife &amp; Nature</strong> — Chitwan, Bardia, and Nepal\'s stunning national parks</li>
  <li><strong>Gear Shop</strong> — Authentic Nepali trekking gear and souvenirs</li>
</ul>

<h3>Our Mission</h3>
<p>We are committed to sustainable, community-based tourism that benefits local guides, porters, and villages across Nepal. Every booking you make directly supports Nepali families and conservation efforts.</p>

<h3>Get In Touch</h3>
<p>Have questions? Our team of local experts is always happy to help you plan your perfect Nepal adventure.</p>
<p>📧 <a href="mailto:hello@pathtosnow.com">hello@pathtosnow.com</a><br>📍 Thamel, Kathmandu, Nepal</p>',
            ],
            [
                'slug'             => 'privacy-policy',
                'title'            => 'Privacy Policy',
                'sort_order'       => 2,
                'meta_description' => 'PathToSnow Nepal Privacy Policy — how we collect, use and protect your personal data.',
                'content'          => '<h2>Privacy Policy</h2>
<p><em>Last updated: July 2026</em></p>

<p>PathToSnow Nepal ("we", "us", "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>

<h3>Information We Collect</h3>
<ul>
  <li><strong>Personal Information:</strong> Name, email address, phone number when you register or make a booking</li>
  <li><strong>Payment Information:</strong> Processed securely by our payment partners; we do not store card details</li>
  <li><strong>Usage Data:</strong> Pages visited, time spent, browser type, IP address for analytics</li>
</ul>

<h3>How We Use Your Information</h3>
<ul>
  <li>To process bookings and purchases</li>
  <li>To send booking confirmations and travel updates</li>
  <li>To improve our services and website experience</li>
  <li>To send promotional emails (you may opt out at any time)</li>
</ul>

<h3>Data Security</h3>
<p>We implement appropriate technical and organisational measures to protect your personal information against unauthorised access, alteration, or destruction.</p>

<h3>Third-Party Services</h3>
<p>We may use third-party services (Google Analytics, payment processors) that collect data under their own privacy policies.</p>

<h3>Your Rights</h3>
<p>You have the right to access, correct, or delete your personal data. Contact us at <a href="mailto:privacy@pathtosnow.com">privacy@pathtosnow.com</a> to exercise these rights.</p>

<h3>Contact</h3>
<p>For privacy-related enquiries: <a href="mailto:privacy@pathtosnow.com">privacy@pathtosnow.com</a></p>',
            ],
            [
                'slug'             => 'terms-of-service',
                'title'            => 'Terms of Service',
                'sort_order'       => 3,
                'meta_description' => 'PathToSnow Nepal Terms of Service — the rules and guidelines for using our platform.',
                'content'          => '<h2>Terms of Service</h2>
<p><em>Last updated: July 2026</em></p>

<p>By accessing or using PathToSnow Nepal ("the Service"), you agree to be bound by these Terms of Service. Please read them carefully.</p>

<h3>1. Acceptance of Terms</h3>
<p>By using our website, you confirm that you are at least 18 years old and agree to these terms. If you do not agree, please do not use our services.</p>

<h3>2. Bookings &amp; Payments</h3>
<ul>
  <li>All bookings are subject to availability and confirmation</li>
  <li>Prices are displayed in USD unless otherwise stated</li>
  <li>Full payment is required to confirm a booking</li>
  <li>PathToSnow acts as an agent between you and local service providers</li>
</ul>

<h3>3. Cancellation Policy</h3>
<ul>
  <li><strong>30+ days before departure:</strong> Full refund minus processing fees</li>
  <li><strong>15–29 days:</strong> 50% refund</li>
  <li><strong>0–14 days:</strong> No refund</li>
</ul>

<h3>4. Traveller Responsibilities</h3>
<p>You are responsible for ensuring you have valid travel documents, travel insurance, and physical fitness appropriate for your chosen activity.</p>

<h3>5. Limitation of Liability</h3>
<p>PathToSnow shall not be liable for injury, loss, or damage arising from participation in activities. All adventure activities carry inherent risks.</p>

<h3>6. Governing Law</h3>
<p>These terms are governed by the laws of Nepal. Disputes shall be subject to the exclusive jurisdiction of courts in Kathmandu.</p>

<h3>Contact</h3>
<p>For legal enquiries: <a href="mailto:legal@pathtosnow.com">legal@pathtosnow.com</a></p>',
            ],
            [
                'slug'             => 'contact-us',
                'title'            => 'Contact Us',
                'sort_order'       => 4,
                'meta_description' => 'Get in touch with PathToSnow Nepal — we\'re here to help plan your perfect Nepal adventure.',
                'content'          => '<h2>Contact Us</h2>
<p>We\'d love to hear from you! Whether you have a question about a package, need help with a booking, or just want to learn more about Nepal — our team is here to help.</p>

<h3>📍 Our Office</h3>
<p>PathToSnow Nepal<br>Thamel, Kathmandu 44600<br>Nepal</p>

<h3>📞 Phone &amp; WhatsApp</h3>
<p>+977-1-4700000<br>WhatsApp: +977-9800000000<br>Available: Sunday–Friday, 9am–6pm NPT</p>

<h3>📧 Email</h3>
<ul>
  <li>General enquiries: <a href="mailto:hello@pathtosnow.com">hello@pathtosnow.com</a></li>
  <li>Bookings: <a href="mailto:bookings@pathtosnow.com">bookings@pathtosnow.com</a></li>
  <li>Support: <a href="mailto:support@pathtosnow.com">support@pathtosnow.com</a></li>
</ul>

<h3>🕐 Response Time</h3>
<p>We aim to respond to all enquiries within 24 hours during business days.</p>',
            ],
        ];

        foreach ($pages as $data) {
            StaticPage::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
