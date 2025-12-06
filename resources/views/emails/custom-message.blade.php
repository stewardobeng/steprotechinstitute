<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            color: #333333;
            margin-bottom: 20px;
        }
        .message-content {
            font-size: 15px;
            color: #555555;
            line-height: 1.8;
            white-space: pre-wrap;
            margin-bottom: 30px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
            font-size: 13px;
            color: #6c757d;
        }
        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ \App\Models\Setting::getValue('app_name', config('app.name', 'StepProClass')) }}</h1>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                <strong>Hello {{ $userName }},</strong>
            </div>
            
            <div class="divider"></div>
            
            <div class="message-content">
{{ $content }}
            </div>
            
            <div class="divider"></div>
            
            <p style="font-size: 14px; color: #6c757d; margin-top: 30px;">
                Best regards,<br>
                <strong>{{ \App\Models\Setting::getValue('app_name', config('app.name', 'StepProClass')) }} Team</strong>
            </p>
        </div>
        
        <div class="email-footer">
            <p><strong>{{ \App\Models\Setting::getValue('app_name', config('app.name', 'StepProClass')) }}</strong></p>
            <p>{{ \App\Models\Setting::getValue('app_name', config('app.name', 'StepProClass')) }} - Professional Certification Program</p>
            <p>
                <a href="{{ config('app.url') }}">Visit our website</a> | 
                <a href="{{ config('app.url') }}/dashboard">Access Dashboard</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #adb5bd;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>

