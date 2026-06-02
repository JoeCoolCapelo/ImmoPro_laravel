<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement - #{{ $transaction->id }}</title>
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
        .doc-title { text-align: center; font-size: 26px; font-weight: 900; color: #0f172a; margin: 30px 0; text-transform: uppercase; }
        
        .receipt-intro { background: #f8fafc; padding: 30px; border-radius: 20px; border: 1px solid #e2e8f0; margin-bottom: 30px; }
        .client-highlight { font-size: 22px; font-weight: 900; color: #6366f1; margin-top: 5px; display: block; }

        .details-grid { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-grid td { padding: 15px; border: 1px solid #f1f5f9; }
        .details-label { font-size: 9px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .details-value { font-size: 14px; font-weight: 900; color: #0f172a; }

        .main-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .main-table th { background: #f8fafc; text-align: left; padding: 15px; font-size: 10px; font-weight: 900; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #e2e8f0; }
        .main-table td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
        
        .total-box { margin-top: 40px; text-align: right; border-top: 2px solid #0f172a; padding-top: 20px; }
        .total-amount { font-size: 28px; font-weight: 900; color: #0f172a; }

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
                                <div style="font-size: 11px; color: #6366f1; font-weight: bold; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px;">Agent : {{ $transaction->agent?->name ?? __('Agent inconnu') }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="doc-type">
                    <div class="doc-badge">Reçu de Paiement Officiel</div>
                    <div style="font-size: 10px; margin-top: 5px; color: #94a3b8;">N° REÇU: #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="container">
        <div class="doc-title">Reçu de Paiement</div>

        <div class="receipt-intro">
            <span style="font-size: 12px; color: #64748b; font-weight: bold;">L'agence {{ $settings['agency_name'] ?? 'ImmoPro' }} certifie avoir reçu de la part de :</span>
            <span class="client-highlight">M./Mme {{ $transaction->client?->name ?? __('Client inconnu') }}</span>
        </div>

        <table class="details-grid">
            <tr>
                <td style="width: 70%;">
                    <span class="details-label">Désignation du Bien Immobilier</span>
                    @if($transaction->bien)
                        <div class="details-value">{{ $transaction->bien->titre }}</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">{{ $transaction->bien->adresse }}, {{ $transaction->bien->ville }} — Guinée</div>
                    @else
                        <div style="color: #e11d48; font-weight: bold;">[BIEN SUPPRIMÉ DU CATALOGUE]</div>
                    @endif
                </td>
                <td style="width: 30%; text-align: right;">
                    <span class="details-label">Type de Transaction</span>
                    <div class="details-value" style="color: #6366f1;">{{ strtoupper($transaction->type) }}</div>
                </td>
            </tr>
        </table>

        <table class="main-table">
            <thead>
                <tr>
                    <th>Description des Frais</th>
                    <th style="text-align: right;">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Prix Principal du Bien</strong><br>
                        Transaction validée le {{ $transaction->date_transaction->format('d/m/Y') }}
                    </td>
                    <td style="text-align: right; font-weight: 900;">{{ number_format($transaction->montant, 0, ',', ' ') }} GNF</td>
                </tr>
                <tr>
                    <td>
                        <strong>Frais d'Agence & Honoraires</strong><br>
                        Inclus dans le montant total ({{ number_format($transaction->commission_pourcentage, 1) }}%)
                    </td>
                    <td style="text-align: right; color: #64748b;">{{ number_format($transaction->commission_montant, 0, ',', ' ') }} GNF</td>
                </tr>
            </tbody>
        </table>

        <div class="total-box">
            <span class="details-label">Montant Total Encaissé</span>
            <div class="total-amount">{{ number_format($transaction->montant, 0, ',', ' ') }} <small style="font-size: 14px;">GNF</small></div>
            <div style="font-size: 11px; color: #64748b; font-style: italic; margin-top: 5px;">
                Arrêté la présente somme à : {{ number_format($transaction->montant, 0, ',', ' ') }} Francs Guinéens.
            </div>
        </div>

        <table style="width: 100%; margin-top: 60px;">
            <tr>
                <td style="width: 50%;">
                    <div class="details-label">Signature de l'Acquéreur</div>
                    <div style="height: 100px; border-bottom: 1px dashed #e2e8f0; width: 220px; margin-top: 10px;"></div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="details-label">Pour l'Agence ({{ $transaction->agent?->name ?? __('Agent inconnu') }})</div>
                    <div style="height: 100px; border-bottom: 1px dashed #e2e8f0; width: 220px; margin-top: 10px; margin-left: auto;"></div>
                    <div style="font-size: 10px; font-weight: 900; margin-top: 10px; color: #0f172a;">CACHET DE L'AGENCE</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-text">
            {{ $settings['agency_name'] ?? 'ImmoPro' }} — {{ $settings['agency_address'] ?? 'Conakry, Guinée' }} — Tél: {{ $settings['agency_phone'] ?? '' }}<br>
            Reçu officiel — Document à conserver précieusement pour toute démarche administrative.
        </div>
    </div>
</body>
</html>
