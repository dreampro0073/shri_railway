<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking Confirmation – Gorakhpur Sleeping Pods Hotels</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background-color: #f4f0ea;
      font-family: 'Jost', sans-serif;
      font-weight: 300;
      color: #2c2c2c;
      padding: 40px 16px;
    }

    .email-wrapper {
      max-width: 620px;
      margin: 0 auto;
      background: #fffdf9;
      border: 1px solid #ddd5c4;
      box-shadow: 0 8px 40px rgba(0,0,0,0.07);
    }

    /* ── HEADER ── */
    .header {
      background: #1a1410;
      padding: 48px 40px 36px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .header::before {
      content: '';
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 40px,
        rgba(255,255,255,0.015) 40px,
        rgba(255,255,255,0.015) 80px
      );
    }
    .header-ornament {
      font-family: 'Cormorant Garamond', serif;
      font-size: 11px;
      letter-spacing: 5px;
      color: #c9a96e;
      text-transform: uppercase;
      margin-bottom: 14px;
    }
    .hotel-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 42px;
      font-weight: 300;
      color: #fffdf9;
      line-height: 1.1;
      letter-spacing: 2px;
    }
    .hotel-tagline {
      font-size: 11px;
      letter-spacing: 4px;
      color: #8a7a65;
      text-transform: uppercase;
      margin-top: 10px;
    }
    .divider-gold {
      width: 60px;
      height: 1px;
      background: linear-gradient(to right, transparent, #c9a96e, transparent);
      margin: 20px auto 0;
    }

    /* ── CONFIRMATION BADGE ── */
    .badge-section {
      background: #c9a96e;
      padding: 20px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .badge-label {
      font-size: 10px;
      letter-spacing: 4px;
      text-transform: uppercase;
      color: #6b4f1e;
    }
    .badge-number {
      font-family: 'Cormorant Garamond', serif;
      font-size: 22px;
      font-weight: 600;
      color: #1a1410;
      letter-spacing: 2px;
    }
    .badge-status {
      font-size: 10px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #1a1410;
      background: rgba(26,20,16,0.12);
      padding: 6px 14px;
      border: 1px solid rgba(26,20,16,0.25);
    }

    /* ── BODY ── */
    .body-section {
      padding: 48px 40px 40px;
    }
    .greeting {
      font-family: 'Cormorant Garamond', serif;
      font-size: 28px;
      font-weight: 300;
      font-style: italic;
      color: #1a1410;
      margin-bottom: 16px;
    }
    .intro-text {
      font-size: 14px;
      line-height: 1.8;
      color: #5a5040;
      margin-bottom: 36px;
    }
    .intro-text strong {
      color: #1a1410;
      font-weight: 500;
    }

    /* ── DETAILS BLOCK ── */
    .details-block {
      border: 1px solid #ddd5c4;
      margin-bottom: 32px;
    }
    .details-header {
      background: #f4f0ea;
      padding: 14px 24px;
      font-size: 10px;
      letter-spacing: 4px;
      text-transform: uppercase;
      color: #8a7a65;
      border-bottom: 1px solid #ddd5c4;
    }
    .details-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
    }
    .detail-cell {
      padding: 20px 24px;
      border-bottom: 1px solid #ede8df;
    }
    .detail-cell:nth-child(odd) {
      border-right: 1px solid #ede8df;
    }
    .detail-cell:last-child,
    .detail-cell:nth-last-child(2):nth-child(odd) {
      border-bottom: none;
    }
    .detail-cell:nth-last-child(2):not(:nth-child(odd)) {
      border-bottom: none;
    }
    .detail-label {
      font-size: 10px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #c9a96e;
      margin-bottom: 6px;
    }
    .detail-value {
      font-family: 'Cormorant Garamond', serif;
      font-size: 19px;
      font-weight: 400;
      color: #1a1410;
    }
    .detail-sub {
      font-size: 12px;
      color: #8a7a65;
      margin-top: 3px;
    }

    /* ── ROOM HIGHLIGHT ── */
    .room-highlight {
      background: #1a1410;
      padding: 28px 30px;
      margin-bottom: 32px;
      display: flex;
      gap: 24px;
      align-items: flex-start;
    }
    .room-icon {
      width: 48px;
      height: 48px;
      border: 1px solid #c9a96e;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 22px;
    }
    .room-info-label {
      font-size: 10px;
      letter-spacing: 4px;
      text-transform: uppercase;
      color: #8a7a65;
      margin-bottom: 6px;
    }
    .room-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 24px;
      font-weight: 300;
      color: #fffdf9;
      margin-bottom: 8px;
    }
    .room-amenities {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }
    .amenity-tag {
      font-size: 10px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #c9a96e;
      border: 1px solid rgba(201,169,110,0.35);
      padding: 4px 10px;
    }

    /* ── PRICE SUMMARY ── */
    .price-summary {
      border: 1px solid #ddd5c4;
      margin-bottom: 36px;
    }
    .price-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 24px;
      border-bottom: 1px solid #ede8df;
      font-size: 13px;
      color: #5a5040;
    }
    .price-row:last-child { border-bottom: none; }
    .price-row.total {
      background: #f4f0ea;
      font-weight: 500;
      color: #1a1410;
    }
    .price-row.total .amount {
      font-family: 'Cormorant Garamond', serif;
      font-size: 24px;
      font-weight: 600;
      color: #1a1410;
    }

    /* ── IMPORTANT NOTE ── */
    .note-box {
      border-left: 3px solid #c9a96e;
      padding: 16px 20px;
      background: #faf7f2;
      margin-bottom: 36px;
      font-size: 13px;
      line-height: 1.7;
      color: #5a5040;
    }
    .note-box strong {
      color: #1a1410;
      font-weight: 500;
      display: block;
      margin-bottom: 4px;
    }

    /* ── CTA ── */
    .cta-section {
      text-align: center;
      margin-bottom: 40px;
    }
    .cta-button {
      display: inline-block;
      background: #1a1410;
      color: #c9a96e;
      text-decoration: none;
      font-size: 11px;
      letter-spacing: 4px;
      text-transform: uppercase;
      padding: 16px 40px;
      border: 1px solid #1a1410;
      transition: all 0.2s;
    }
    .cta-secondary {
      display: inline-block;
      margin-left: 16px;
      font-size: 11px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #8a7a65;
      text-decoration: none;
      border-bottom: 1px solid #ddd5c4;
      padding-bottom: 2px;
    }

    /* ── FOOTER ── */
    .footer {
      background: #1a1410;
      padding: 36px 40px;
      text-align: center;
    }
    .footer-ornament {
      font-family: 'Cormorant Garamond', serif;
      font-size: 28px;
      color: #c9a96e;
      margin-bottom: 16px;
      letter-spacing: 4px;
    }
    .footer-links {
      display: flex;
      justify-content: center;
      gap: 24px;
      margin-bottom: 20px;
    }
    .footer-links a {
      font-size: 10px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #8a7a65;
      text-decoration: none;
    }
    .footer-contact {
      font-size: 12px;
      color: #5a5040;
      line-height: 1.8;
    }
    .footer-contact a {
      color: #c9a96e;
      text-decoration: none;
    }
    .footer-legal {
      margin-top: 20px;
      font-size: 10px;
      color: #3d3530;
      line-height: 1.7;
    }
  </style>
