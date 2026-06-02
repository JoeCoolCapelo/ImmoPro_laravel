<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contrat de {{ ucfirst($transaction->type) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .agency-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin: 0;
        }
        .agency-details {
            font-size: 12px;
            color: #64748b;
        }
        .contract-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 30px;
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .grid {
            width: 100%;
            margin-bottom: 10px;
        }
        .col-left {
            width: 30%;
            display: inline-block;
            font-weight: bold;
            color: #475569;
        }
        .col-right {
            width: 68%;
            display: inline-block;
        }
        .terms {
            font-size: 12px;
            text-align: justify;
            color: #475569;
            background-color: #f8fafc;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 40px;
        }
        .signatures {
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 31%;
            display: inline-block;
            vertical-align: top;
            border: 1px solid #cbd5e1;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            box-sizing: border-box;
        }
        .signature-spacing {
            margin-left: 2%;
        }
        .sign-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            color: #1e293b;
            margin-bottom: 10px;
        }
        .sign-img {
            max-width: 100%;
            height: 60px;
            margin: 10px 0;
            object-fit: contain;
        }
        .sign-date {
            font-size: 10px;
            color: #64748b;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #cbd5e1;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        @if(isset($settings['agency_logo']) && $settings['agency_logo'])
            @php
                $logoPath = storage_path('app/public/' . $settings['agency_logo']);
                if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    echo '<img src="'.$base64.'" class="logo">';
                }
            @endphp
        @endif
        <h1 class="agency-name">{{ $settings['agency_name'] ?? 'ImmoPro' }}</h1>
        <div class="agency-details">
            {{ $settings['contact_address'] ?? 'Adresse non configurée' }}<br>
            Tél: {{ $settings['contact_phone'] ?? 'N/A' }} | Email: {{ $settings['contact_email'] ?? 'N/A' }}
        </div>
    </div>

    <div class="contract-title">
        CONTRAT DE {{ strtoupper($transaction->type) }}
        <div style="font-size: 12px; font-weight: normal; margin-top: 5px;">Réf: #TR-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section">
        <div class="section-title">Les Parties</div>
        
        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; margin-bottom: 5px;">Le Propriétaire (Bailleur / Vendeur) :</div>
            <div class="grid">
                <span class="col-left">Nom complet :</span>
                <span class="col-right">{{ $transaction->bien->owner->name ?? 'N/A' }}</span>
            </div>
            <div class="grid">
                <span class="col-left">Contact :</span>
                <span class="col-right">{{ $transaction->bien->owner->email ?? 'N/A' }}</span>
            </div>
        </div>

        <div>
            <div style="font-weight: bold; margin-bottom: 5px;">Le Client (Preneur / Acquéreur) :</div>
            <div class="grid">
                <span class="col-left">Nom complet :</span>
                <span class="col-right">{{ $transaction->client->name ?? 'N/A' }}</span>
            </div>
            <div class="grid">
                <span class="col-left">Contact :</span>
                <span class="col-right">{{ $transaction->client->email ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Objet du Contrat</div>
        <div class="grid">
            <span class="col-left">Type de bien :</span>
            <span class="col-right">{{ ucfirst($transaction->bien->type_bien) }}</span>
        </div>
        <div class="grid">
            <span class="col-left">Désignation :</span>
            <span class="col-right">{{ $transaction->bien->titre }}</span>
        </div>
        <div class="grid">
            <span class="col-left">Localisation :</span>
            <span class="col-right">{{ $transaction->bien->adresse }}, {{ $transaction->bien->ville }}</span>
        </div>
        <div class="grid">
            <span class="col-left">Superficie :</span>
            <span class="col-right">{{ $transaction->bien->surface }} m²</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Conditions Financières</div>
        <div class="grid">
            <span class="col-left">Montant principal :</span>
            <span class="col-right" style="font-weight: bold; font-size: 16px;">{{ number_format($transaction->montant, 0, ',', ' ') }} GNF</span>
        </div>
        <div class="grid">
            <span class="col-left">Date d'effet :</span>
            <span class="col-right">{{ $transaction->date_transaction->format('d/m/Y') }}</span>
        </div>
        @if($transaction->type === 'location' && $transaction->date_fin_occupation)
        <div class="grid">
            <span class="col-left">Fin de bail prevue :</span>
            <span class="col-right">{{ $transaction->date_fin_occupation->format('d/m/Y') }}</span>
        </div>
        @endif
        <div class="grid">
            <span class="col-left">Agent intermédiaire :</span>
            <span class="col-right">{{ $transaction->agent->name ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Dispositions Générales</div>
        <div class="terms">
            <p><strong>Article 1 :</strong> Les parties déclarent que les informations fournies ci-dessus sont exactes et acceptent d'être liées par les termes du présent contrat généré via la plateforme {{ $settings['agency_name'] ?? 'ImmoPro' }}.</p>
            <p><strong>Article 2 :</strong> La signature numérique apposée ci-dessous par les deux parties vaut consentement explicite et a la même valeur juridique qu'une signature manuscrite traditionnelle, conformément à la réglementation en vigueur.</p>
            <p><strong>Article 3 :</strong> {{ $settings['agency_name'] ?? 'ImmoPro' }} agit en tant que facilitateur et intermédiaire, attestant par la présente de l'accord mutuel entre le Propriétaire et le Client, constaté et enregistré dans notre système sécurisé.</p>
        </div>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <div class="sign-title">Le Propriétaire</div>
            <div class="sign-date">Nom : {{ $transaction->bien->owner->name ?? 'N/A' }}</div>
            
            @if($transaction->owner_signed && $transaction->owner_signature_image)
                <img src="{{ $transaction->owner_signature_image }}" class="sign-img" alt="Signature Propriétaire">
                <div class="sign-date">Signé le :<br>{{ $transaction->owner_signed_at->format('d/m/Y à H:i') }}</div>
                <div class="sign-date">IP: {{ $transaction->signature_ip }}</div>
            @else
                <div style="height: 60px; margin: 10px 0; color: #94a3b8; font-style: italic;">(En attente)</div>
            @endif
        </div>

        <div class="signature-box signature-spacing">
            <div class="sign-title">Le Client</div>
            <div class="sign-date">Nom : {{ $transaction->client->name ?? 'N/A' }}</div>
            
            @if($transaction->client_signed && $transaction->client_signature_image)
                <img src="{{ $transaction->client_signature_image }}" class="sign-img" alt="Signature Client">
                <div class="sign-date">Signé le :<br>{{ $transaction->client_signed_at->format('d/m/Y à H:i') }}</div>
                <div class="sign-date">IP: {{ $transaction->signature_ip }}</div>
            @else
                <div style="height: 60px; margin: 10px 0; color: #94a3b8; font-style: italic;">(En attente)</div>
            @endif
        </div>

        <div class="signature-box signature-spacing">
            <div class="sign-title">L'Agence</div>
            <div class="sign-date">Nom : {{ $settings['agency_name'] ?? 'ImmoPro' }}</div>
            
            @if($transaction->agency_signed && $transaction->agency_signature_image)
                <img src="{{ $transaction->agency_signature_image }}" class="sign-img" alt="Signature Agence">
                <div class="sign-date">Signé le :<br>{{ $transaction->agency_signed_at->format('d/m/Y à H:i') }}</div>
                <div class="sign-date">IP: {{ $transaction->signature_ip }}</div>
            @else
                <div style="height: 60px; margin: 10px 0; color: #94a3b8; font-style: italic;">(En attente)</div>
            @endif
        </div>
    </div>

    <div class="footer">
        Document généré automatiquement par {{ $settings['agency_name'] ?? 'ImmoPro' }} le {{ now()->format('d/m/Y à H:i') }}.<br>
        Ce document constitue une preuve numérique de l'accord intervenu entre les parties.
    </div>

</body>
</html>
