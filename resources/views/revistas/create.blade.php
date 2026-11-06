@extends('layouts.app')

@push('styles')
<style>
    .form-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }
    .form-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e3a8a 100%);
        border-radius: 1.25rem 1.25rem 0 0;
    }
    .file-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .file-dropzone:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">

            <div class="form-card shadow-lg overflow-hidden">
                <div class="form-header p-4 p-md-5 text-white">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <a href="{{ route('estudante.dashboard') }}" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" title="Voltar ao Painel">
                            <i class="bi bi-arrow-left fs-5"></i>
                        </a>
                        <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill small fw-semibold border border-white border-opacity-25">
                            <i class="bi bi-journal-plus me-1 text-warning"></i> Nova Submissão Académica
                        </span>
                    </div>
                    <h2 class="fw-bold mb-1 text-white">Submeter Trabalho Científico</h2>
                    <p class="text-white-50 fs-6 mb-0">Preencha com atenção os metadados do seu artigo ou tese para apreciação da comissão científica.</p>
                </div>

                <form action="{{ route('revistas.store') }}" method="POST" enctype="multipart/form-data" class="p-4 p-md-5">
                    @csrf

                    <div class="d-flex align-items-center gap-2 mb-3 text-primary">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark">1. Dados do Trabalho & Classificação</h5>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="titulo" class="form-label fw-semibold text-dark">Título Completo do Trabalho <span class="text-danger">*</span></label>
                            <input type="text" name="titulo" id="titulo" class="form-control form-control-lg rounded-3 @error('titulo') is-invalid @enderror" 
                                   placeholder="Ex: Aplicação de Inteligência Artificial na Otimização Agrícola em Moçambique" 
                                   value="{{ old('titulo') }}" required>
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="curso" class="form-label fw-semibold text-dark">Curso / Licenciatura <span class="text-danger">*</span></label>
                            <select name="curso" id="curso" class="form-select form-select-lg rounded-3 @error('curso') is-invalid @enderror" required>
                                <option value="" disabled selected>Selecione o seu curso...</option>
                                
                                <optgroup label="Ciências Exatas, Engenharias & Tecnologias">
                                    <option value="Lic. em Geologia" {{ old('curso') == 'Lic. em Geologia' ? 'selected' : '' }}>Lic. em Geologia</option>
                                    <option value="Lic. em Engenharia de Construção Civil" {{ old('curso') == 'Lic. em Engenharia de Construção Civil' ? 'selected' : '' }}>Lic. em Engenharia de Construção Civil</option>
                                    <option value="Lic. em Informática" {{ old('curso') == 'Lic. em Informática' ? 'selected' : '' }}>Lic. em Informática</option>
                                    <option value="Lic. em Estatística e Gestão de Informação" {{ old('curso') == 'Lic. em Estatística e Gestão de Informação' ? 'selected' : '' }}>Lic. em Estatística e Gestão de Informação</option>
                                </optgroup>

                                <optgroup label="Ambiente, Sociedade & Humanidades">
                                    <option value="Lic. em Gestão Ambiental e Desenvolvimento Comunitário" {{ old('curso') == 'Lic. em Gestão Ambiental e Desenvolvimento Comunitário' ? 'selected' : '' }}>Lic. em Gestão Ambiental e Desenvolvimento Comunitário</option>
                                    <option value="Lic. em Ciências Energéticas" {{ old('curso') == 'Lic. em Ciências Energéticas' ? 'selected' : '' }}>Lic. em Ciências Energéticas</option>
                                    <option value="Lic. em Química Ambiental" {{ old('curso') == 'Lic. em Química Ambiental' ? 'selected' : '' }}>Lic. em Química Ambiental</option>
                                    <option value="Lic. em História" {{ old('curso') == 'Lic. em História' ? 'selected' : '' }}>Lic. em História</option>
                                    <option value="Lic. em Relações Internacionais e Assistência Humanitária" {{ old('curso') == 'Lic. em Relações Internacionais e Assistência Humanitária' ? 'selected' : '' }}>Lic. em Relações Internacionais e Assistência Humanitária</option>
                                    <option value="Lic. em Direito" {{ old('curso') == 'Lic. em Direito' ? 'selected' : '' }}>Lic. em Direito</option>
                                    <option value="Lic. em Ciências Políticas e Governação" {{ old('curso') == 'Lic. em Ciências Políticas e Governação' ? 'selected' : '' }}>Lic. em Ciências Políticas e Governação</option>
                                    <option value="Lic. em Sociologia" {{ old('curso') == 'Lic. em Sociologia' ? 'selected' : '' }}>Lic. em Sociologia</option>
                                </optgroup>

                                <optgroup label="Educação, Pedagogia & Psicologia">
                                    <option value="Lic. em AGE" {{ old('curso') == 'Lic. em AGE' ? 'selected' : '' }}>Lic. em AGE</option>
                                    <option value="Lic. em Ciências de Educação" {{ old('curso') == 'Lic. em Ciências de Educação' ? 'selected' : '' }}>Lic. em Ciências de Educação</option>
                                    <option value="Lic. em Educação de Infância" {{ old('curso') == 'Lic. em Educação de Infância' ? 'selected' : '' }}>Lic. em Educação de Infância</option>
                                    <option value="Lic. em Ensino Básico" {{ old('curso') == 'Lic. em Ensino Básico' ? 'selected' : '' }}>Lic. em Ensino Básico</option>
                                    <option value="Lic. em Educação Visual" {{ old('curso') == 'Lic. em Educação Visual' ? 'selected' : '' }}>Lic. em Educação Visual</option>
                                    <option value="Lic. em Educação Física e Desportos" {{ old('curso') == 'Lic. em Educação Física e Desportos' ? 'selected' : '' }}>Lic. em Educação Física e Desportos</option>
                                    <option value="Lic. em Psicologia Social e das Organizações" {{ old('curso') == 'Lic. em Psicologia Social e das Organizações' ? 'selected' : '' }}>Lic. em Psicologia Social e das Organizações</option>
                                    <option value="Lic. em Psicologia Educacional" {{ old('curso') == 'Lic. em Psicologia Educacional' ? 'selected' : '' }}>Lic. em Psicologia Educacional</option>
                                </optgroup>

                                <optgroup label="Ensino das Línguas & Ciências">
                                    <option value="Lic. em Ensino de Português" {{ old('curso') == 'Lic. em Ensino de Português' ? 'selected' : '' }}>Lic. em Ensino de Português</option>
                                    <option value="Lic. em Ensino de Inglês" {{ old('curso') == 'Lic. em Ensino de Inglês' ? 'selected' : '' }}>Lic. em Ensino de Inglês</option>
                                    <option value="Lic. em Ensino de Francês" {{ old('curso') == 'Lic. em Ensino de Francês' ? 'selected' : '' }}>Lic. em Ensino de Francês</option>
                                    <option value="Lic. em Ensino de Química" {{ old('curso') == 'Lic. em Ensino de Química' ? 'selected' : '' }}>Lic. em Ensino de Química</option>
                                    <option value="Lic. em Ensino de Matemática" {{ old('curso') == 'Lic. em Ensino de Matemática' ? 'selected' : '' }}>Lic. em Ensino de Matemática</option>
                                    <option value="Lic. em Ensino de Geografia" {{ old('curso') == 'Lic. em Ensino de Geografia' ? 'selected' : '' }}>Lic. em Ensino de Geografia</option>
                                    <option value="Lic. em Ensino de Biologia" {{ old('curso') == 'Lic. em Ensino de Biologia' ? 'selected' : '' }}>Lic. em Ensino de Biologia</option>
                                    <option value="Lic. em Ensino de Filosofia" {{ old('curso') == 'Lic. em Ensino de Filosofia' ? 'selected' : '' }}>Lic. em Ensino de Filosofia</option>
                                    <option value="Lic. em Ensino de Física" {{ old('curso') == 'Lic. em Ensino de Física' ? 'selected' : '' }}>Lic. em Ensino de Física</option>
                                </optgroup>

                                <optgroup label="Gestão, Finanças & Recursos Humanos">
                                    <option value="Lic. em Gestão de Empresas" {{ old('curso') == 'Lic. em Gestão de Empresas' ? 'selected' : '' }}>Lic. em Gestão de Empresas</option>
                                    <option value="Lic. em Gestão dos Recursos Humanos" {{ old('curso') == 'Lic. em Gestão dos Recursos Humanos' ? 'selected' : '' }}>Lic. em Gestão dos Recursos Humanos</option>
                                    <option value="Lic. em Contabilidade e Finanças" {{ old('curso') == 'Lic. em Contabilidade e Finanças' ? 'selected' : '' }}>Lic. em Contabilidade e Finanças</option>
                                </optgroup>
                            </select>
                            @error('curso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tipo_documento" class="form-label fw-semibold text-dark">Tipo de Documento</label>
                            <select name="tipo_documento" id="tipo_documento" class="form-select form-select-lg rounded-3">
                                <option value="Tese de Licenciatura">Tese / Monografia de Licenciatura</option>
                                <option value="Artigo Científico">Artigo Científico</option>
                                <option value="Relatório de Estágio">Relatório de Estágio Profissional</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="orientador" class="form-label fw-semibold text-dark">Orientador(a) Académico(a)</label>
                            <input type="text" name="orientador" id="orientador" class="form-control rounded-3" placeholder="Ex: Prof. Dr. Armando Mabunda" value="{{ old('orientador') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="ano_academico" class="form-label fw-semibold text-dark">Ano da Publicação</label>
                            <input type="number" name="ano_academico" id="ano_academico" class="form-control rounded-3" min="2010" max="{{ date('Y') }}" value="{{ old('ano_academico', date('Y')) }}">
                        </div>
                    </div>

                    <!-- Secção de Coautores Dinâmicos (Máximo 5) -->
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold text-dark mb-0">
                                <i class="bi bi-people-fill text-primary me-1"></i> Coautores / Colaboradores (Máximo 5)
                            </label>
                            <span id="contador-coautores" class="badge bg-secondary">0 / 5</span>
                        </div>
                        <small class="text-muted d-block mb-3">Adicione os nomes dos estudantes que colaboraram na elaboração deste trabalho.</small>

                        <div id="coautores-container"></div>

                        <button type="button" id="btn-adicionar-coautor" class="btn btn-sm btn-outline-primary fw-semibold mt-1">
                            <i class="bi bi-person-plus-fill me-1"></i> Adicionar Coautor
                        </button>
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="d-flex align-items-center gap-2 mb-3 text-primary">
                        <i class="bi bi-file-text-fill fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark">2. Resumo / Abstract Alargado</h5>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label fw-semibold text-dark">Resumo do Trabalho <span class="text-danger">*</span></label>
                        <textarea name="descricao" id="descricao" rows="5" class="form-control rounded-3 @error('descricao') is-invalid @enderror" placeholder="Escreva de forma sucinta os objetivos, metodologia e resultados..." required>{{ old('descricao') }}</textarea>
                        @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="d-flex align-items-center gap-2 mb-3 text-primary">
                        <i class="bi bi-cloud-arrow-up-fill fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark">3. Ficheiros do Trabalho</h5>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Documento do Trabalho (PDF) <span class="text-danger">*</span></label>
                            <div class="file-dropzone p-4 text-center" onclick="document.getElementById('ficheiro_pdf').click()">
                                <i class="bi bi-file-earmark-pdf-fill fs-1 text-danger mb-2 d-block"></i>
                                <span class="fw-bold text-dark d-block">Clique para selecionar o PDF</span>
                                <small class="text-muted d-block" id="pdf-filename">Apenas formatos PDF (Máx. 30MB)</small>
                            </div>
                            <input type="file" name="ficheiro_pdf" id="ficheiro_pdf" class="d-none @error('ficheiro_pdf') is-invalid @enderror" accept="application/pdf" required onchange="updateFileName(this, 'pdf-filename')">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Imagem de Capa (Opcional)</label>
                            <div class="file-dropzone p-4 text-center" onclick="document.getElementById('capa').click()">
                                <i class="bi bi-image-fill fs-1 text-primary mb-2 d-block"></i>
                                <span class="fw-bold text-dark d-block">Clique para carregar a capa</span>
                                <small class="text-muted d-block" id="capa-filename">JPEG, PNG, WEBP (Máx. 5MB)</small>
                            </div>
                            <input type="file" name="capa" id="capa" class="d-none @error('capa') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" onchange="updateFileName(this, 'capa-filename')">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('estudante.dashboard') }}" class="btn btn-light fw-bold text-secondary rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill px-5 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Submeter para Revisão
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateFileName(input, targetId) {
        if (input.files && input.files[0]) {
            document.getElementById(targetId).textContent = 'Ficheiro selecionado: ' + input.files[0].name;
            document.getElementById(targetId).classList.add('text-primary', 'fw-bold');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('coautores-container');
        const btnAdicionar = document.getElementById('btn-adicionar-coautor');
        const contador = document.getElementById('contador-coautores');
        const maxCoautores = 5;

        const oldCoautores = @json(old('coautores', []));

        function atualizarInterface() {
            const linhas = container.querySelectorAll('.coautor-row');
            const total = linhas.length;
            contador.textContent = `${total} / ${maxCoautores}`;
            btnAdicionar.style.display = total >= maxCoautores ? 'none' : 'inline-block';
        }

        function criarLinhaCoautor(valor = '') {
            if (container.querySelectorAll('.coautor-row').length >= maxCoautores) return;

            const div = document.createElement('div');
            div.className = 'input-group mb-2 coautor-row';
            div.innerHTML = `
                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                <input type="text" name="coautores[]" class="form-control" value="${valor}" placeholder="Nome completo do coautor / colaborador">
                <button type="button" class="btn btn-outline-danger btn-remover-coautor" title="Remover coautor">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            container.appendChild(div);
            atualizarInterface();
        }

        if (Array.isArray(oldCoautores) && oldCoautores.length > 0) {
            oldCoautores.forEach(nome => {
                if (nome && nome.trim() !== '') criarLinhaCoautor(nome);
            });
        }

        btnAdicionar.addEventListener('click', () => criarLinhaCoautor());

        container.addEventListener('click', function (e) {
            if (e.target.closest('.btn-remover-coautor')) {
                e.target.closest('.coautor-row').remove();
                atualizarInterface();
            }
        });

        atualizarInterface();
    });
</script>
@endpush