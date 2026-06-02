<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de Visite - #{{ $visite->id }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; margin: 0; padding: 0; background: #ffffff; }
        
        /* Header */
        .header { background: #0f172a; color: white; padding: 40px; }
        .header-content { width: 100%; border-collapse: collapse; }
        .agency-info { width: 60%; }
        .agency-name { font-size: 28px; font-weight: 900; letter-spacing: -1px; margin-bottom: 5px; color: #6366f1; }
        .agency-director { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #94a3b8; font-weight: bold; }
        .doc-type { width: 40%; text-align: right; vertical-align: bottom; }
        .doc-badge { background: #6366f1; color: white; padding: 8px 15px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }

        .container { padding: 40px; }
        .doc-title { text-align: center; font-size: 24px; font-weight: 900; color: #0f172a; margin: 30px 0; text-transform: uppercase; letter-spacing: 1px; }
        
        .info-box { background: #f8fafc; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0; margin-bottom: 30px; position: relative; }
        .box-label { position: absolute; top: -10px; left: 20px; background: #6366f1; color: white; padding: 2px 10px; font-size: 9px; font-weight: 900; border-radius: 4px; text-transform: uppercase; }
        
        .clause { font-size: 11px; color: #64748b; margin-top: 30px; text-align: justify; border: 2px dashed #e2e8f0; padding: 20px; border-radius: 12px; line-height: 1.6; }
        .clause strong { color: #0f172a; }

        .signature-area { margin-top: 50px; width: 100%; border-collapse: collapse; }
        .signature-box { width: 50%; padding: 20px; }
        .sig-line { border-bottom: 1px solid #cbd5e1; height: 120px; margin-top: 10px; }
        .sig-label { font-size: 11px; font-weight: 900; text-transform: uppercase; color: #64748b; }

        .footer { position: fixed; bottom: 0; width: 100%; background: #f8fafc; padding: 20px 40px; border-top: 1px solid #e2e8f0; text-align: center; }
        .footer-text { font-size: 10px; color: #94a3b8; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-content">
            <tr>
                <td class="agency-info">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            @if(isset($settings['agency_logo']))
                            <td style="width: 80px; padding-right: 20px;">
                                <img src="{{ public_path('storage/' . $settings['agency_logo']) }}" style="max-height: 70px; max-width: 80px; border-radius: 10px;">
                            </td>
                            @endif
                            <td>
                                <div class="agency-name">{{ $settings['agency_name'] ?? 'ImmoPro' }}</div>
                                <div class="agency-director">Dir. Général : {{ $settings['agency_director'] ?? 'M. Mohamed SYLLA' }}</div>
                                <div style="font-size: 11px; color: #6366f1; font-weight: bold; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px;">Agent : {{ $visite->bien->agent->name ?? 'Agent ImmoPro' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="doc-type">
                    <div class="doc-badge">Document Officiel d'Accompagnement</div>
                    <div style="font-size: 10px; margin-top: 5px; color: #94a3b8;">N° VISITE: #{{ str_pad($visite->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="container">
        <div class="doc-title">Bon de Visite Immobilière</div>

        <p style="font-size: 13px; color: #475569; margin-bottom: 30px;">
            Le présent document atteste que l'agence <strong>{{ $settings['agency_name'] ?? 'ImmoPro' }}</strong> a fait visiter ce jour, le {{ $visite->date_visite->format('d/m/Y') }} à {{ $visite->date_visite->format('H:i') }}, le bien immobilier désigné ci-après à :
        </p>

        <div class="info-box">
            <span class="box-label">Informations Client</span>
            <div style="font-size: 18px; font-weight: 900; color: #0f172a;">{{ $visite->client?->name ?? __('Client inconnu') }}</div>
            <div style="font-size: 13px; color: #64748b; margin-top: 5px;">Contact: {{ $visite->client?->email ?? 'N/A' }}</div>
        </div>

        <div class="info-box">
            <span class="box-label">Désignation du Bien</span>
            @if($visite->bien)
                <div style="font-size: 18px; font-weight: 900; color: #6366f1;">{{ $visite->bien->titre }}</div>
                <div style="font-size: 13px; color: #0f172a; margin-top: 5px; font-weight: bold;">{{ $visite->bien->adresse }}, {{ $visite->bien->ville }}</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 5px;">Nature : {{ ucfirst($visite->bien->nature) }} | Superficie : {{ $visite->bien->surface }} m²</div>
            @else
                <div style="color: #e11d48; font-weight: bold;">[PROPRIÉTÉ SUPPRIMÉE DU CATALOGUE]</div>
            @endif
        </div>

        <div class="info-box">
            <span class="box-label">Agent Accompagnateur</span>
            <div style="font-size: 14px; font-weight: 900; color: #0f172a;">{{ $visite->bien->agent->name ?? 'Agent ImmoPro' }}</div>
            <div style="font-size: 11px; color: #64748b;">Représentant officiel ImmoPro</div>
        </div>

        <div class="clause">
            <strong>ENGAGEMENT DU VISITEUR :</strong> En signant ce bon, le visiteur reconnaît avoir pris connaissance du bien par l'intermédiaire exclusif de l'agence <strong>{{ $settings['agency_name'] ?? 'ImmoPro' }}</strong>. Il s'engage formellement à ne pas traiter l'acquisition ou la location de ce bien, directement ou par un autre intermédiaire, sans le concours de la présente agence, sous peine de dommages et intérêts.
        </div>

        <table class="signature-area">
            <tr>
                <td class="signature-box">
                    <div class="sig-label">Signature du Visiteur</div>
                    <div style="font-size: 9px; color: #94a3b8;">"Lu et approuvé"</div>
                    <div class="sig-line"></div>
                </td>
                <td class="signature-box">
                    <div class="sig-label" style="text-align: right;">Cachet & Visa Agence</div>
                    <div class="sig-line" style="margin-left: auto;"></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-text">
            {{ $settings['agency_name'] ?? 'ImmoPro' }} — {{ $settings['agency_address'] ?? 'Conakry, Guinée' }} — {{ $settings['agency_phone'] ?? '' }}<br>
            Document généré automatiquement par la plateforme ImmoPro.
        </div>
    </div>
</body>
</html>
