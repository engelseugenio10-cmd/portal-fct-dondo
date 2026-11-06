<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Publicação</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #1a252f;
        }
        .cert-container {
            width: 100%;
            height: 100%;
            padding: 50px;
            box-sizing: border-box;
            text-align: center;
            border: 15px solid #1a365d;
            background: #ffffff;
            position: relative;
        }
        .header {
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a365d;
            font-size: 28px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .sub-header {
            font-size: 16px;
            color: #718096;
            margin-bottom: 40px;
            text-transform: uppercase;
        }
        .title {
            font-size: 22px;
            color: #2b6cb0;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .body-text {
            font-size: 18px;
            line-height: 1.6;
            color: #2d3748;
            margin: 30px 40px;
        }
        .highlight {
            font-weight: bold;
            color: #1a252f;
        }
        .footer {
            margin-top: 60px;
            width: 100%;
        }
        .signature-box {
            display: inline-block;
            width: 40%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #a0aec0;
            margin-bottom: 5px;
        }
        .signature-title {
            font-size: 12px;
            color: #718096;
        }
        .date {
            font-size: 14px;
            color: #4a5568;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="header">Certificado de Publicação</div>
        <div class="sub-header">Revista Científica e Académica</div>

        <div class="body-text">
            Certificamos que o trabalho intitulado<br>
            <div class="title">"{{ $revista->titulo }}"</div>
            de autoria de <span class="highlight">{{ $revista->user->name ?? 'Autor' }}</span>
            @if(!empty($coautoresStr))
                em coautoria com <span class="highlight">{{ $coautoresStr }}</span>
            @endif
            ,<br>
            pertencente ao curso de <span class="highlight">{{ $revista->curso }}</span>, 
            foi devidamente avaliado, aprovado e publicado na plataforma em
            <span class="highlight">{{ $dataAprovacao }}</span>.
        </div>

        <div class="footer">
            <div class="signature-box">
                <div class="signature-line"></div>
                <strong>Conselho Editorial</strong><br>
                <span class="signature-title">Comissão de Avaliação Científica</span>
            </div>
        </div>

        <div class="date">
            Emissão Oficial - {{ date('d/m/Y') }}
        </div>
    </div>
</body>
</html>