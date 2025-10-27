@extends('layouts.baseacceso')

@section('titulo', 'Iniciar Sesión')

@push('CSS')
<style>
    .login-container{
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .login-card{
        backdrop-filter: blur(10px);
        background: rgba(255, 205, 205, 0.95);
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    .brand-section {
        background: rgba(255, 205, 205, 0.1);
        backdrop-filter: blur(10px)
        border-radius: 15px;
    }
</style>
@endpush

@section('contenido')
<div class="login-container d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 login-card">

                    <div class="col-md-6 d-flex align-items-center justify-content-center brand-section text-black p-5">
                        <div class="text-center">
                            <h1 class="display-4 mb-4">
                                <i class="bi bi-mortarboard-fill"></i>
                            </h1>
                            <h2 class="mb-3">Sistema de Calificaciones</h2>
                            <p class="lead">Gestiona las calificaciones de manera eficiente y segura</p>
                        </div>
                    </div>

                    <div class="col-md-6 p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">Iniciar Sesión</h3>
                            <p class="text-muted">Ingresa tus credenciales para acceder</p>
                        </div>
                        <form id="frmAcceso" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-primary"></i>Correo Electrónico
                                </label>
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="ejemplo@correo.com"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock text-primary"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        placeholder="Ingresa tu contraseña"
                                        required>
                                    <button class="btn btn-outline-secondary" type="button" id="tooglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                               
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="btnAcceso">
                                    <i class="bi bi-box-arrow-in-right"></i>Iniciar Sesión
                                </button>
                            </div>

                        </form>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i>Acceso seguro y protegido
                            </small>
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
        const btn = $("#btnAcceso");
            if (cargando) {
                btn.html('<i class="bi bi-box-hourglass-split"></i>Verificando...').prop("disable", true);
            }
            else{
                btn.html('<i class="bi bi-box-arrow-in-right"></i>Iniciar Sesión').prop("disable", false);
            }
    }
</script>
@endpush

@push('JSOR')
$('#tooglePassword').on("click", function(){
    const passwordField = $("#password");
    const passwordFieldType = passwordField.attr("type"); 
    const toogleIcon = $(this).find('i');

    if( passwordFieldType === "password" ){
        passwordField.attr("type", "text");
        toogleIcon.removeClass("bi-eye").addClass("bi-eye-slash");
    }
    else{
        passwordField.attr("type", "password");
        toogleIcon.removeClass("bi-eye-slash").addClass("bi-eye");
    }
});

$('#frmAcceso').on("submit", function(e){
    e.preventDefault();
    cambiarEstadoBoton(true);
    $.ajax({
        url: $(this).attr("action"),
        method: "POST",
        data: $(this).serialize(),
        success: function(response){
            Swal.fire({
                icon: "success",
                title: "Bienvenido",
                text: "Acceso concedido correctamente",
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true
            }).then(() => {
                window.location.href = response.redirect || "/dashboard";
            });
        },
        error: function(xhr){
            cambiarEstadoBoton(false);
            if( xhr.status == 422 ){
                location.reload();
            }
            else if( xhr.status == 401 ){
                Swal.fire({
                    icon: "error",
                    title: "Acceso Denegado",
                    text: "Las credenciales ingresadas son incorrectas",
                    confirmButtonText: "Intentar de nuevo"
            });
            }
            else{
                wal.fire({
                    icon: "error",
                    title: "Error del sistema",
                    text: "Ocurrió un problema, intenta más tarde",
                    confirmButtonText: "Aceptar "
            });
        }
    }
    });
});
@endpush