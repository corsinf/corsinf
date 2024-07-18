<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    section {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      min-height: 100vh;
      background: url(../img/inicio/login1.jpg);
      background-position: center;
      background-size: cover;
    }

    .contenedor {
      position: relative;
      width: 400px;
      border: 2px solid rgba(255, 255, 255, 6);
      border-radius: 20px;
      backdrop-filter: blur(15px);
      height: 450px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .contenedor h2 {
      font-size: 2.3rem;
      color: #fff;
      text-align: center;
    }

    .input-contenedor {
      position: relative;
      margin: 30px 0;
      width: 300px;
      border-bottom: 2px solid #fff;
    }

    .input-contenedor label {
      position: absolute;
      top: 50%;
      left: 5px;
      transform: translateY(-50%);
      color: #fff;
      font-size: 1rem;
      pointer-events: none;
      transition: .6s;
      font-weight: bold;
    }

    input:focus~label,
    input:valid~label {
      top: -5px;
    }

    .input-contenedor input {
      width: 100%;
      height: 50px;
      background-color: transparent;
      border: none;
      outline: none;
      font-size: 1rem;
      padding: - 35px 0;
      color: #fff;
    }

    .input-contenedor i {
      position: absolute;
      color: #fff;
      font-size: 1.6rem;
      top: 19px;
      right: 8px;
    }

    .olvidar {
      margin: -15px 0 15px;
      font-size: .9em;
      color: #fff;
      display: flex;
      justify-content: center;
    }

    .olvidar label input {
      margin: 3px;
    }

    .olvidar label a {
      color: #fff;
      text-decoration: none;
      transition: .3s;
      font-size: .9em;
    }

    .olvidar label a:hover {
      text-decoration: underline;
    }

    button {
      width: 100%;
      height: 45px;
      border-radius: 40px;
      background-color: #fff;
      border: none;
      font-weight: bold;
      cursor: pointer;
      outline: none;
      font-size: 1rem;
      transition: .4s;
    }

    button:hover {
      opacity: .9;
    }

    .registrar {
      font-size: .8rem;
      color: #fff;
      text-align: center;
      margin: 20px 0 10px;
    }

    .registrar p a {
      text-decoration: none;
      color: #fff;
      font-weight: bold;
      transition: .3s;
    }

    .registrar p a:hover {
      text-decoration: underline;
    }

    .formulario img {
      color: #fff;
    }
  </style>
  <title>Prueba login</title>
</head>

<body>
  <section>
    <div class="contenedor">
      <div class="formulario">
        <form action="#">
          <img src="../img/de_sistema/apudata_blanco.svg" width="300" alt="" />


          <div class="input-contenedor">
            <i class='bx bx-envelope'></i>
            <input type="text" name="txt_email" id="txt_email" value="" placeholder="" required>
            <label for="txt_email">Email</label>
          </div>

          <div class="input-contenedor">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" name="txt_contrasenia" id="txt_contrasenia" value="" placeholder="" required>
            <label for="txt_contrasenia">Constraseña</label>
          </div>
          <div class="olvidar">
            <label for="#">
              <input type="checkbox" name="cbx_olvidar" id="cbx_olvidar" value=""> Recordar
              <a href="#">Olvidé la contraseña</a>
            </label>
          </div>
        </form>

        <div>
          <button>Acceder</button>

          <div class="registrar">
            <p>No tengo Cuenta <a href="#">Crear una</a></p>
          </div>
        </div>

      </div>
    </div>
  </section>
</body>

</html>