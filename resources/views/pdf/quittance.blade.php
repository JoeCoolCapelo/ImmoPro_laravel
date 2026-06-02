<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quittance de Loyer - #{{ $transaction->id }}</title>
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
        .doc-badge { background: #10b981; color: white; padding: 8px 15px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }

        .container { padding: 40px; }
        .doc-title { text-align: center; font-size: 26px; font-weight: 900; color: #0f172a; margin: 30px 0; text-transform: uppercase; }
        
        .grid { width: 100%; border-collapse: separate; border-spacing: 20px 0; margin-left: -20px; margin-bottom: 30px; }
        .info-box { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; }
        .box-label { font-size: 9px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; }
        .box-value { font-size: 15px; font-weight: 900; color: #0f172a; }

        .main-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .main-table th { background: #f8fafc; text-align: left; padding: 15px; font-size: 10px; font-weight: 900; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #e2e8f0; }
        .main-table td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
        
        .amount-section { margin-top: 40px; text-align: right; background: #f8fafc; padding: 30px; border-radius: 20px; border: 1px solid #e2e8f0; }
        .amount-total { font-size: 24px; font-weight: 900; color: #6366f1; }
        .amount-words { font-size: 11px; color: #64748b; font-style: italic; margin-top: 10px; }

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
                                <div style="font-size: 11px; color: #6366f1; font-weight: bold; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px;">Gestionnaire : {{ $transaction->agent?->name ?? __('Agent inconnu') }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="doc-type">
                    <div class="doc-badge">Quittance de Loyer Libératoire</div>
                    <div style="font-size: 10px; margin-top: 5px; color: #94a3b8;">Réf: #TR-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="container">
        <div class="doc-title">Quittance de Loyer</div>

        <table class="grid">
            <tr>
                <td style="width: 50%;">
                    <div class="info-box">
                        <span class="box-label">Propriétaire / Bailleur</span>
                        <div class="box-value">{{ $transaction->bien->owner->name ?? 'N/A' }}</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 5px;">Géré par l'Agence {{ $settings['agency_name'] ?? 'ImmoPro' }}</div>
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="info-box">
                        <span class="box-label">Locataire</span>
                        <div class="box-value">{{ $transaction->client?->name ?? __('Client inconnu') }}</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 5px;">Contact: {{ $transaction->client?->email ?? 'N/A' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div style="margin: 30px 0;">
            <span class="box-label">Désignation du Bien Loué</span>
            @if($transaction->bien)
                <div style="font-size: 18px; font-weight: 900; color: #0f172a;">{{ $transaction->bien->titre }}</div>
                <div style="font-size: 13px; color: #64748b; margin-top: 5px;">Situé à : {{ $transaction->bien->adresse }}, {{ $transaction->bien->ville }} — Guinée</div>
            @else
                <div style="color: #e11d48; font-weight: bold;">[BIEN SUPPRIMÉ DU CATALOGUE]</div>
            @endif
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th>Description de la Période</th>
                    <th style="text-align: right;">Montant Payé</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Loyer Mensuel</strong><br>
                        Période du {{ $transaction->date_transaction->format('d/m/Y') }} au {{ $transaction->date_transaction->addMonth()->format('d/m/Y') }}
                    </td>
                    <td style="text-align: right; font-size: 18px; font-weight: 900;">{{ number_format($transaction->montant, 0, ',', ' ') }} GNF</td>
                </tr>
            </tbody>
        </table>

        <div class="amount-section">
            <div class="amount-total">Total Reçu : {{ number_format($transaction->montant, 0, ',', ' ') }} GNF</div>
            <div class="amount-words">
                Somme arrêtée à la présente quittance : {{ number_format($transaction->montant, 0, ',', ' ') }} Francs Guinéens.
            </div>
        </div>

        <table style="width: 100%; margin-top: 50px;">
            <tr>
                <td style="width: 50%;">
                    <div class="box-label">Signature du Locataire</div>
                    <div style="height: 100px; border-bottom: 1px dashed #e2e8f0; width: 220px;"></div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="box-label">Le Gestionnaire ({{ $transaction->agent?->name ?? __('Agent inconnu') }})</div>
                    <div style="height: 100px; border-bottom: 1px dashed #e2e8f0; width: 220px; margin-left: auto;"></div>
                    <div style="font-size: 10px; font-weight: 900; margin-top: 10px; color: #0f172a;">POUR L'AGENCE {{ $settings['agency_name'] ?? 'IMMOPRO' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-text">
            {{ $settings['agency_name'] ?? 'ImmoPro' }} — {{ $settings['agency_address'] ?? 'Conakry, Guinée' }} — Tél: {{ $settings['agency_phone'] ?? '' }}<br>
            Document officiel — Ne peut être contesté sans preuve de paiement bancaire ou cachet original.
        </div>
    </div>
</body>
</html>
