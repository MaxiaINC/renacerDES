<?php

function codificar_pdf_a_base64($archivo_pdf) {
  // Lee el contenido del archivo PDF
  $contenido_pdf = file_get_contents($archivo_pdf);

  // Codifica el contenido a base64
  $base64_pdf = base64_encode($contenido_pdf);

  return $base64_pdf;
}

// Uso de la función para codificar un archivo PDF llamado "ejemplo.pdf"
$archivo_pdf = 'resolucion_20230217_04_02_15.pdf';
$base64_pdf = codificar_pdf_a_base64($archivo_pdf);

// Imprime el resultado
echo $base64_pdf;