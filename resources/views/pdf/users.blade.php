<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Utilisateurs — {{ $settings['agency_name'] ?? 'ImmoPro' }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; margin: 0; padding: 0; background: #ffffff; }

        /* Header */
        .header { background: #0f172a; color: white; padding: 36px 40px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .agency-name { font-size: 24px; font-weight: 900; letter-spacing: -1px; margin-bottom: 4px; color: #ffffff; }
        .agency-meta { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: bold; margin-bottom: 2px; }
        .doc-badge { background: #ffffff; color: #0f172a; padding: 8px 16px; border-radius: 8px; font-size: 10px; font-weight: 900; text-transform: uppercase; display: inline-block; letter-spacing: 1px; }
        .doc-date { font-size: 10px; margin-top: 6px; color: #64748b; text-align: right; }

        /* Meta Bar */
        .meta-bar { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 40px; }
        .meta-bar-inner { display: inline-block; font-size: 11px; color: #64748b; font-weight: bold; }
        .meta-bar span { color: #0f172a; font-weight: 900; }

        /* Table */
        .container { padding: 30px 40px; }
        .users-table { width: 100%; border-collapse: collapse; }
        .users-table thead th {
            background: #0f172a;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 12px 14px;
            text-align: left;
        }
        .users-table tbody tr:nth-child(even) { background: #f8fafc; }
        .users-table tbody tr:nth-child(odd) { background: #ffffff; }
        .users-table tbody td { padding: 11px 14px; font-size: 11px; border-bottom: 1px solid #f1f5f9; color: #1e293b; vertical-align: middle; }
        .user-name { font-weight: 900; color: #0f172a; font-size: 12px; }
        .user-email { font-size: 10px; color: #64748b; margin-top: 2px; }
        .role-badge {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 10px;
            border-radius: 50px;
        }

        /* Footer */
        .footer { position: fixed; bottom: 0; left: 0; right: 0; background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 40px; }
        .footer-text { font-size: 9px; color: #94a3b8; font-weight: bold; text-align: center; }
        .page-number:before { content: "Page " counter(page); }
        .page-number:after { content: " / " counter(pages); }
    </style>
</head>
<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 65%; vertical-align: middle;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            @if(!empty($settings['agency_logo']))
                            <td style="width: 70px; padding-right: 16px; vertical-align: middle;">
                                <img src="{{ public_path('storage/' . $settings['agency_logo']) }}" style="max-height: 60px; max-width: 60px; border-radius: 10px;">
                            </td>
                            @endif
                            <td style="vertical-align: middle;">
                                <div class="agency-name">{{ $settings['agency_name'] ?? 'ImmoPro' }}</div>
                                <div class="agency-meta">Dir. Général : {{ $settings['agency_director'] ?? '' }}</div>
                                <div class="agency-meta">{{ $settings['contact_email'] ?? '' }} — {{ $settings['contact_phone'] ?? '' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 35%; text-align: right; vertical-align: bottom;">
                    <div class="doc-badge">Liste des Utilisateurs</div>
                    <div class="doc-date">Généré le {{ date('d/m/Y à H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-bar">
        <div class="meta-bar-inner">
            Total : <span>{{ $users->count() }} utilisateur(s)</span>
            @if($filterSearch) &nbsp;|&nbsp; Recherche : <span>"{{ $filterSearch }}"</span> @endif
            @if($filterRole) &nbsp;|&nbsp; Rôle : <span>{{ ucfirst($filterRole) }}</span> @endif
        </div>
    </div>

    <div class="container">
        <table class="users-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $user)
                <tr>
                    <td style="color: #94a3b8; font-size: 10px; font-weight: bold;">{{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </td>
                    <td>
                        <span class="role-badge">{{ $user->roles->first()?->name ?? 'Aucun' }}</span>
                    </td>
                    <td style="font-size: 11px; color: #475569; font-weight: bold;">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div class="footer-text">
            {{ $settings['agency_name'] ?? 'ImmoPro' }} — Document confidentiel généré automatiquement par la plateforme ImmoPro &nbsp;|&nbsp; <span class="page-number"></span>
        </div>
    </div>

</body>
</html>
