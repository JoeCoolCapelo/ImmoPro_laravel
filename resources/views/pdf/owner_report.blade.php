<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Performance - {{ $user->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 40px; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .agency-name { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .report-title { font-size: 18px; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px; }
        .date { font-size: 12px; color: #64748b; }
        
        .stats-grid { margin-bottom: 40px; }
        .stat-card { background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 10px; }
        .stat-label { font-size: 10px; text-transform: uppercase; font-weight: bold; color: #64748b; margin-bottom: 5px; }
        .stat-value { font-size: 20px; font-weight: bold; color: #0f172a; }
        
        .table { w-full; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #f1f5f9; padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase; }
        .table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; padding: 20px 0; }
        .net-revenue { color: #10b981; }
    </style>
</head>
<body>
    <div class="header">
        <div class="agency-name">IMMOPRO AGENCY</div>
        <div class="report-title">Bilan de Performance Immobilier</div>
        <div class="date">Généré le {{ now()->format('d/m/Y') }} pour {{ $user->name }}</div>
    </div>

    <div class="stats-grid">
        <div style="width: 48%; float: left;">
            <div class="stat-card">
                <div class="stat-label">Valeur du Portfolio</div>
                <div class="stat-value">{{ number_format($biens->sum('prix'), 0, ',', ' ') }} GNF</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Audience Totale (Vues)</div>
                <div class="stat-value">{{ number_format($stats['total_vues'], 0, ',', ' ') }}</div>
            </div>
        </div>
        <div style="width: 48%; float: right;">
            <div class="stat-card">
                <div class="stat-label">Revenus Bruts</div>
                <div class="stat-value">{{ number_format($stats['revenus_bruts'], 0, ',', ' ') }} GNF</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Revenu Net (Après frais)</div>
                <div class="stat-value net-revenue">{{ number_format($stats['revenu_net'], 0, ',', ' ') }} GNF</div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <h3>Résumé du Parc Immobilier</h3>
    <table class="table" style="width: 100%;">
        <thead>
            <tr>
                <th>Bien</th>
                <th>Statut</th>
                <th>Prix</th>
                <th>Vues</th>
                <th>Visites</th>
            </tr>
        </thead>
        <tbody>
            @foreach($biens as $bien)
                <tr>
                    <td><strong>{{ $bien->titre }}</strong><br><small>{{ $bien->ville }}, {{ $bien->quartier }}</small></td>
                    <td>{{ strtoupper($bien->statut) }}</td>
                    <td>{{ number_format($bien->prix, 0, ',', ' ') }}</td>
                    <td>{{ $bien->vues }}</td>
                    <td>{{ $bien->visites->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document officiel généré par ImmoPro Agency - Gestion de Patrimoine Immobilier Premium.
    </div>
</body>
</html>
