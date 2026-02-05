<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Artists Collaboration Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #000000 0%, #cc0000 100%);
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e3e6f0;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 20px;
        }
        .field-label {
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .field-value {
            background: #fff;
            padding: 12px 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
        }
        .message-box {
            background: #fff;
            padding: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            white-space: pre-wrap;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BOLDROOTS - ARTISTS COLLABORATION</h1>
    </div>
    
    <div class="content">
        <p>A new collaboration request has been submitted:</p>
        
        <div class="field">
            <div class="field-label">Full Name</div>
            <div class="field-value">{{ $first_name }} {{ $last_name }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Instagram / TikTok</div>
            <div class="field-value">{{ $social_handle }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Phone Number</div>
            <div class="field-value">{{ $phone }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Message</div>
            <div class="message-box">{{ $collab_message }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was sent from the BOLDROOTS Artists Collaboration form.</p>
    </div>
</body>
</html>
