@extends('layouts.basedashboard')

@section('titulo', 'Materias')

@push('CSS')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="bi bi-book-fill me-2"></i>
                        Materias
                    </h2>
                    <p class="text-muted mb-0">Gestiona las materias del sistema</p>
                </div>
                <div>
                    <a href="{{ route('materias.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle-fill me-1"></i>
                        Nueva Materia
                    </a>
                </div>
            </div>
            <!-- Tabla de Materias -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaMaterias">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materias as $materia)
                                <tr>
                                    <td>{{ $materia->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-book text-primary me-2"></i>
                                            <span class="fw-semibold">{{ $materia->nombre }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Botón Editar -->
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="editarMateria({{ $materia->id }})"
                                                    title="Editar materia">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <!-- Botón Eliminar -->
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="eliminarMateria({{ $materia->id }}, '{{ $materia->nombre }}')"
                                                    title="Eliminar materia">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('JS')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    function editarMateria(id){
        window.location.href = `{{ route('materias.index') }}/${id}/edit`;
    }
    function eliminarMateria(id, nombre){
        Swal.fire({
            title: "¿Eliminar materia?",
            text: `¿Estás seguro de eliminar la materia "${nombre}"?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $("<form>", {
                    "method": "POST",
                    "action": `{{ route('materias.index') }}/${id}`
                });
                form.append($("<input>", {
                    "type": "hidden",
                    "name": "_token",
                    "value": $('meta[name="csrf-token"]').attr("content")
                }));
                form.append($("<input>", {
                    "type": "hidden",
                    "name": "_method",
                    "value": "DELETE"
                }));
                $("body").append(form);
                form.submit();
            }
        });
    }
</script>
@endpush

@push('JSOR')
    $("#tablaMaterias").DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[1, "asc"]], // Ordenar por nombre
        columnDefs: [
            {
                targets: [2], // Columna de acciones
                orderable: false,
                searchable: false
            }
        ]
    });
    console.log("DataTables materias inicializado correctamente");
@endpush