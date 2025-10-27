@extends('layouts.basedashboard')

@section('titulo', 'Editar Usuario')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-warning mb-1">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Usuario
                    </h2>
                    <p class="text-muted mb-0">Modifica la información del usuario: <strong>{{ $usuario->username }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-person-fill me-2"></i>
                                Información del Usuario
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="formEditarUsuario" method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <!-- Username -->
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label fw-semibold">
                                            <i class="bi bi-person text-primary me-1"></i>
                                            Username <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('username') is-invalid @enderror" 
                                               id="username" 
                                               name="username" 
                                               value="{{ old('username', $usuario->username) }}" 
                                               placeholder="Ingresa el username"
                                               required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="bi bi-envelope text-primary me-1"></i>
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $usuario->email) }}" 
                                               placeholder="ejemplo@correo.com"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Tipo de Usuario -->
                                <div class="mb-4">
                                    <label for="tipos_id" class="form-label fw-semibold">
                                        <i class="bi bi-person-badge text-primary me-1"></i>
                                        Tipo de Usuario <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('tipos_id') is-invalid @enderror" 
                                            id="tipos_id" 
                                            name="tipos_id" 
                                            required>
                                        <option value="">Selecciona un tipo de usuario</option>
                                        @foreach($tipos as $tipo)
                                            <option value="{{ $tipo->id }}" 
                                                {{ old('tipos_id', $usuario->tipos_id) == $tipo->id ? 'selected' : '' }}>
                                                @if($tipo->tipo === 'admin')
                                                    Administrador
                                                @elseif($tipo->tipo === 'profesor')
                                                    Profesor
                                                @else
                                                    Estudiante
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipos_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Cambiar Contraseña -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="cambiarPassword">
                                            <label class="form-check-label fw-semibold" for="cambiarPassword">
                                                <i class="bi bi-key text-warning me-1"></i>
                                                Cambiar contraseña
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body" id="passwordSection" style="display: none;">
                                        <div class="row">
                                            <!-- Nueva Contraseña -->
                                            <div class="col-md-6 mb-3">
                                                <label for="password" class="form-label fw-semibold">
                                                    <i class="bi bi-lock text-primary me-1"></i>
                                                    Nueva Contraseña
                                                </label>
                                                <div class="input-group">
                                                    <input type="password" 
                                                           class="form-control @error('password') is-invalid @enderror" 
                                                           id="password" 
                                                           name="password" 
                                                           placeholder="Mínimo 6 caracteres">
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <!-- Confirmar Nueva Contraseña -->
                                            <div class="col-md-6 mb-3">
                                                <label for="password_confirmation" class="form-label fw-semibold">
                                                    <i class="bi bi-lock-fill text-primary me-1"></i>
                                                    Confirmar Nueva Contraseña
                                                </label>
                                                <div class="input-group">
                                                    <input type="password" 
                                                           class="form-control" 
                                                           id="password_confirmation" 
                                                           name="password_confirmation" 
                                                           placeholder="Repite la nueva contraseña">
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-warning" id="btnActualizar">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Actualizar Usuario
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
            btn.html('<i class="bi bi-check-circle me-1"></i> Actualizar Usuario').prop("disabled", false);
        }
    }
    
    function togglePasswordVisibility(inputId, buttonId){
        const passwordField = $(inputId);
        const passwordFieldType = passwordField.attr("type");
        const toggleIcon = $(buttonId).find("i");
        
        if( passwordFieldType === "password" ){
            passwordField.attr("type", "text");
            toggleIcon.removeClass("bi-eye").addClass("bi-eye-slash");
        }
        else{
            passwordField.attr("type", "password");
            toggleIcon.removeClass("bi-eye-slash").addClass("bi-eye");
        }
    }
</script>
@endpush

@push('JSOR')
    // Toggle para mostrar/ocultar sección de contraseña
    $("#cambiarPassword").on("change", function(){
        const passwordSection = $("#passwordSection");
        const passwordInput = $("#password");
        const passwordConfirmInput = $("#password_confirmation");
        
        if( $(this).is(":checked") ){
            passwordSection.slideDown();
            passwordInput.attr("required", true);
            passwordConfirmInput.attr("required", true);
        }
        else {
            passwordSection.slideUp();
            passwordInput.attr("required", false).val("");
            passwordConfirmInput.attr("required", false).val("");
        }
    });
    
    // Toggle para mostrar/ocultar contraseña
    $("#togglePassword").on("click", function(){
        togglePasswordVisibility("#password", "#togglePassword");
    });
    
    $("#togglePasswordConfirm").on("click", function(){
        togglePasswordVisibility("#password_confirmation", "#togglePasswordConfirm");
    });
    
    // Manejo del formulario
    $("#formEditarUsuario").on("submit", function(e){
        e.preventDefault();
        cambiarEstadoBoton(true);
        $.ajax({
            url: $(this).attr("action"),
            method: "POST",
            data: $(this).serialize(),
            success: function(response){
                Swal.fire({
                    icon: "success",
                    title: "¡Usuario actualizado!",
                    text: "El usuario se ha actualizado correctamente",
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = "{{ route('usuarios.index') }}";
                });
            },
            error: function(xhr) {
                cambiarEstadoBoton(false);
                if( xhr.status === 422 ){
                    // Recargar para mostrar errores de validación
                    location.reload();
                }
                else{
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Ocurrió un problema al actualizar el usuario",
                        confirmButtonText: "Aceptar"
                    });
                }
            }
        });
    });
    
    console.log("Vista editar usuario cargada");
@endpush
