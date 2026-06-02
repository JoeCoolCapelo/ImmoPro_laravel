<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez {{ $agencyName }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: sans-serif;
            color: #334155;
            border-radius: 24px;
            overflow: hidden;
            margin-top: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 20px;
        }
        .content {
            padding: 40px 30px;
        }
        h1 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 20px;
            letter-spacing: -0.025em;
            line-height: 1.2;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 20px;
        }
        .cta-container {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .button {
            background-color: #4f46e5;
            color: #ffffff !important;
            padding: 18px 36px;
            text-decoration: none;
            border-radius: 16px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }
        .footer {
            background-color: #f1f5f9;
            padding: 40px 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .feature-card {
            background-color: #f8fafc;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #f1f5f9;
        }
        .feature-title {
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 30px 0;
        }
        
        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .main {
                width: 95% !important;
                margin-top: 20px !important;
                border-radius: 16px !important;
            }
            .content {
                padding: 30px 20px !important;
            }
            .feature-column {
                width: 100% !important;
                display: block !important;
                margin-right: 0 !important;
                margin-bottom: 20px !important;
            }
            h1 {
                font-size: 22px !important;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <div style="margin-bottom: 15px;">
                        @if($logo)
                            <div style="width: 80px; height: 80px; margin: 0 auto; background: #ffffff; border-radius: 50%; padding: 10px; box-shadow: 0 10px 15px rgba(0,0,0,0.2);">
                                <img src="{{ $logo }}" alt="{{ $agencyName }}" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                    <div style="color: #ffffff; font-size: 24px; font-weight: 900; letter-spacing: 4px; text-transform: uppercase; margin-top: 10px;">{{ $agencyName }}</div>
                    <div style="color: #94a3b8; font-size: 11px; font-weight: bold; letter-spacing: 5px; margin-top: 5px; text-transform: uppercase;">Real Estate Excellence</div>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h1>Bienvenue, {{ $userName }} !</h1>
                    <p>C'est un honneur de vous accueillir chez <strong>{{ $agencyName }}</strong>. Vous venez de franchir une étape clé vers la réalisation de vos projets immobiliers les plus ambitieux.</p>
                    
                    <div class="divider"></div>
                    
                    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: separate; border-spacing: 0;">
                        <tr>
                            <td class="feature-column" style="width: 48%; vertical-align: top; padding-bottom: 20px;">
                                <div class="feature-card">
                                    <div style="font-size: 32px; margin-bottom: 12px;">💎</div>
                                    <div class="feature-title">Accès Premium</div>
                                    <div style="font-size: 13px; color: #64748b; line-height: 1.5;">Explorez des propriétés exclusives non listées sur le marché public.</div>
                                </div>
                            </td>
                            <td class="feature-column" style="width: 4%; line-height: 1px; font-size: 1px;">&nbsp;</td>
                            <td class="feature-column" style="width: 48%; vertical-align: top; padding-bottom: 20px;">
                                <div class="feature-card">
                                    <div style="font-size: 32px; margin-bottom: 12px;">⚡</div>
                                    <div class="feature-title">Rapidité & Suivi</div>
                                    <div style="font-size: 13px; color: #64748b; line-height: 1.5;">Notifications instantanées et suivi digital de vos dossiers 24/7.</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="feature-column" style="width: 48%; vertical-align: top; padding-bottom: 20px;">
                                <div class="feature-card">
                                    <div style="font-size: 32px; margin-bottom: 12px;">🤝</div>
                                    <div class="feature-title">Conseil Expert</div>
                                    <div style="font-size: 13px; color: #64748b; line-height: 1.5;">Un interlocuteur dédié pour vous accompagner à chaque étape.</div>
                                </div>
                            </td>
                            <td class="feature-column" style="width: 4%; line-height: 1px; font-size: 1px;">&nbsp;</td>
                            <td class="feature-column" style="width: 48%; vertical-align: top; padding-bottom: 20px;">
                                <div class="feature-card">
                                    <div style="font-size: 32px; margin-bottom: 12px;">📄</div>
                                    <div class="feature-title">Zéro Papier</div>
                                    <div style="font-size: 13px; color: #64748b; line-height: 1.5;">Signature et gestion de documents entièrement dématérialisées.</div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div class="cta-container" style="margin-top: 40px;">
                        <a href="{{ $dashboardUrl }}" class="button">Découvrir mon Tableau de Bord</a>
                    </div>

                    <p style="font-size: 14px; text-align: center; color: #94a3b8; font-weight: 500;">
                        Propulsé par la technologie {{ $agencyName }} Core™
                    </p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <div class="footer-text">Contactez-nous</div>
                    <p style="font-size: 13px; margin-bottom: 5px;">{{ $address }}</p>
                    <p style="font-size: 13px; margin-bottom: 5px;">{{ $phone }} | {{ $email }}</p>
                    
                    <div class="social-links">
                        <a href="https://wa.me/224625997903">WhatsApp</a>
                        <a href="#">Site Web</a>
                    </div>
                    
                    <div style="margin-top: 20px; font-size: 11px; color: #cbd5e1;">
                        &copy; {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
