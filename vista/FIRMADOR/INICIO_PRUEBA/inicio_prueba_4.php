<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Prueba 2</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #081b29;
        }

        .wrapper {
            position: relative;
            width: 750px;
            height: 450px;
            background: transparent;
            border: 2px solid #0ef;
            box-shadow: 0 0 25px #0ef;
            overflow: hidden;
        }

        .wrapper .form-box {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .wrapper .form-box.login {
            left: -20px;
            padding: 0 60px 0px;
        }

        .wrapper .form-box.login .animation {
            transform: translateX(0);
            opacity: 1;
            filter: blur(0);
            transition: .7s ease;
            transition-delay: calc(.1s * var(--j));
        }

        .wrapper.active .form-box.login .animation {
            transform: translateX(-120%);
            opacity: 0;
            filter: blur(10px);
            transition-delay: calc(.1s * var(--i));
        }


        .wrapper .form-box.register {
            right: 50px;
            padding: 0px 40px 0 0;
            pointer-events: none;
        }

        .wrapper.active .form-box.register {
            pointer-events: auto;
        }

        .wrapper .form-box.register .animation {
            transform: translateX(120%);
            opacity: 0;
            filter: blur(10px);
            transition: .8s ease;
            transition-delay: calc(.1s * var(--j));
        }

        .wrapper.active .form-box.register .animation {
            transform: translateX(0);
            opacity: 1;
            filter: blur(0);
            transition-delay: calc(.1s * var(--i));
        }

        .wrapper .form-box.users {
            left: -20px;
            padding: 0 60px 0px;
            pointer-events: none;
        }

        .wrapper.active-users .form-box.users {
            pointer-events: auto;
        }

        .wrapper .form-box.users .animation {
            transform: translateX(-120%);
            opacity: 0;
            filter: blur(10px);
            transition: .8s ease;
            transition-delay: calc(.1s * var(--j));
        }

        .wrapper.active-users .form-box.users .animation {
            transform: translateX(0);
            opacity: 1;
            filter: blur(0);
            transition-delay: calc(.12s * var(--i));
        }

        .wrapper.active-empresas .form-box.register .animation {
            transform: translateX(120%);
            opacity: 0;
            filter: blur(10px);
            transition-delay: calc(.02s * var(--i));
        }

        .form-box h2 {
            font-size: 32px;
            color: #fff;
            text-align: center;
        }

        .form-box .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 25px 0;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            border-bottom: 2px solid #fff;
            padding-right: 23px;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            transition: .5s;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-bottom-color: #0ef;
        }

        input {
            caret-color: #fff;
            text-shadow: 0 0 1px rgba(255, 255, 255, 0.5);
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #081b29 inset !important;
            -webkit-text-fill-color: #fff !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .input-box input:-webkit-autofill~label,
        .input-box input:-webkit-autofill:hover~label,
        .input-box input:-webkit-autofill:focus~label,
        .input-box input:-webkit-autofill:active~label {
            top: -5px;
            color: #0ef;
        }

        .input-box input:-webkit-autofill~i,
        .input-box input:-webkit-autofill:hover~i,
        .input-box input:-webkit-autofill:focus~i,
        .input-box input:-webkit-autofill:active~i {
            color: #0ef;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            font-size: 16px;
            color: #fff;
            pointer-events: none;
            transition: .5s;
            z-index: 1;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -5px;
            color: #0ef;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            font-size: 18px;
            color: #fff;
            transition: .5s;
        }

        .input-box input:focus~i,
        .input-box input:valid~i {
            color: #0ef;
        }

        .card-option {
            width: 120%;
            min-width: 250px;
            height: 60px;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            overflow: hidden;
            padding: 10px;
        }

        .card-option:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-option .product-img {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 40px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .card-option .product-img img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .card-option .badge {
            font-size: 0.9em;
            padding: 4px 8px;
        }

        .card-option .flex-grow-1 {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
        }

        .card-option .col-sm-3 {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }


        .col-md-4 {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn {
            position: relative;
            width: 100%;
            height: 45px;
            background: transparent;
            border: 2px solid #0ef;
            outline: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            z-index: 1;
            overflow: hidden;
            align-self: center;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: -100%;
            left: 0;
            width: 100%;
            height: 300%;
            background: linear-gradient(#081b29, #0ef,
                    #081b29, #0ef);
            z-index: -1;
            transition: .5s;
        }

        .btn:hover::before {
            top: 0;
        }

        .wrapper .info-text {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .wrapper .info-text.login {
            right: 0;
            text-align: right;
            padding: 0 40px 60px 135px;
        }

        .wrapper .info-text.login .animation {
            transform: translateX(0);
            opacity: 1;
            filter: blur(0);
            transition: .7s ease;
            transition-delay: calc(.1s * var(--j));
        }

        .wrapper.active .info-text.login .animation {
            transform: translateX(120%);
            opacity: 0;
            filter: blur(10px);
            transition-delay: calc(.1s * var(--i));
        }

        .wrapper .info-text.register {
            left: -30px;
            text-align: left;
            padding: 0 40px 60px 60px;
            pointer-events: none;
        }

        .wrapper.active .info-text.register {
            pointer-events: auto;
        }

        .wrapper .info-text.register .animation {
            transform: translateX(-120%);
            opacity: 0;
            filter: blur(10px);
            transition: .8s ease;
            transition-delay: calc(.1s * var(--j));
        }

        .wrapper.active .info-text.register .animation {
            transform: translateX(0);
            opacity: 1;
            filter: blur(0);
            transition-delay: calc(.1s * var(--i));
        }

        .wrapper.active-empresas .info-text.register .animation {
            transform: translateX(-120%);
            opacity: 1;
            filter: blur(10px);
            transition-delay: calc(.02s * var(--i));
        }

        .wrapper .info-text.users {
            right: -350px;
            text-align: right;
            padding: 0 0px 0px -130px;
            pointer-events: none;
        }
        .wrapper.active-users .info-text.users {
            pointer-events: auto;
        }

        .wrapper .info-text.users .animation {
            transform: translateX(-120%);
            opacity: 0;
            filter: blur(10px);
            transition: .7s ease;
            transition-delay: calc(.1s * var(--j));
        }

        .wrapper.active-users .info-text.users .animation {
            transform: translateX(0);
            opacity: 1;
            filter: blur(0);
            transition-delay: calc(.1s * var(--i));
        }

        .info-text h3 {
            font-size: 20px;
            color: #fff;
            line-height: 1.3;
            text-transform: uppercase;
        }

        .info-text p {
            font-size: 16px;
            color: #fff;
        }

        .wrapper .bg-animate {
            position: absolute;
            top: -200px;
            right: -80px;
            width: 1000px;
            height: 850px;
            background: linear-gradient(45deg, #081b29, #0ef);
            border-bottom: 3px solid #0ef;
            transform: rotate(18deg) skewY(40deg) translateY(150px);
            /*transform: rotate(0) skewY(0);*/
            transform-origin: bottom right;
            transition: 1.5s ease;
            transition-delay: 1.6s;
        }

        .wrapper.active .bg-animate {
            transform: rotate(0) skewY(0);
            transition-delay: .5s;
        }

        .wrapper .bg-animate2 {
            position: absolute;
            top: 100%;
            left: 850px;
            width: 1000px;
            height: 900px;
            background: #081b29;
            border-top: 3px solid #0ef;
            /*transform: rotate(-11deg) skewY(-41deg);*/
            transform: rotate(0) skewY(0);
            transform-origin: bottom left;
            transition: 1.5s ease;
            transition-delay: .5s;
        }

        .wrapper.active .bg-animate2 {
            transform: rotate(-30deg) skewY(-60deg) translateY(-200px) translateX(-400px);
            transition-delay: 1.2s;
        }

        .wrapper.active-users .bg-animate {
            transform: rotate(30deg) skewY(60deg) translateY(700px) translateX(100px);
            transition-delay: 1.8s;
        }

        .wrapper.active-users .bg-animate2 {
            transform: rotate(0deg) skewY(0deg);
            transition-delay: .8s;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>
        <!-- <span class="bg-animate2"></span> -->

        <!-- Login -->
        <div class="form-box login">
            <img src="../img/de_sistema/apudata_blanco.svg" class="animation" style="--i:0; --j:21;" width="215" alt="" />
            <form action="#">
                <div class="input-box animation" style="--i:1; --j:22">
                    <input type="text" required autofocus>
                    <label>Usuario</label>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box animation" style="--i:2; --j:23">
                    <input type="password" required>
                    <label>Contraseña</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="button" class="btn animation ingresar-link" style="--i:3; --j:24">Ingresar</button>
            </form>
        </div>
        <div class="info-text login">
            <h3 class="animation" style="--i:1; --j:21;">Soluciones de desarrollo de Software.</h3>
        </div>

        <!-- Empresas -->
        <div class="form-box register">
            <h2 class="animation" style="--i:17; --j:0;">Empresa</h2>
            <form action="#">
                <div class="input-box animation pb-2" style="--i:18; --j:1;">
                    <div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer card-option user-link">
                        <div class="col-sm-9 d-flex align-items-center">
                            <div class="product-img me-3">
                                <img src="../img/de_sistema/sin-logo.png" alt="">
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Facturación</h6>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-center justify-content-end">
                            <div class="badge rounded-pill bg-success">Ingresar</div>
                        </div>
                    </div>
                </div>
                <div class="input-box animation pb-2" style="--i:19; --j:2;">
                    <div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer card-option user-link">
                        <div class="col-sm-9 d-flex align-items-center">
                            <div class="product-img me-3">
                                <img src="../img/de_sistema/sin-logo.png" alt="">
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Desarrollo</h6>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-center justify-content-end">
                            <div class="badge rounded-pill bg-success">Ingresar</div>
                        </div>
                    </div>
                </div>
                <div class="input-box animation pb-4" style="--i:20; --j:3;">
                    <div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer card-option user-link">
                        <div class="col-sm-9 d-flex align-items-center">
                            <div class="product-img me-3">
                                <img src="../img/de_sistema/sin-logo.png" alt="">
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Hospital</h6>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-center justify-content-end">
                            <div class="badge rounded-pill bg-success">Ingresar</div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="position-relative">
                    <button type="button" class="btn animation back-link position-absolute top-50 start-50 translate-middle" style="--i:21; --j:4;">Volver</button>
                </div>
            </form>
        </div>
        <div class="info-text register">
            <img src="../img/de_sistema/apudata_blanco.svg" class="animation" style="--i:17; --j:0;" width="180" alt="" />
            <p class="animation" style="--i:18; --j:1;">Soluciones de desarrollo de Software.</p>
        </div>

        <!-- Usuarios -->
        <div class="form-box users">
            <h2 class="animation" style="--i:17; --j:0;">Usuarios</h2>
            <form action="#">
                <div class="input-box animation pb-2" style="--i:18; --j:1">
                    <div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer card-option">
                        <div class="col-sm-9 d-flex align-items-center">
                            <div class="product-img me-3">
                                <img src="../img/de_sistema/sin-logo.png" alt="">
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Médico</h6>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-center justify-content-end">
                            <div class="badge rounded-pill bg-success">Ingresar</div>
                        </div>
                    </div>
                </div>
                <div class="input-box animation pb-2" style="--i:19; --j:2">
                    <div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer card-option">
                        <div class="col-sm-9 d-flex align-items-center">
                            <div class="product-img me-3">
                                <img src="../img/de_sistema/sin-logo.png" alt="">
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Estudiante</h6>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-center justify-content-end">
                            <div class="badge rounded-pill bg-success">Ingresar</div>
                        </div>
                    </div>
                </div>
                <div class="input-box animation pb-2" style="--i:20; --j:3">
                    <div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer card-option">
                        <div class="col-sm-9 d-flex align-items-center">
                            <div class="product-img me-3">
                                <img src="../img/de_sistema/sin-logo.png" alt="">
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Profesor</h6>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-center justify-content-end">
                            <div class="badge rounded-pill bg-success">Ingresar</div>
                        </div>
                    </div>
                </div>
                <br>
                <button type="button" class="btn animation back-link1 mt-2" style="--i:21; --j:4">Volver</button>
            </form>
            <div class="info-text users">
                <img src="../img/de_sistema/apudata_blanco.svg" class="animation" style="--i:17; --j:0;" width="180" alt="" />
                <p class="animation" style="--i:18; --j:1;">Soluciones de desarrollo de Software.</p>
            </div>
        </div>

    </div>
    <script>
        const wrapper = document.querySelector('.wrapper');
        const ingresarLink = document.querySelector('.ingresar-link');
        const backLinkIngreso = document.querySelectorAll('.back-link');
        const backLinkEmpresas = document.querySelectorAll('.back-link1');
        const userLinks = document.querySelectorAll('.user-link')

        ingresarLink.onclick = () => {
            wrapper.classList.add('active');
            wrapper.classList.remove('active-users');
        }

        userLinks.forEach(link => {
            link.onclick = () => {
                wrapper.classList.add('active-users');
                wrapper.classList.add('active-empresas');
            }
        });

        backLinkIngreso.forEach(link => {
            link.onclick = () => {
                wrapper.classList.remove('active');
            }
        });

        backLinkEmpresas.forEach(link => {
            link.onclick = () => {
                wrapper.classList.remove('active-users');
                wrapper.classList.remove('active-empresas');
            }
        });
    </script>
</body>


</html>