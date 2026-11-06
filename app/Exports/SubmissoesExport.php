<?php

namespace App\Exports;

use App\Models\Revista;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubmissoesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Revista::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Autor/Estudante',
            'Estado',
            'Visualizações',
            'Downloads',
            'Data de Submissão',
        ];
    }

    public function map($revista): array
    {
        return [
            $revista->id,
            $revista->titulo,
            $revista->user->name ?? 'N/A',
            ucfirst($revista->status),
            $revista->visualizacoes ?? 0,
            $revista->downloads ?? 0,
            $revista->created_at->format('d/m/Y H:i'),
        ];
    }
}