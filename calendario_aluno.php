<?php
// Inicia a sessão
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: login.html");
    exit();
}
?>  
  <!doctype html>
  <html lang="pt-br">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta content="width=device-width, initial-scale=1.0">
      <meta name="keywords" content="calendar, events, reminders, javascript, html, css, open source coding">
    
      <title>Calendário</title>

    <!--links de estilos-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/style_calendar.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    </head>
    <body class="main" style="padding-top: 56px;">
      <!--navbar-->
      <nav class="navbar fixed-top navbar-expand-lg background_blue">
          <div class="container-fluid">
            <a class="navbar-brand text-white" href="calendario_aluno.php?turma=">Oblivion</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </div>
        </nav>
      <!--container-->
          <div class="container">
            <div class="left">
              <div class="calendar">
                <div class="month">
                  <i class="fas fa-angle-left prev"></i>
                  <div class="date"></div>
                  <i class="fas fa-angle-right next"></i>
                </div>
                <div class="weekdays">
                  <div>Dom</div>
                  <div>Seg</div>
                  <div>Ter</div>
                  <div>Qua</div>
                  <div>Qui</div>
                  <div>Sex</div>
                  <div>Sáb</div>
                </div>
                <div class="days">
                 
            
                </div>
              
              
                <div class="goto-today">
                  <div class="goto">
                    <input type="text" placeholder="mm/yyyy" class="date-input" />
                    <button class="goto-btn">Ir</button>
                  </div>
                  <button class="today-btn">Hoje</button>
                </div>
              </div>
            </div>
            
            <!--consulta do calendario-->
            <div class="right">
              <div class="today-date">
                <div class="event-day"></div>
                <div class="event-date"></div>
              </div>
              <div class="events"></div>
 
      <script src="javascript/scriptv2.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      <script>
        // Máscara de CPF (exemplo para página de cadastro/login)
        document.addEventListener('DOMContentLoaded', function() {
          var cpfInput = document.getElementById('cpf');
          if (cpfInput) {
            cpfInput.addEventListener('input', function (e) {
              let value = e.target.value.replace(/\D/g, '').substring(0, 11);
              if (value.length > 9)
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
              else if (value.length > 6)
                value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
              else if (value.length > 3)
                value = value.replace(/(\d{3})(\d+)/, '$1.$2');
              e.target.value = value;
            });
          }
        });
      </script>
    </body>
  </html>
