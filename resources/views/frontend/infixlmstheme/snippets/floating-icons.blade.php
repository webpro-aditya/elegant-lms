<style>
    .floating-contact {
        position: fixed;
        bottom: 160px;
        left: 20px;
        z-index: 999;
    }

    /* Main Button */
    .fab-main {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        font-size: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        transition: 0.3s;
    }

    .fab-main:hover {
        transform: rotate(90deg) scale(1.05);
    }

    /* Options Container */
    .fab-options {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-bottom: 10px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: 0.3s ease;
    }

    /* Active state */
    .floating-contact.active .fab-options {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Individual buttons */
    .fab-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        margin: 6px 0;
        border-radius: 30px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        transition: 0.3s;
    }

    /* Hover effect */
    .fab-item:hover {
        transform: translateX(-5px);
    }

    /* Colors */
    .call {
        background: #007bff;
    }

    .email {
        background: #6c757d;
    }

    .whatsapp {
        background: #25D366;
    }

    /* Mobile tweak */
    @media (max-width: 768px) {
        .fab-main {
            width: 55px;
            height: 55px;
            font-size: 22px;
        }

        .fab-item span {
            display: none;
            /* icon only on mobile */
        }
    }
</style>
<div class="floating-contact">
    <div class="fab-main" id="fabToggle">
        ☰
    </div>

    <div class="fab-options">
        <a href="tel:+971566835512" class="fab-item call">
            📞 <span>Call</span>
        </a>

        <a href="mailto:sales@elegant-training.ae" class="fab-item email">
            ✉️ <span>Email</span>
        </a>

        <a href="https://wa.me/971566835512" target="_blank" class="fab-item whatsapp">
            🟢 <span>WhatsApp</span>
        </a>
    </div>
</div>

<script>
    const fab = document.getElementById('fabToggle');
    const container = document.querySelector('.floating-contact');

    fab.addEventListener('click', () => {
        container.classList.toggle('active');
    });
</script>