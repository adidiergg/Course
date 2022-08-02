function registro_correcto() {

swal({
  title: 'Registrado correctamente',
  icon:'success'


});
}

function hubo_fallo(fallo) {

  swal({
    title: 'Hubo un error',
    text:fallo,
    icon:'error'

  });
  }
