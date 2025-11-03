@extends('layouts.basedashboard')

@section('titulo', 'Editar Materia')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-warning mb-1">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Materia
                    </h2>
                    <p class="text-muted mb-0">Modifica la materia: <strong>{{ $materia->nombre }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('materias.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver
                    </a>
                </div>
            </div>
            <!-- Formulario -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-book-fill me-2"></i>
                                Información de la Materia
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="formEditarMateria" method="POST" action="{{ route('materias.update', $materia->id) }}">
                                @csrf
                                @method('PUT')

                                <!-- Información actual -->
                                <div class="alert alert-info mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            <strong>Materia actual:</strong>
                                            <span class="badge bg-primary ms-2">
                                                <i class="bi bi-book me-1"></i>{{ $materia->nombre }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                Usuarios asignados: <strong>{{ $materia->materiasXUsuarios?->count() ?? 0 }}</strong> |
                                                Calificaciones: <strong>{{ $materia->calificaciones?->count() ?? 0 }}</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <!-- Nombre -->
                                <div class="mb-4">
                                    <label for="nombre" class="form-label fw-semibold">
                                        <i class="bi bi-book text-primary me-1"></i>
                                        Nombre de la Materia <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre', $materia->nombre) }}" 
                                           placeholder="Ej: Matemáticas, Historia, Ciencias, etc."
                                           maxlength="255"
                                           required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Modifica el nombre de la materia (máximo 255 caracteres)
                                    </div>
                                </div>
                                <!-- Ejemplos sugeridos -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Ejemplos comunes:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary ejemplo-materia" data-materia="Matemáticas">
                                            Matemáticas
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success ejemplo-materia" data-materia="Ciencias Naturales">
                                            Ciencias Naturales
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info ejemplo-materia" data-materia="Historia">
                                            Historia
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning ejemplo-materia" data-materia="Lengua y Literatura">
                                            Lengua y Literatura
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ejemplo-materia" data-materia="Educación Física">
                                            Educación Física
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-dark ejemplo-materia" data-materia="Inglés">
                                            Inglés
                                        </button>
                                    </div>
                                    <small class="text-muted">Haz clic en cualquier ejemplo para usarlo</small>
                                </div>
                                <!-- Preview de la materia -->
                                <div class="mb-4" id="previewMateria">
                                    <label class="form-label fw-semibold">Vista previa:</label>
                                    <div id="materiaPreview"></div>
                                </div>
                                <!-- Advertencia si tiene relaciones -->
                                @if(($materia->materiasXUsuarios?->count() ?? 0) > 0 || ($materia->calificaciones?->count() ?? 0) > 0)
                                <div class="alert alert-warning mb-4">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                                        <div>
                                            <strong>¡Atención!</strong><br>
                                            Esta materia tiene datos relacionados:
                                            @if(($materia->materiasXUsuarios?->count() ?? 0) > 0)
                                                <br>• <strong>{{ $materia->materiasXUsuarios->count() }} usuario(s)</strong> asignado(s)
                                            @endif
                                            @if(($materia->calificaciones?->count() ?? 0) > 0)
                                                <br>• <strong>{{ $materia->calificaciones->count() }} calificación(es)</strong> registrada(s)
                                            @endif
                                            <br>Al cambiar el nombre, se actualizará en todos los registros relacionados.
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- Botones -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('materias.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-warning" id="btnActualizar">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Actualizar Materia
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('JS')
<script>
    function cambiarEstadoBoton(cargando){
        const btn = $("#btnActualizar");
        if( cargando ){
            btn.html('<i class="bi bi-hourglass-split"></i> Actualizando...').prop("disabled", true);
        }
        else{
            btn.html('<i class="bi bi-check-circle me-1"></i> Actualizar Materia').prop("disabled", false);
        }
    }
    function actualizarPreview(nombre){
        const materiaPreview = $("#materiaPreview");
        if( nombre.trim() ){
            const card = `
                <div class="card border-primary">
                    <div class="card-body py-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book text-primary me-2"></i>
                            <span class="fw-semibold">${nombre}</span>
                        </div>
                    </div>
                </div>
            `;
            materiaPreview.html(card);
        }
        else{
            materiaPreview.html("<span class="text-muted">Ingresa un nombre para ver la vista previa</span>");
        }
    }
</script>
@endpush

@push('JSOR')
    // Inicializar preview
    actualizarPreview( $("#nombre").val() );
    // Preview en tiempo real
    $("#nombre").on("input", function(){
        actualizarPreview( $(this).val() );
    });
    // Botones de ejemplo
    $(".ejemplo-materia").on("click", function(){
        const materia = $(this).data("materia");
        $("#nombre").val(materia);
        actualizarPreview( materia );
    });
    // Manejo del formulario
    $("#formEditarMateria").on("submit", function(e){
        e.preventDefault();
        cambiarEstadoBoton(true);
        $.ajax({
            url: $(this).attr("action"),
            method: "POST",
            data: $(this).serialize(),
            success: function(response){
                Swal.fire({
                    icon: "success",
                    title: "¡Materia actualizada!",
                    text: "La materia se ha actualizado correctamente",
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = "{{ route('materias.index') }}";
                });
            },
            error: function(xhr){
                cambiarEstadoBoton(false);
                if( xhr.status === 422 ){
                    location.reload();
                }
                else{
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Ocurrió un problema al actualizar la materia",
                        confirmButtonText: "Aceptar"
                    });
                }
            }
        });
    });
    
    console.log("Vista editar materia cargada");
@endpush