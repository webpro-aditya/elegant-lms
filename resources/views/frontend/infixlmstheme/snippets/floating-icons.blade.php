{{-- ════════════════════════════════════════════
     Floating Contact Widget  ·  Universal
     Works on any background — paste before </body>
════════════════════════════════════════════ --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap');

:root {
  --fc-blue:     #1e3c72;
  --fc-blue-mid: #2a5298;
  --fc-blue-lt:  #3b82f6;
  --fc-wa:       #22c55e;
  --fc-wa-dark:  #15803d;
  --fc-purple:   #7c3aed;
}

.fc-widget {
  position: fixed;
  bottom: 30px; right: 28px;
  z-index: 9999;
  display: flex; flex-direction: column; align-items: flex-end;
  font-family: 'Outfit', sans-serif;
}

/* Cards */
.fc-cards { display:flex; flex-direction:column; gap:8px; margin-bottom:12px; align-items:flex-end; }

.fc-card {
  position:relative; display:flex; align-items:center; gap:11px;
  padding:10px 15px 10px 10px; border-radius:18px;
  text-decoration:none; overflow:hidden;
  background:rgba(255,255,255,0.88);
  border:1px solid rgba(255,255,255,0.7);
  backdrop-filter:blur(20px) saturate(180%);
  -webkit-backdrop-filter:blur(20px) saturate(180%);
  box-shadow:
    0 2px 0 rgba(255,255,255,.6) inset,
    0 6px 24px rgba(30,60,114,.14),
    0 1px 4px rgba(0,0,0,.08);
  opacity:0; transform:translateX(22px) scale(.88); pointer-events:none;
  transition:opacity .38s cubic-bezier(.34,1.5,.64,1),transform .38s cubic-bezier(.34,1.5,.64,1),box-shadow .22s;
  will-change:transform,opacity;
}
.fc-card:nth-child(1){transition-delay:.02s}
.fc-card:nth-child(2){transition-delay:.08s}
.fc-card:nth-child(3){transition-delay:.14s}
.fc-widget.open .fc-card{opacity:1;transform:translateX(0) scale(1);pointer-events:auto}

.fc-card::before{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 38%,rgba(255,255,255,.55) 50%,transparent 62%);transform:translateX(-120%);transition:transform .5s;pointer-events:none}
.fc-card:hover::before{transform:translateX(120%)}

.fc-card::after{content:'';position:absolute;bottom:0;left:14px;right:14px;height:2.5px;border-radius:2px;transform:scaleX(0);transform-origin:left;transition:transform .3s cubic-bezier(.34,1.5,.64,1)}
.fc-card.wa-card::after    {background:linear-gradient(90deg,var(--fc-wa-dark),var(--fc-wa))}
.fc-card.email-card::after {background:linear-gradient(90deg,#5b21b6,var(--fc-purple),#a78bfa)}
.fc-card.call-card::after  {background:linear-gradient(90deg,var(--fc-blue),var(--fc-blue-lt))}
.fc-card:hover::after{transform:scaleX(1)}

.fc-card:hover{transform:translateX(-5px) scale(1.025);background:rgba(255,255,255,.97);box-shadow:0 2px 0 rgba(255,255,255,.8) inset,0 14px 40px rgba(30,60,114,.18),0 2px 6px rgba(0,0,0,.1)}

.fc-icon{width:40px;height:40px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform .3s cubic-bezier(.34,1.5,.64,1)}
.fc-card:hover .fc-icon{transform:scale(1.1) rotate(-6deg)}
.fc-icon svg{width:18px;height:18px;fill:#fff}

.wa-card    .fc-icon{background:linear-gradient(135deg,var(--fc-wa-dark),var(--fc-wa));   box-shadow:0 4px 14px rgba(34,197,94,.35)}
.email-card .fc-icon{background:linear-gradient(135deg,#5b21b6,var(--fc-purple));          box-shadow:0 4px 14px rgba(124,58,237,.35)}
.call-card  .fc-icon{background:linear-gradient(135deg,var(--fc-blue),var(--fc-blue-lt)); box-shadow:0 4px 14px rgba(30,60,114,.35)}

.fc-info{display:flex;flex-direction:column;min-width:116px}
.fc-label{font-size:10.5px;font-weight:400;color:#8899bb;line-height:1;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em}
.fc-value{font-size:13.5px;font-weight:600;color:#1a2644;line-height:1.3}

.fc-arrow{margin-left:auto;opacity:0;transform:translateX(-5px);transition:opacity .22s,transform .22s;color:#b0bdd8}
.fc-arrow svg{width:14px;height:14px}
.fc-card:hover .fc-arrow{opacity:1;transform:translateX(0)}

/* Main button */
.fc-trigger {
  position:relative; width:60px; height:60px; border-radius:20px;
  background:linear-gradient(145deg,#1e3c72 0%,#2a5298 55%,#3464c0 100%);
  border:none; color:#fff; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  outline:none; user-select:none; overflow:visible;
  box-shadow:
    0 1px 0 rgba(255,255,255,.18) inset,
    0 8px 28px rgba(30,60,114,.50),
    0 2px 6px rgba(0,0,0,.18),
    0 0 0 3px rgba(255,255,255,.12);
  transition:transform .3s cubic-bezier(.34,1.4,.64,1),box-shadow .3s;
}
.fc-trigger::before{content:'';position:absolute;inset:0;border-radius:inherit;background:linear-gradient(155deg,rgba(255,255,255,.22) 0%,transparent 52%);pointer-events:none}

/* Spinning ring — no background dependency */
.fc-ring{
  position:absolute; inset:-6px; border-radius:26px;
  border:2px solid transparent;
  background:
    linear-gradient(rgba(0,0,0,0),rgba(0,0,0,0)) padding-box,
    conic-gradient(from var(--a,0deg),transparent 0%,transparent 48%,rgba(99,160,255,.9) 56%,rgba(148,180,255,.7) 64%,transparent 72%,transparent 100%) border-box;
  opacity:0; transition:opacity .35s; pointer-events:none;
  animation:fc-spin 2.6s linear infinite;
}
@property --a{syntax:'<angle>';inherits:false;initial-value:0deg}
@keyframes fc-spin{to{--a:360deg}}
.fc-trigger:hover .fc-ring,.fc-widget.open .fc-ring{opacity:1}

.fc-trigger:hover{transform:scale(1.07) translateY(-2px);box-shadow:0 1px 0 rgba(255,255,255,.22) inset,0 14px 36px rgba(30,60,114,.58),0 4px 10px rgba(0,0,0,.22),0 0 0 3px rgba(255,255,255,.18)}
.fc-widget.open .fc-trigger{background:linear-gradient(145deg,#111827,#1e2d4a,#253660);box-shadow:0 1px 0 rgba(255,255,255,.12) inset,0 8px 28px rgba(5,10,30,.55),0 2px 6px rgba(0,0,0,.25),0 0 0 3px rgba(255,255,255,.08)}

.fc-icon-grid{display:grid;grid-template-columns:repeat(3,5px);grid-template-rows:repeat(3,5px);gap:3.5px}
.fc-dot{width:5px;height:5px;background:#fff;border-radius:1.5px;transition:transform .38s cubic-bezier(.34,1.5,.64,1),opacity .28s}
.fc-widget.open .fc-dot:nth-child(1){transform:translate(8.5px,8.5px) rotate(45deg) scaleX(2.3);border-radius:2px}
.fc-widget.open .fc-dot:nth-child(2){opacity:0;transform:scale(0)}
.fc-widget.open .fc-dot:nth-child(3){transform:translate(-8.5px,8.5px) rotate(-45deg) scaleX(2.3);border-radius:2px}
.fc-widget.open .fc-dot:nth-child(4){opacity:0;transform:scale(0)}
.fc-widget.open .fc-dot:nth-child(5){opacity:0;transform:scale(0)}
.fc-widget.open .fc-dot:nth-child(6){opacity:0;transform:scale(0)}
.fc-widget.open .fc-dot:nth-child(7){transform:translate(8.5px,-8.5px) rotate(-45deg) scaleX(2.3);border-radius:2px}
.fc-widget.open .fc-dot:nth-child(8){opacity:0;transform:scale(0)}
.fc-widget.open .fc-dot:nth-child(9){transform:translate(-8.5px,-8.5px) rotate(45deg) scaleX(2.3);border-radius:2px}

.fc-badge{position:absolute;top:-8px;right:-8px;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;font-size:9px;font-weight:700;padding:2px 7px;border-radius:20px;letter-spacing:.06em;text-transform:uppercase;box-shadow:0 2px 10px rgba(239,68,68,.5);border:2px solid rgba(255,255,255,.9);animation:fc-badgepulse 2.2s ease-in-out infinite;pointer-events:none}
@keyframes fc-badgepulse{0%,100%{transform:scale(1)}50%{transform:scale(1.1)}}
.fc-widget.open .fc-badge{display:none}

.fc-tip{position:absolute;right:calc(100% + 13px);top:50%;transform:translateY(-50%) translateX(5px);background:rgba(15,25,55,.92);color:rgba(255,255,255,.9);font-size:12px;font-weight:400;padding:6px 13px;border-radius:10px;white-space:nowrap;pointer-events:none;border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(10px);box-shadow:0 4px 18px rgba(0,0,0,.28);opacity:0;transition:opacity .2s,transform .2s}
.fc-tip::after{content:'';position:absolute;left:100%;top:50%;transform:translateY(-50%);border:5px solid transparent;border-left-color:rgba(15,25,55,.92)}
.fc-trigger:hover .fc-tip{opacity:1;transform:translateY(-50%) translateX(0)}
.fc-widget.open .fc-tip{opacity:0!important}

.fc-particles{position:absolute;inset:0;border-radius:inherit;pointer-events:none;overflow:visible}
.fc-particle{position:absolute;width:5px;height:5px;border-radius:50%;left:50%;top:50%;opacity:0}
.fc-widget.open .fc-particle{animation:fc-scatter .58s cubic-bezier(.25,.46,.45,.94) forwards}
.fc-particle:nth-child(1){background:#60a5fa;--dx:-38px;--dy:-38px;animation-delay:.00s}
.fc-particle:nth-child(2){background:#a78bfa;--dx:0;    --dy:-52px;animation-delay:.04s}
.fc-particle:nth-child(3){background:#34d399;--dx: 38px;--dy:-38px;animation-delay:.08s}
.fc-particle:nth-child(4){background:#fbbf24;--dx: 52px;--dy:0;    animation-delay:.04s}
.fc-particle:nth-child(5){background:#f472b6;--dx: 38px;--dy: 38px;animation-delay:.00s}
.fc-particle:nth-child(6){background:#60a5fa;--dx:0;    --dy: 52px;animation-delay:.04s}
.fc-particle:nth-child(7){background:#34d399;--dx:-38px;--dy: 38px;animation-delay:.08s}
.fc-particle:nth-child(8){background:#fb7185;--dx:-52px;--dy:0;    animation-delay:.04s}
@keyframes fc-scatter{0%{opacity:1;transform:translate(-50%,-50%) translate(0,0) scale(1)}70%{opacity:.8}100%{opacity:0;transform:translate(-50%,-50%) translate(var(--dx),var(--dy)) scale(0)}}

@media(max-width:600px){
  .fc-widget{bottom:18px;right:18px}
  .fc-trigger{width:54px;height:54px;border-radius:17px}
  .fc-info{display:none}
  .fc-card{padding:9px}
  .fc-icon{width:36px;height:36px;border-radius:11px}
  .fc-arrow{display:none}
}
</style>

<div class="fc-widget" id="fcWidget">
  <div class="fc-cards">

    <a href="https://wa.me/971566835512" target="_blank" class="fc-card wa-card">
      <div class="fc-icon">
        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.978-1.413A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2"/></svg>
      </div>
      <div class="fc-info">
        <span class="fc-label">Chat with us</span>
        <span class="fc-value">WhatsApp</span>
      </div>
      <div class="fc-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg></div>
    </a>

    <a href="mailto:sales@elegant-training.ae" class="fc-card email-card">
      <div class="fc-icon">
        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
      </div>
      <div class="fc-info">
        <span class="fc-label">Drop us a line</span>
        <span class="fc-value">Send Email</span>
      </div>
      <div class="fc-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg></div>
    </a>

    <a href="tel:+971566835512" class="fc-card call-card">
      <div class="fc-icon">
        <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
      </div>
      <div class="fc-info">
        <span class="fc-label">Talk to us now</span>
        <span class="fc-value">+971 56 683 5512</span>
      </div>
      <div class="fc-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg></div>
    </a>

  </div>

  <button class="fc-trigger" id="fcTrigger" aria-label="Contact us">
    <div class="fc-ring"></div>
    <div class="fc-particles">
      <div class="fc-particle"></div><div class="fc-particle"></div>
      <div class="fc-particle"></div><div class="fc-particle"></div>
      <div class="fc-particle"></div><div class="fc-particle"></div>
      <div class="fc-particle"></div><div class="fc-particle"></div>
    </div>
    <div class="fc-icon-grid">
      <div class="fc-dot"></div><div class="fc-dot"></div><div class="fc-dot"></div>
      <div class="fc-dot"></div><div class="fc-dot"></div><div class="fc-dot"></div>
      <div class="fc-dot"></div><div class="fc-dot"></div><div class="fc-dot"></div>
    </div>
    <div class="fc-badge">Live</div>
    <div class="fc-tip">Contact Us</div>
  </button>
</div>

<script>
  const fcWidget  = document.getElementById('fcWidget');
  const fcTrigger = document.getElementById('fcTrigger');

  fcTrigger.addEventListener('click', () => {
    fcWidget.classList.toggle('open');
    if (fcWidget.classList.contains('open')) {
      fcWidget.querySelectorAll('.fc-particle').forEach(p => {
        p.style.animation = 'none';
        void p.offsetWidth;
        p.style.animation = '';
      });
    }
  });

  document.addEventListener('click', e => {
    if (!fcWidget.contains(e.target)) fcWidget.classList.remove('open');
  });

  fcTrigger.addEventListener('mousemove', e => {
    const r  = fcTrigger.getBoundingClientRect();
    const dx = (e.clientX - (r.left + r.width  / 2)) * 0.26;
    const dy = (e.clientY - (r.top  + r.height / 2)) * 0.26;
    fcTrigger.style.transform = `translate(${dx}px,${dy}px) scale(1.06)`;
  });
  fcTrigger.addEventListener('mouseleave', () => { fcTrigger.style.transform = ''; });
</script>