<?php
session_start();
include('conexao.php');

// 1. Proteção básica de sessão
if(!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// 2. Segurança Reforçada: Checa o tipo no banco em tempo real
// (Previne que um usuário bloqueado ou com permissão alterada continue acessando)
$id_usuario = $_SESSION['id'];
$sql_check = "SELECT TIPO, nome FROM users WHERE id = '$id_usuario'";
$result_check = $mysqli->query($sql_check);
$row_check = $result_check->fetch_assoc();

// Atualiza sessão com dados frescos
$tipo_usuario = $row_check['TIPO'];
$_SESSION['tipo'] = $tipo_usuario; 
$_SESSION['nome'] = $row_check['nome'];

// 3. Lógica de Títulos Dinâmicos
$titulo_dash = "Área do Estudante";
$subtitulo_dash = "Bem-vindo! Continue sua jornada de aprendizado.";

if($tipo_usuario == 'ADMIN') {
    $titulo_dash = "Painel Administrativo";
    $subtitulo_dash = "Gerencie cursos, turmas, professores e alunos.";
} elseif ($tipo_usuario == 'PROFESSOR') {
    $titulo_dash = "Área do Professor";
    $subtitulo_dash = "Gerencie suas turmas e faça o lançamento de chamadas.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <title>Expansão Cultural - <?php echo $titulo_dash; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
  
  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>
   
    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">
      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <div class="site-logo mr-auto w-25"><a href="dashboard.php">Expansão Cultural</a></div>

          <div class="mx-auto text-center">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block m-0 p-0">
                <li><a href="#home-section" class="nav-link">Início</a></li>
                <li><a href="#courses-section" class="nav-link">Cursos</a></li>
                
                <?php if($tipo_usuario == 'ADMIN'): ?>
                <li class="has-children">
                  <a href="#" class="nav-link" style="color: #ff5722;">Administrativo</a>
                  <ul class="dropdown">
                    <li><a href="admin_cursos.php">Gerenciar Cursos</a></li>
                    <li><a href="admin_turmas.php">Gerenciar Turmas/Alunos</a></li>
                    <li><a href="admin_professores.php">Gerenciar Professores</a></li>
                    <li><a href="professor_presenca.php">Lista de Presença (Geral)</a></li>
                  </ul>
                </li>
                <?php endif; ?>

                <?php if($tipo_usuario == 'PROFESSOR'): ?>
                <li class="has-children">
                  <a href="#" class="nav-link" style="color: #28a745;">Área do Professor</a>
                  <ul class="dropdown">
                    <li><a href="professor_turmas.php">Minhas Turmas</a></li>
                  </ul>
                </li>
                <?php endif; ?>

              </ul>
            </nav>
          </div>

          <div class="ml-auto w-25">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                <li class="has-children">
                    <a href="#" class="nav-link"><span>Olá, <?php echo $_SESSION['nome'] ?: 'Usuário'; ?></span></a>
                    <ul class="dropdown">
                        <li><a href="perfil.php">Meu Perfil</a></li>
                        <li><a href="logout.php">Sair</a></li>
                    </ul>
                </li>
              </ul>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a>
          </div>
        </div>
      </div>
    </header>

    <div class="intro-section" id="home-section">
      <div class="slide-1" style="background-image: url('images/hero_1.jpg');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12">
              <div class="row align-items-center">
                <div class="col-lg-12 mb-4 text-center">
                  
                  <h1 data-aos="fade-up" data-aos-delay="100"><?php echo $titulo_dash; ?></h1>
                  <p class="mb-4 lead" data-aos="fade-up" data-aos-delay="200"><?php echo $subtitulo_dash; ?></p>
                  
                  <?php if(empty($_SESSION['nome'])): ?>
                     <p data-aos="fade-up" data-aos-delay="300"><a href="perfil.php" class="btn btn-warning py-3 px-5 btn-pill">Complete seu Perfil</a></p>
                  <?php else: ?>
                     <?php if($tipo_usuario == 'PROFESSOR'): ?>
                        <p data-aos="fade-up" data-aos-delay="300"><a href="professor_turmas.php" class="btn btn-success py-3 px-5 btn-pill">Acessar Minhas Turmas</a></p>
                     <?php elseif($tipo_usuario == 'ADMIN'): ?>
                        <p data-aos="fade-up" data-aos-delay="300"><a href="admin_turmas.php" class="btn btn-danger py-3 px-5 btn-pill">Gerenciar Sistema</a></p>
                     <?php else: ?>
                        <p data-aos="fade-up" data-aos-delay="300"><a href="#courses-section" class="btn btn-primary py-3 px-5 btn-pill">Ver Meus Cursos</a></p>
                     <?php endif; ?>
                  <?php endif; ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section courses-title" id="courses-section">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 text-center">
            <h2 class="section-title">Cursos Disponíveis</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section courses-entry-wrap" data-aos="fade-up" data-aos-delay="100">
      <div class="container">
        <div class="row">
          <div class="owl-carousel col-12 nonloop-block-14">
            
            <?php
            // Busca cursos cadastrados no banco
            $sql_cursos = "SELECT * FROM cursos";
            $res_cursos = $mysqli->query($sql_cursos);

            if($res_cursos && $res_cursos->num_rows > 0):
                while($curso = $res_cursos->fetch_assoc()):
            ?>
            <div class="course bg-white h-100 align-self-stretch">
              <figure class="m-0">
                <a href="#"><img src="<?php echo !empty($curso['imagem']) ? $curso['imagem'] : 'images/img_1.jpg'; ?>" alt="Image" class="img-fluid"></a>
              </figure>
              <div class="course-inner-text py-4 px-4">
                <span class="course-price">Grátis</span>
                <div class="meta"><span class="icon-clock-o"></span> <?php echo $curso['horario']; ?></div>
                <h3><a href="#"><?php echo $curso['nome']; ?></a></h3>
                <p><?php echo substr($curso['descricao'], 0, 80) . '...'; ?></p>
              </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
                <p class="text-center w-100">Nenhum curso cadastrado ainda.</p>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>

    <div class="site-section" id="teachers-section">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 mb-5 text-center">
            <h2 class="section-title">Nossos Professores</h2>
            <p class="mb-5">Conheça a equipe de especialistas dedicada ao seu aprendizado na Expansão Cultural.</p>
          </div>
        </div>

        <div class="row">
          <?php
          // Busca apenas usuários do tipo PROFESSOR
          $sql_prof = "SELECT * FROM users WHERE TIPO = 'PROFESSOR'";
          $res_prof = $mysqli->query($sql_prof);

          if($res_prof && $res_prof->num_rows > 0):
              while($prof = $res_prof->fetch_assoc()):
                  // Define a foto
                  $foto = !empty($prof['urlfoto']) ? $prof['urlfoto'] : 'images/person_transparent.png';
          ?>
          
          <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="teacher text-center">
              <img src="<?php echo $foto; ?>" alt="Foto de <?php echo $prof['nome']; ?>" 
                   class="img-fluid w-50 rounded-circle mx-auto mb-4" 
                   style="width: 150px; height: 150px; object-fit: cover;">
              
              <div class="py-2">
                <h3 class="text-black"><?php echo $prof['nome']; ?></h3>
                <p class="position">Docente</p>
                <p class="small text-muted"><?php echo $prof['EMAIL']; ?></p>
              </div>
            </div>
          </div>

          <?php 
              endwhile; 
          else:
          ?>
              <div class="col-12 text-center">
                  <div class="alert alert-light">Nenhum professor listado no momento.</div>
              </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
    
    <footer class="footer-section bg-white">
      <div class="container">
        <div class="row pt-5 mt-5 text-center">
            <div class="col-md-12">
                <p>Copyright &copy; Expansão Cultural</p>
            </div>
        </div>
      </div>
    </footer>

  </div> 

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/main.js"></script>
  </body>
</html>