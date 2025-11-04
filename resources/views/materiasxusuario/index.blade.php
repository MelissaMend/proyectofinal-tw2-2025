@extends('layouts.basedashboard')

@section('titulo', 'Gestionar Materias - ' . $usuario->username)

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
                        <i class="bi bi-people-fill me-2"></i>
                        Gestionar Materias
                    </h2>
                    <p class="text-muted mb-0">
                        Usuario: <strong> {{ $usuario->username }} </strong>
                        <span class="badge bg-{{ $usuario->tipo->tipo === 'admin' ? 'danger' : ($usuario->tipo->tipo === 'profesor' ? 'success' : 'primary') }} ms-2">
                            {{ ucfirst($usuario->tipo->tipo) }}
                        </span>
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalAsignarMateria">
                        <i class="bi bi-plus-circle-fill me-1"></i>
                        Asignar Materia
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver a Usuarios
                    </a>
                </div>
            </div>

            <!-- Tabla de Materias Asignadas -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaMaterias">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Materia</th>
                                    <th>Nombre de la Materia</th>
                                    <th>Promedio</th>                                
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materiasAsignadas as $asignacion)
                                <tr>
                                    <td>{{ $asignacion->materia->id }}</td>
                                    <td>{{ $asignacion->materia->nombre }}</td>
                                    <td>
                                        @if( $asignacion->promedio > 0 )
                                            <span class="badge bg-{{ $asignacion->promedio >= 7 ? 'success' : ( $asignacion->promedio >= 5 ? 'warning' : 'danger' ) }} fs-6">
                                                {{ $asignacion->promedio }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">Sin calificaciones</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Botón Agregar Calificación -->
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="agregarCalificacion({{ $usuario->id, $asignacion->materia->id }})"
                                                    title="Agregar calificación">
                                                <i class="bi bi-plus-square"></i>
                                            </button>
                                            
                                            <!-- Botón Eliminar Materia -->
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="eliminarMateria({{ $asignacion->id }}, '{{ $asignacion->materia->nombre }}')"
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

<div class="modal fade" id="modalAsignarMateria" tabindex="-1" aria-labelledby="modalAsignarMateriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAsignarMateriaLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    Asignar Materia a {{ $usuario->username }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAsignarMateria">
                @csrf
                <div class="modal-body">
                    @if( $materiasDisponibles->count() > 0 )
                        <div class="mb-3">
                            <label for="materia_id" class="form-label fw-semibold">
                                <i class="bi bi-book text-primary me-1"></i>
                                Seleccionar Materia <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="materia_id" name="materia_id" required>
                                <option value="">-- Seleccionar materia --</option>
                                @foreach( $materiasDisponibles as $materia )
                                    <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Información:</strong>
                             Solo se muestran las materias que no están asignadas a este usuario.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Sin materias disponibles:</strong>
                             Todas las materias ya están asignadas a este usuario o no hay materias creadas en el sistema.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Asignar Materia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('JS')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    function agregarCalificacion(usuarioId, materiaId){
        // Redirigir a la página de edición
        //window.location.href = `{{ route('usuarios.index') }}/${id}/edit`;
    }
    
    function eliminarMateria(asignacionId, nombreMateria){
        Swal.fire({
            title: "¿Eliminar materia?",
            text: `¿Estás seguro de eliminar la materia "${nombreMateria}" de este usuario?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                // Crear formulario para eliminar
                const form = $("<form>", {
                    "method": "POST",
                    'action': `{{ url('materiasxusuario') }}/${asignacionId}/desasignar`
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
    
    function cambiarEstadoBoton(cargando){
        const btn = $("#btnAsignar");
        if( cargando ){
            btn.html('<i class="bi bi-hourglass-split"></i> Asignando...').prop("disabled", true);
        }
        else{
            btn.html('<i class="bi bi-check-circle me-1"></i> Asignar Materia').prop("disabled", false);
        }
    }
</script>
@endpush

@push('JSOR')
    // Inicializar DataTables
    $("#tablaMaterias").DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[1, "asc"]],
        columnDefs: [
            {
                targets: [3], // Columna de acciones
                orderable: false,
                searchable: false
            }
        ]
    });

    $("#formAsignarMateria").on("submit", function(e){
        e.preventDefault();
        cambiarEstadoBoton(true);
        $.ajax({
            url: "{{ route('materiasxusuario.asignar', $usuario->id) }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response){
                $("#modalAsignarMateria").modal("hide");
                Swal.fire({
                    icon: "success",
                    title: "!Materia asignada!",
                    text: "La materia se ha asignado correctamente",
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr){
                cambiarEstadoBoton(false);
                if( xhr.status === 422 ){
                    const errors = xhr.responseJSON.errors;
                    Object.key(errors).forEach(function(key){
                        $(`#${key}`).addClass("is-invalid");
                        $(`#${key}`).siblings(".invalid-feedback").text(errors[key][0]);
                    });
                }
                else{
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: xhr.responseJSON?.message || "Ocurrió un problema al asignar la materia",
                        confirmButtonText: "Aceptar"
                    });
                }
            }
        });
    });
    
    console.log("DataTables inicializado correctamente");
@endpush