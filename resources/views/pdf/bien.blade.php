<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $bien->titre }} - Fiche Descriptive</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; margin: 0; padding: 0; background: #ffffff; }
        
        /* Corporate Header */
        .header { background: #0f172a; color: white; padding: 40px; }
        .header-content { width: 100%; border-collapse: collapse; }
        .agency-info { width: 65%; }
        .agency-name { font-size: 26px; font-weight: 900; letter-spacing: -1px; margin-bottom: 5px; color: #6366f1; }
        .agency-meta { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: bold; margin-bottom: 3px; }
        .doc-type { width: 35%; text-align: right; vertical-align: bottom; }
        .doc-badge { background: #6366f1; color: white; padding: 8px 15px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }

        /* Main Content */
        .container { padding: 40px; }
        .title-section { margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; }
        .property-title { font-size: 30px; font-weight: 900; margin: 0; color: #0f172a; }
        .property-location { color: #64748b; font-size: 14px; margin-top: 5px; font-weight: bold; }

        /* Property Stats Grid */
        .stats-grid { width: 100%; margin-bottom: 35px; border-collapse: separate; border-spacing: 15px 0; margin-left: -15px; }
        .stat-card { background: #f8fafc; border: 1px solid #f1f5f9; padding: 18px; border-radius: 15px; text-align: center; }
        .stat-label { font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .stat-value { font-size: 16px; font-weight: 900; color: #1e293b; }

        /* Photos Section */
        .photo-main { width: 100%; height: 380px; border-radius: 20px; object-fit: cover; margin-bottom: 20px; background: #f1f5f9; }
        
        /* Sections */
        .section-title { font-size: 16px; font-weight: 900; color: #0f172a; margin: 30px 0 15px 0; border-left: 4px solid #6366f1; padding-left: 15px; text-transform: uppercase; }
        .description { font-size: 13px; line-height: 1.8; color: #475569; text-align: justify; margin-bottom: 30px; }

        /* Details Table */
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        .details-label { font-weight: bold; color: #64748b; width: 35%; }
        .details-value { font-weight: 900; color: #1e293b; text-align: right; }

        /* Footer */
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
                                <div class="agency-meta">Dir. Général : {{ $settings['agency_director'] ?? 'M. Mohamed SYLLA' }}</div>
                                <div class="agency-meta" style="color: #6366f1;">Agent Responsable : {{ $bien->agent->name ?? 'Agence ImmoPro' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="doc-type">
                    <div class="doc-badge">Fiche Descriptive Officielle</div>
                    <div style="font-size: 10px; margin-top: 5px; color: #94a3b8;">Réf: #BIEN-{{ str_pad($bien->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="container">
        <div class="title-section">
            <h1 class="property-title">{{ $bien->titre }}</h1>
            <div class="property-location">{{ $bien->adresse }}, {{ $bien->ville }} — République de Guinée</div>
        </div>

        <table class="stats-grid">
            <tr>
                <td style="width: 33%;">
                    <div class="stat-card">
                        <span class="stat-label">Valeur du Bien</span>
                        <div class="stat-value" style="color: #6366f1;">{{ number_format($bien->prix, 0, ',', ' ') }} <small style="font-size: 9px;">GNF</small></div>
                    </div>
                </td>
                <td style="width: 33%;">
                    <div class="stat-card">
                        <span class="stat-label">Surface Totale</span>
                        <div class="stat-value">{{ $bien->surface }} m²</div>
                    </div>
                </td>
                <td style="width: 33%;">
                    <div class="stat-card">
                        <span class="stat-label">Pièces</span>
                        <div class="stat-value">{{ $bien->nb_pieces ?? 'N/A' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        @if($bien->images->count() > 0)
            @php $mainImage = $bien->images->where('is_main', true)->first() ?? $bien->images->first(); @endphp
            <img src="{{ public_path('storage/' . $mainImage->path) }}" class="photo-main">
        @endif

        <div class="section-title">Description détaillée</div>
        <div class="description">
            {!! nl2br(e($bien->description)) !!}
        </div>

        <div class="section-title">Caractéristiques Techniques</div>
        <table class="details-table">
            <tr>
                <td class="details-label">Type de propriété</td>
                <td class="details-value">{{ ucfirst($bien->type) }}</td>
            </tr>
            <tr>
                <td class="details-label">Nature de l'offre</td>
                <td class="details-value">{{ ucfirst($bien->nature) }}</td>
            </tr>
            <tr>
                <td class="details-label">Ville / Quartier</td>
                <td class="details-value">{{ $bien->ville }}</td>
            </tr>
            <tr>
                <td class="details-label">Conseiller en charge</td>
                <td class="details-value">{{ $bien->agent->name ?? 'ImmoPro' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-text">
            {{ $settings['agency_name'] ?? 'ImmoPro' }} — {{ $settings['agency_address'] ?? 'Conakry, Guinée' }} — Tél: {{ $settings['agency_phone'] ?? '' }}<br>
            Document contractuel généré le {{ date('d/m/Y') }} par la plateforme certifiée ImmoPro.
        </div>
    </div>
</body>
</html>
