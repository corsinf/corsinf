<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../assets/login_v2/login_v2.css">
  <title>Prueba login</title>
</head>
<style>
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
</style>
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