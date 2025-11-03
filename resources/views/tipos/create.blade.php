@extends('layouts.basedashboard')

@section('titulo', 'Crear Tipo de Usuario')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-success mb-1">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        Crear Tipo de Usuario
                    </h2>
                    <p class="text-muted mb-0">Agrega un nuevo tipo de usuario al sistema</p>
                </div>
                <div>
                    <a href="{{ route('tipos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-fill me-2"></i>
                                Información del Usuario
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="formCrearTipo" method="POST" action="{{ route('tipos.store') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="tipo" class="form-label fw-semibold">
                                        <i class="bi bi-person-badge text-primary me-1"></i>
                                        Nombre del Tipo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" @error('tipo') is-invalid @enderror id="tipo" name="tipo" 
                                    value="{{ old('tipo') }}" placeholder="Ej. admin, profesor, estudinte" maxlength="50" required>
                                    
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Ejemplos comunes: </label>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary ejemplo-tipo" data-tipo="admin">Admin</button>
                                            <button type="button" class="btn btn-sm btn-outline-success ejemplo-tipo" data-tipo="profesor">Profesor</button>
                                            <button type="button" class="btn btn-sm btn-outline-info ejemplo-tipo" data-tipo="estudiante">Estudiante</button>
                                            <button type="button" class="btn btn-sm btn-outline-warning ejemplo-tipo" data-tipo="coordinador">Coordinador</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary ejemplo-tipo" data-tipo="invitado">Invitado</button>
                                        </div>
                                    <small class="text-muted">Haz clic en cualquier boton ejemplo para usarlo</small>
                                </div>

                                <div class="mb-4" id="previewTipo" style="display: none;">
                                    <label class="form-label fw-semibold">Vista previa:</label>
                                    <div id="badgePreview"></div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('tipos.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success" id="btnGuardar"><i class="bi bi-check-circle me-1"></i>Crear Tipo</button>
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
        const btn = $("#btnGuardar");
        if( cargando ){
            btn.html('<i class="bi bi-hourglass-split"></i> Creando...').prop("disabled", true);
        }
        else{
            btn.html('<i class="bi bi-check-circle me-1"></i> Crear Usuario').prop("disabled", false);
        }
    }

    function actualizarPreview(tipo){
        const preview = $("#previewTipo");
        const badgePreview = ("#badgePreview");

        if ( tipo.trim() ) {
            //color del badge segun el tipo
            let colorClass = "bg-secondary";
            if(tipo.tolowerCase() === "admin") colorClass = "bg-danger";
            else if(tipo.tolowerCase() === "profesor") colorClass = "bg-success";
            else if(tipo.tolowerCase() === "estudiante") colorClass = "bg-primary";
            else if(tipo.tolowerCase() === "coordinador") colorClass = "bg-warning";

            const badge = `<span class="badge ${colorClass} fs-6><i class="bi bi-person-badge me-1></i>${tipo}</span>`;

            badgePreview.html(badge);
            preview.slideUp();
        }
    }

</script>
@endpush

@push('JSOR')
    $("#tipo").on("input", function(){
        actualizarPreview($(this).val());
    });
    $(".ejemplo-tipo").on("click", function(){
        const tipo = $(this).data("tipo");
        $("#tipo").val(tipo).trigger("input");
    });

    // Manejo del formulario
    $("#formCrearTipo").on("submit", function(e){
        e.preventDefault();
        cambiarEstadoBoton(true);
        $.ajax({
            url: $(this).attr("action"),
            method: "POST",
            data: $(this).serialize(),
            success: function(response){
                Swal.fire({
                    icon: "success",
                    title: "Tipo creado!",
                    text: "El tipo de usuario se ha creado correctamente",
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = "{{ route('tipos.index') }}";
                });
            },
            error: function(xhr){
                cambiarEstadoBoton(false);
                if( xhr.status === 422 ){
                    // Recargar para mostrar errores de validación
                    location.reload();
                }
                else{
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Ocurrió un problema al crear el tipo",
                        confirmButtonText: "Aceptar"
                    });
                }
            }
        });
    });
    
    console.log("Vista crear usuario cargada");
@endpush