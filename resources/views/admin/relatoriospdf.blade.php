<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Submissões - FCT DONDO</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        p.sub { text-align: center; color: #666; margin-top: 0; }
        .summary { margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .summary td { border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        table.data th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>FCT DONDO - Relatório de Submissões</h2>
    <p class="sub">Gerado em {{ date('d/m/Y H:i') }}</p>

    <table class="summary">
        <tr>
            <td style="color: green;">Publicadas: {{ $totalPublicadas }}</td>
            <td style="color: orange;">Pendentes: {{ $totalPendentes }}</td>
            <td style="color: red;">Rejeitadas: {{ $totalRejeitadas }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Visualizações</th>
                <th>Downloads</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revistas as $revista)
                <tr>
                    <td>{{ $revista->id }}</td>
                    <td>{{ $revista->titulo }}</td>
                    <td>{{ ucfirst($revista->status) }}</td>
                    <td>{{ $revista->visualizacoes ?? 0 }}</td>
                    <td>{{ $revista->downloads ?? 0 }}</td>
                    <td>{{ $revista->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>