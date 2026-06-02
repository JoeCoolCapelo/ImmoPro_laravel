<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Financier - {{ $settings['agency_name'] ?? 'ImmoPro' }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; line-height: 1.5; font-size: 10pt; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24pt; font-weight: bold; color: #4f46e5; letter-spacing: -1px; }
        .report-title { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin-top: 10px; color: #0f172a; }
        .meta { color: #64748b; font-size: 9pt; }
        
        .summary-grid { width: 100%; margin-bottom: 30px; }
        .summary-card { background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .summary-label { font-size: 8pt; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 5px; }
        .summary-value { font-size: 14pt; font-weight: bold; color: #0f172a; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f1f5f9; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 7pt; text-align: left; padding: 10px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 10px; border-bottom: 1px solid #f1f5f9; font-size: 8pt; }
        .status { padding: 2px 6px; border-radius: 4px; font-size: 7pt; font-weight: bold; text-transform: uppercase; }
        .status-validée { background: #dcfce7; color: #166534; }
        .status-en_attente { background: #fef3c7; color: #92400e; }
        
        .footer { position: fixed; bottom: 0; width: 100%; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 8pt; color: #94a3b8; text-align: center; }
        .director-sign { margin-top: 50px; text-align: right; }
        .sign-box { border-bottom: 1px solid #000; width: 200px; display: inline-block; height: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="border:none; margin-bottom: 0;">
            <tr style="border:none;">
                <td style="border:none; padding:0;">
                    <div class="logo">{{ $settings['agency_name'] ?? 'ImmoPro' }}</div>
                    <div class="report-title">Rapport Financier Global</div>
                </td>
                <td style="border:none; padding:0; text-align: right;">
                    <div class="meta">Date d'édition : {{ date('d/m/Y H:i') }}</div>
                    <div class="meta">Période : Jusqu'au {{ date('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="summary-grid" style="border:none;">
        <tr style="border:none;">
            <td style="border:none; padding-left: 0;">
                <div class="summary-card">
                    <div class="summary-label">Volume de Ventes</div>
                    <div class="summary-value">{{ number_format($stats['total_volume'], 0, ',', ' ') }} GNF</div>
                </div>
            </td>
            <td style="border:none;">
                <div class="summary-card" style="border-color: #4f46e5; background: #eef2ff;">
                    <div class="summary-label" style="color: #4f46e5;">Revenus Agence (Commissions)</div>
                    <div class="summary-value" style="color: #4f46e5;">{{ number_format($stats['total_commissions'], 0, ',', ' ') }} GNF</div>
                </div>
            </td>
            <td style="border:none; padding-right: 0;">
                <div class="summary-card">
                    <div class="summary-label">Transactions Totales</div>
                    <div class="summary-value">{{ $transactions->count() }}</div>
                </div>
            </td>
        </tr>
    </table>

    <h3>Détails des Transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Réf</th>
                <th>Bien</th>
                <th>Nature</th>
                <th>Client</th>
                <th>Montant (GNF)</th>
                <th>Comm (GNF)</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>#TR-{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $t->bien->titre ?? 'N/A' }}</strong></td>
                    <td>{{ strtoupper($t->type) }}</td>
                    <td>{{ $t->client->name ?? 'N/A' }}</td>
                    <td>{{ number_format($t->montant, 0, ',', ' ') }}</td>
                    <td>{{ number_format($t->commission_montant, 0, ',', ' ') }}</td>
                    <td>{{ $t->date_transaction ? $t->date_transaction->format('d/m/Y') : 'N/A' }}</td>
                    <td><span class="status status-{{ $t->statut }}">{{ $t->statut }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="director-sign">
        <p>Le Directeur Général</p>
        <div class="sign-box"></div>
        <p><strong>{{ $settings['agency_director'] ?? 'Joseph Bangoura' }}</strong></p>
    </div>

    <div class="footer">
        {{ $settings['agency_name'] ?? 'ImmoPro' }} — {{ $settings['agency_address'] ?? 'Conakry, Guinée' }} — {{ $settings['agency_phone'] ?? '' }}
    </div>
</body>
</html>
