/**
 * Terms & Conditions Modal
 * Shows on first visit. Stores acceptance in localStorage.
 */
(function() {
    const STORAGE_KEY = 'avery_terms_accepted';

    // Only show if not already accepted
    if (localStorage.getItem(STORAGE_KEY)) return;

    // Build the modal
    const overlay = document.createElement('div');
    overlay.id = 'terms-overlay';
    overlay.innerHTML = `
        <div class="terms-modal-box">
            <div class="terms-header">
                <i data-lucide="shield-check" style="width:32px;height:32px;color:var(--primary-color);"></i>
                <h2>Terms & Conditions</h2>
                <p class="terms-subtitle">Please read and accept before continuing</p>
            </div>
            <div class="terms-body">
                <div class="terms-section">
                    <h4><i data-lucide="car" style="width:18px;height:18px;"></i> Booking & Reservations</h4>
                    <ul>
                        <li>All bookings are subject to vehicle availability and must be confirmed via WhatsApp, Email, or the inquiry form.</li>
                        <li>A confirmed booking requires the passenger's full name, contact number, travel date, and destination.</li>
                        <li>Maximum capacity is <strong>7 passengers</strong> per trip.</li>
                    </ul>
                </div>
                <div class="terms-section">
                    <h4><i data-lucide="x-circle" style="width:18px;height:18px;"></i> Cancellation Policy</h4>
                    <ul>
                        <li><strong>Free cancellation</strong> up to 24 hours before the scheduled trip.</li>
                        <li>Cancellations within 24 hours may incur a <strong>50% cancellation fee</strong>.</li>
                        <li>No-shows will be charged the <strong>full fare</strong>.</li>
                    </ul>
                </div>
                <div class="terms-section">
                    <h4><i data-lucide="banknote" style="width:18px;height:18px;"></i> Payment Terms</h4>
                    <ul>
                        <li>Payment is accepted in <strong>Philippine Peso (₱)</strong> via cash, GCash, PayMaya, or PayPal.</li>
                        <li>A <strong>partial advance payment</strong> may be required for long-distance or multi-day trips.</li>
                        <li>Toll fees, parking fees, and driver meals on multi-day trips are shouldered by the passenger.</li>
                    </ul>
                </div>
                <div class="terms-section">
                    <h4><i data-lucide="shield" style="width:18px;height:18px;"></i> Safety & Liability</h4>
                    <ul>
                        <li>The vehicle is regularly maintained and insured for passenger safety.</li>
                        <li>Seatbelts must be worn by all passengers at all times.</li>
                        <li>Avery Maise Transport is not liable for delays caused by traffic, weather, or force majeure events.</li>
                        <li>Passengers are responsible for their personal belongings.</li>
                    </ul>
                </div>
                <div class="terms-section">
                    <h4><i data-lucide="clock" style="width:18px;height:18px;"></i> Waiting Time & Overtime</h4>
                    <ul>
                        <li>A complimentary <strong>30-minute waiting period</strong> is provided for airport pickups.</li>
                        <li>Additional waiting time beyond the agreed schedule may be charged at <strong>₱150/hour</strong>.</li>
                    </ul>
                </div>
                <div class="terms-section">
                    <h4><i data-lucide="lock" style="width:18px;height:18px;"></i> Privacy</h4>
                    <ul>
                        <li>Your personal information is used solely for booking and communication purposes.</li>
                        <li>We do not share, sell, or distribute your data to third parties.</li>
                    </ul>
                </div>
            </div>
            <div class="terms-footer">
                <button id="terms-accept-btn" class="btn btn-primary btn-full">
                    I Accept the Terms & Conditions
                </button>
                <p class="terms-note">By clicking "Accept", you agree to the terms above.</p>
            </div>
        </div>
    `;

    // Inject styles
    const style = document.createElement('style');
    style.textContent = `
        #terms-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            padding: 20px;
            animation: termsFadeIn 0.4s ease;
        }
        @keyframes termsFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .terms-modal-box {
            background: var(--surface-color, #fff);
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 24px;
            max-width: 600px;
            width: 100%;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px -12px rgba(0,0,0,0.5);
            overflow: hidden;
            animation: termsSlideUp 0.5s cubic-bezier(0.16,1,0.3,1);
        }
        @keyframes termsSlideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .terms-header {
            padding: 30px 30px 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color, #e5e7eb);
        }
        .terms-header h2 {
            font-size: 1.6rem;
            margin: 10px 0 5px;
        }
        .terms-subtitle {
            color: var(--text-secondary, #6b7280);
            font-size: 0.9rem;
        }
        .terms-body {
            padding: 25px 30px;
            overflow-y: auto;
            flex: 1;
        }
        .terms-section {
            margin-bottom: 20px;
        }
        .terms-section h4 {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
            margin-bottom: 10px;
            color: var(--text-color, #1f2937);
        }
        .terms-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .terms-section li {
            position: relative;
            padding-left: 18px;
            margin-bottom: 8px;
            font-size: 0.88rem;
            line-height: 1.6;
            color: var(--text-secondary, #6b7280);
        }
        .terms-section li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--primary-color, #6366f1);
            font-weight: 800;
        }
        .terms-footer {
            padding: 20px 30px 25px;
            border-top: 1px solid var(--border-color, #e5e7eb);
            text-align: center;
        }
        .terms-note {
            font-size: 0.78rem;
            color: var(--text-secondary, #6b7280);
            margin-top: 10px;
        }
        .btn-full {
            width: 100%;
            padding: 14px 24px;
            font-size: 1rem;
        }
    `;
    document.head.appendChild(style);

    // Wait for DOM, then inject
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inject);
    } else {
        inject();
    }

    function inject() {
        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        // Re-init lucide icons inside the modal
        if (window.lucide) lucide.createIcons();

        document.getElementById('terms-accept-btn').addEventListener('click', () => {
            localStorage.setItem(STORAGE_KEY, 'true');
            overlay.style.animation = 'termsFadeIn 0.3s ease reverse forwards';
            setTimeout(() => {
                overlay.remove();
                document.body.style.overflow = '';
            }, 300);
        });
    }
})();
