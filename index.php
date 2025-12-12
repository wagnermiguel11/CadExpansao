<?php
session_start();
// Se já estiver logado, redireciona para o painel (ajuste o destino se necessário)
if(isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit;
} 
include("conexao.php");
?>  
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <title>Expansao Cultural  &mdash; Website by Expansao Tech</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">
   
    <style>
        /* Ajuste visual para o link de alternância */
        .toggle-link {
            color: #7971ea;
            cursor: pointer;
            text-decoration: underline;
            font-weight: 600;
        }
        .toggle-link:hover {
            color: #584fce;
        }
        /* Garante que o box do formulário tenha altura dinâmica */
        .form-box {
            transition: all 0.3s ease;
        }
    </style>
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
          <div class="site-logo mr-auto w-25"><a href="index.html">Expansao cultural </a></div>

          <div class="mx-auto text-center">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block  m-0 p-0">
                <li><a href="#home-section" class="nav-link">Inicio</a></li>
                <li><a href="#courses-section" class="nav-link">Projetos</a></li>
                <li><a href="#programs-section" class="nav-link">Nossa Historia</a></li>
                <li><a href="#teachers-section" class="nav-link">Professores</a></li>
              </ul>
            </nav>
          </div>

          <div class="ml-auto w-25">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                <li class="cta"><a href="#contact-section" class="nav-link"><span>Contatos</span></a></li>
              </ul>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a>
          </div>
        </div>  
      </div>
      
    </header>

    <div class="intro-section" id="home-section">
      
      <div class="slide-1" style="background-image: url('images/expansaoed.png');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12">
              <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                  <h1  data-aos="fade-up" data-aos-delay="100">Quem somos nós</h1>
                  <p class="mb-4"  data-aos="fade-up" data-aos-delay="200">A ONG Expansão Cultural é uma instituição
                     independente, que atua com o objetivo de promover a inclusão e dignidade social como instrumento 
                      de humanização da população fragilizada da região do Capão Redondo. 
                      Inicialmente a organização atendia somente um grupo de mulheres que necessitavam de auxílio 
                      emocional principalmente, mas hoje a ONG acolhe um público diversificado visando dar assistência
                      a todos da comunidade que necessitam.
                      Atualmente a Expansão Cultural conta com uma UBS para garantir atendimento psicológico e com 
                      uma cozinha solidária (em parceria com a Prefeitura de São Paulo), onde uma média de 500 marmitas 
                      são produzidas diariamente por voluntárias e distribuídas de forma gratuita. Além disso, atividades 
                      extracurriculares como judô, capoeira, cursos voltados ao empreendedorismo, língua inglesa e espanhola 
                      também são ministradas na ONG. A população ainda pode participar do projeto Cozinha Escola, disponibilizado 
                      pela prefeitura gratuitamente e voltado para o ensino gastronômico.</p>
                  
                  <?php if(isset($_GET['erro'])): ?>
                    <div class="alert alert-danger" data-aos="fade-up">
                        <?php 
                        if($_GET['erro'] == 'existe') echo "Email já cadastrado. Faça Login.";
                        if($_GET['erro'] == 'senha') echo "As senhas não conferem.";
                        if($_GET['erro'] == 'login_falha') echo "Email ou senha incorretos.";
                        if($_GET['erro'] == 'erro') echo "Erro no sistema. Tente novamente.";
                        ?>
                    </div>
                  <?php endif; ?>

                  <?php if(isset($_GET['sucesso']) && $_GET['sucesso'] == 'ok'): ?>
                    <div class="alert alert-success" data-aos="fade-up">
                        Cadastro realizado com sucesso! Faça login.
                    </div>
                  <?php endif; ?>

                </div>

                <div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">
                  
                  <div class="form-box">
                    
                    <div id="div-login">
                        <h3 class="h4 text-black mb-4">Entrar</h3>
                        <form action="processa_login.php" method="post">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Seu Email" required>
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="senha" class="form-control" placeholder="Sua Senha" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-pill btn-block" value="Acessar Conta">
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <span class="text-muted small">Novo usuário?</span> 
                            <a class="toggle-link small" onclick="mostrarCadastro()">Cadastre-se aqui</a>
                        </div>
                    </div>

                    <div id="div-cadastro" style="display: none;">
                        <h3 class="h4 text-black mb-4">Criar Nova Conta</h3>
                        <form action="processa_cadastro.php" method="post">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="senha" class="form-control" placeholder="Crie uma Senha" required>
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="confirma_senha" class="form-control" placeholder="Confirme a Senha" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-pill btn-block" value="Cadastrar">
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <span class="text-muted small">Já possui conta?</span> 
                            <a class="toggle-link small" onclick="mostrarLogin()">Faça Login</a>
                        </div>
                    </div>

                  </div>
                </div>

              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
    <div class="site-section courses-title" id="courses-section" style="background-color: white;">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="">
            <h2 class="section-title" style="color: #371b9dff !important;">Nossos projetos</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section courses-entry-wrap"  data-aos="fade-up" data-aos-delay="100">
      <div class="container">
        <div class="row">

          <div class="owl-carousel col-12 nonloop-block-14">

            <div class="course bg-white h-100 align-self-stretch">
              <figure class="m-0"> 
                <a href="course-single.php?id_curso=idiomas"><img src="images/cursos-idiomas.jpg" alt="Image" class="img-fluid"></a>
              </figure>
              <div class="course-inner-text py-4 px-4">
                <span class="course-price">Gratuito</span>
                <div class="meta"><span class=></span></div>
                <h3><a href="#">Curso de Idiomas</a></h3>
                <p>Investimos em cursos de línguas (inglês e espanhol), para possibilitar o acesso do nosso público a outros idiomas. </p>
              </div>
              <div class="d-flex border-top stats">
              </div>
            </div>

            <div class="course bg-white h-100 align-self-stretch">
              <figure class="m-0">
                <a href="course-single.php?id_curso=saude"><img src="images/saude-mental.jpg" alt="Image" class="img-fluid"></a>
              </figure>
              <div class="course-inner-text py-4 px-4">
                <span class="course-price">Gratuito</span>
                <div class="meta"><span class=></span></div>
                <h3><a href="#">Saúde</a></h3>
                <p>Entendemos que cuidar da saúde é fundamental e por isso, disponibilizamos assistência médica em 
                  nossa organização para atender crianças, adultos e idosos e garantir seu bem-estar. </p>
              </div>
              <div class="d-flex border-top stats">
              </div>
            </div>

            <div class="course bg-white h-100 align-self-stretch">
              <figure class="m-0">
                <a href="course-single.php?id_curso=programacao"><img src="images/lanterna.webp" alt="Image" class="img-fluid"></a>
              </figure>
              <div class="course-inner-text py-4 px-4">
                <span class="course-price">Gratuito</span>
                <div class="meta"><span class="icon-clock-o"></span></div>
                <h3><a href="#">Programação</a></h3>
                <p>Um programador cria e mantém sistemas, sites e aplicativos, transformando necessidades em soluções 
                  tecnológicas. Ele desenvolve códigos, resolve problemas e garante que tudo funcione de forma eficiente 
                  e segura. É o profissional responsável por trazer ideias à vida no mundo digital. </p>
              </div>
              <div class="d-flex border-top stats">
              </div>
            </div>

            <div class="course bg-white h-100 align-self-stretch">
                <figure class="m-0">
                <a href="course-single.php?id_curso=capoeira">
                <img src="images/capoeira.avif" alt="Image" class="img-fluid">
                </a>
                </figure>
              <div class="course-inner-text py-4 px-4">
                <span class="course-price">Gratuito</span>
            <div class="meta"><span class="icon-clock-o"></span>Aulas todo sabado</div>
              <h3><a href="course-single.php?id_curso=capoeira">Capoeira</a></h3>
              <p>Ensinando a cultura das artes marciais </p>
            </div>
              <div class="d-flex border-top stats">
            </div>
          </div>

          </div>

        </div>
        <div class="row justify-content-center">
          <div class="col-7 text-center">
            <button class="customPrevBtn btn btn-primary m-1">Anterior</button>
            <button class="customNextBtn btn btn-primary m-1">Proximo</button>
          </div>
        </div>
      </div>
    </div>


    <div class="site-section" id="programs-section">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 text-center"  data-aos="fade-up" data-aos-delay="">
            <h2 class="section-title">Nossa Historia</h2>
          </div>
        </div>
      </div>
    </div>

    </div>

    <div class="site-section bg-image overlay" style="background-image: url('images/expansaoed.png');">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-8 text-center testimony">
            <img src="images/selma.png" alt="Image" class="img-fluid w-25 mb-4 rounded-circle">
            <h3 class="mb-4">Selma Tristao</h3>
            <blockquote>
              <p>&ldquo; A Expansão Cultural surgiu através da ideia de Selma Tristão, moradora do Capão Redondo, que 
                viu nas mulheres que frequentavam sua igreja o anseio e a necessidade de compartilharem suas aflições e 
                problemas diários. Com isso, Selma organizou então um chá da tarde e conseguiu reunir mais de 300 participantes.
                A partir disso, a fundadora percebeu que as mulheres necessitavam de cuidados que fossem além do 
                compartilhamento de suas necessidades, mas de ações que buscassem resgatar a identidade delas e que assim, 
                pudessem se sentir seguras e acolhidas. Selma através disso, buscou auxílio de psicólogas, outras pastoras e 
                coachings e criou a partir disso o Mulheres em Expansão, com o intuito de atingir outras mulheres de mais igrejas 
                que partilhassem de realidades semelhantes.
                Conforme o projeto se desenvolvia, Selma pensou em formas de promover também o conhecimento para esse 
                grupo de mulheres e iniciou a busca por cursos e especializações que elas pudessem fazer em diferentes 
                áreas, tudo de forma gratuita. Através desse crescimento, surgiu então a necessidade de um espaço melhor 
                para atendê-las.
                A divulgação do projeto se deu através da atitude da fundadora do projeto de sair caminhando nas ruas
                e convidando mulheres a participar das atividades oferecidas. E foi durante o acompanhamento de uma sessão 
                de terapia, ao ouvir o desabafo de uma paciente, que Selma compreendeu que o amparo de seu projeto necessitava 
                atingir além das mulheres atendidas, era importante acolher também suas famílias, que faziam parte das preocupações 
                do grupo. A partir desse momento, surge a Expansão Cultural. &rdquo;</p>
            </blockquote>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section bg-light" id="contact-section">
      <div class="container">

        <div class="row justify-content-center">
          <div class="col-md-7">


            
            <h2 class="section-title mb-3">Contacte-nos</h2>
            <p class="mb-5"></p>
          
            <form method="post" data-aos="fade">
              <div class="form-group row">
                <div class="col-md-6 mb-3 mb-lg-0">
                  <input type="text" class="form-control" placeholder="Primeiro nome">
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Sobrenome ">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Assunto">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <input type="email" class="form-control" placeholder="Email">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12">
                  <textarea class="form-control" id="" cols="30" rows="10" placeholder="Digite a sua mensagem:"></textarea>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  
                  <input type="submit" class="btn btn-primary py-3 px-5 btn-block btn-pill" value="Enviar">
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
    
     
    <footer class="footer-section bg-white">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h3>Sobre a Expansão</h3>
            <p>A ONG Expansão Cultural apoia a comunidade do Capão Redondo e Jardim são Luiz com ações de inclusão social, 
              oferecendo atendimento psicológico, distribuição diária de marmitas e atividades como cursos, esportes e 
              formação gastronômica gratuita.</p>
          </div>

          <div class="col-md-3 ml-auto">
            <h3>Links</h3>
            <ul class="list-unstyled footer-links">
              <li><a href="#">Inicio</a></li>
              <li><a href="#">Nossos projeto</a></li>
              <li><a href="#">Nossa historia</a></li>
            </ul>
          </div>

          <div class="col-md-4">
            <h3>Venha fazer parte também</h3>
            <p>Junte-se a nós e tenha acesso a cursos, programas e uma comunidade engajada. O cadastro é rápido e gratuito!</p>
            <div class="mb-5">
              <a href="cadastro.php" class="btn btn-primary rounded-0 py-3 px-5">
            Cadastre-se Agora
              </a>
              </div>
          </div>

        </div>

<div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
              <p>
                Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
                </p>
            </div>
          </div>
        </div>
      </div>
    </footer>

  </div> <script src="js/jquery-3.3.1.min.js"></script>
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
 
 <script>
    function mostrarCadastro() {
        $('#div-login').fadeOut(300, function(){
            $('#div-cadastro').fadeIn(300);
        });
    }

    function mostrarLogin() {
        $('#div-cadastro').fadeOut(300, function(){
            $('#div-login').fadeIn(300);
        });
    }

    // NOVO CÓDIGO: Verifica se o usuário veio de uma página de curso para se cadastrar
    $(document).ready(function() {
        // Pega os parâmetros da URL (ex: ?foco=cadastro)
        const urlParams = new URLSearchParams(window.location.search);
        
        // Se o parâmetro "foco" for igual a "cadastro"
        if (urlParams.get('foco') === 'cadastro') {
            // Esconde o formulário de Login
            $('#div-login').hide();
            // Mostra o formulário de Cadastro
            $('#div-cadastro').show();
        }
    });
 </script>
    
 </body>
</html>