</head>
<body>

<div class="email-wrapper">


  <div class="header">
    <div class="hotel-name">Booking Confirmation – Gorakhpur Sleeping Pods Hotels</div>
    <div class="hotel-tagline">A Legacy of Timeless Hospitality</div>
    <div class="divider-gold"></div>
  </div>


  <div class="badge-section">
    <div>
      <div class="badge-label">Booking Reference</div>
      <div class="badge-number">{{$entry->unique_id}}</div>
    </div>
    <div class="badge-status">✓ Confirmed</div>
  </div>

  <div class="body-section">

    <div class="greeting">Dear {{$entry->name}},</div>
    <p class="intro-text">
      Your reservation at <strong>Gorakhpur Sleeping Pods Hotels, Gorakhpur</strong> has been confirmed.
      We are delighted to welcome you and have prepared everything to ensure your stay
      is nothing short of extraordinary. Your booking details are outlined below for your records.
    </p>


    <div class="details-block">
      <div class="details-header">Reservation Details</div>
      <div class="details-grid">
        <div class="detail-cell">
          <div class="detail-label">Check-In</div>
          <div class="detail-value">{{date("d F Y",strtotime($entry->checkin_date))}}</div>
          <div class="detail-sub">From {{date("H:i A",strtotime($entry->checkin_date))}}</div>
        </div>
        <div class="detail-cell">
          <div class="detail-label">Check-Out</div>
          <div class="detail-value">{{date("d F Y",strtotime($entry->checkout_date))}}</div>
          <div class="detail-sub">Until {{date("H:i A",strtotime($entry->checkout_date))}}</div>
        </div>
        <div class="detail-cell">
          <div class="detail-label">Duration</div>
          <div class="detail-value">{{$entry->hours_occ}} Hours</div>
          <div class="detail-sub">{{date("l",strtotime($entry->checkin_date))}}</div>
        </div>
        <div class="detail-cell">
          <div class="detail-label">Guests</div>
          <div class="detail-value">{{sizeof($availableIds)}}</div>
          <div class="detail-sub">Room</div>
        </div>
      </div>
    </div>  
    <div class="note-box">
      <strong>Important Information</strong>
      Please carry a valid government-issued photo ID at check-in.
      Early check-in is subject to availability. Cancellations made within 48 hours of arrival
      <!-- are non-refundable. Complimentary airport transfers can be arranged — contact our concierge
      at least 24 hours in advance. -->
    </div>

    
    <!-- <div class="cta-section">
      <a href="#" class="cta-button">Manage My Booking</a>
      <a href="#" class="cta-secondary">Add Special Requests</a>
    </div> -->

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-ornament">❧</div>
    <!-- <div class="footer-links">
      <a href="#">Website</a>
      <a href="#">Spa &amp; Wellness</a>
      <a href="#">Dining</a>
      <a href="#">Concierge</a>
    </div> -->
    <div class="footer-contact">
      Sleeping Pod Hotel, Gorakhpur Railway Station, Platform No. 9<br/>
      <a href="tel:+91-9369023506">+91-9369023506</a> &nbsp;·&nbsp;
      <a href="mailto:msnnhp11@gmail.com">msnnhp11@gmail.com</a>
    </div>
    <div class="footer-legal">
      This email was sent to {{$entry->email_id}} as a booking confirmation.<br/>
      © 2026 Gorakhpur Sleeping Pods Hotels. &nbsp;|&nbsp;
    </div>
  </div>

</div>
</body>
</html>
