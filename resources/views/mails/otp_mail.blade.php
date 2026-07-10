<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking Confirmation – Gorakhpur Sleeping Pods Hotels</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body>

<div class="body-section">

    <div class="greeting">
        Hello,
    </div>

    <p class="intro-text">
        We received your request for <strong>Discount Verification</strong>.
        Please use the OTP below to continue. This OTP is valid for
        <strong>10 minutes</strong>.
    </p>

    <!-- OTP Card -->
    <div style="
        background:linear-gradient(135deg,#fffdf9,#f8f1e8);
        border:2px solid #d8b46a;
        border-radius:12px;
        padding:35px;
        text-align:center;
        margin:35px 0;
        box-shadow:0 10px 25px rgba(0,0,0,.08);
    ">

        <!-- Mobile -->
        <div style="
            color:#8b6b2e;
            text-transform:uppercase;
            letter-spacing:3px;
            font-size:12px;
            font-weight:bold;
        ">
            Registered Mobile
        </div>

        <div style="
            font-size:24px;
            color:#1a1410;
            font-weight:600;
            margin:12px 0 30px;
        ">
            📱 {{$mobile}}
        </div>

        <!-- Divider -->
        <div style="
            width:70px;
            height:3px;
            background:#c9a96e;
            margin:0 auto 30px;
            border-radius:5px;
        "></div>

        <!-- OTP Label -->
        <div style="
            color:#8b6b2e;
            text-transform:uppercase;
            letter-spacing:4px;
            font-size:13px;
            font-weight:bold;
        ">
            Your Verification OTP
        </div>

        <!-- OTP -->
        <div style="
            background:#1a1410;
            color:#f7d37a;
            display:inline-block;
            padding:18px 35px;
            margin:20px 0;
            border-radius:10px;
            font-size:42px;
            font-weight:bold;
            letter-spacing:12px;
            box-shadow:0 5px 15px rgba(0,0,0,.15);
        ">
            {{$otp}}
        </div>

        <br>

        <span style="
            display:inline-block;
            background:#d8b46a;
            color:#1a1410;
            padding:8px 18px;
            border-radius:30px;
            font-size:12px;
            font-weight:bold;
        ">
            ⏳ Valid for 10 Minutes
        </span>

    </div>

    <!-- Security Box -->
    <div style="
        background:#fff8eb;
        border-left:5px solid #d8b46a;
        padding:18px 22px;
        border-radius:6px;
        color:#555;
        line-height:1.8;
        font-size:14px;
    ">
        <strong style="color:#1a1410;">🔒 Security Notice</strong><br><br>

        • Never share this OTP with anyone.<br>
        • Our team will never ask for your OTP via phone or email.<br>
        • If you didn't request this verification, simply ignore this email.
    </div>

</div>
</body>
</html>
