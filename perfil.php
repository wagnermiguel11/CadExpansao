<?php
session_start();
include('conexao.php');

// Segurança: Se não estiver logado, manda pro login
if(!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Busca os dados atualizados do usuário no banco
$sql = "SELECT * FROM users WHERE id = '$id_usuario'";
$result = $mysqli->query($sql);
$user = $result->fetch_assoc();

// Define foto padrão se não tiver
$foto_perfil = !empty($user['urlfoto']) ? $user['urlfoto'] : 'images/person_transparent.png';
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <title>Meu Perfil - Expansão Cultural</title>
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
    
    <style>
        .profile-header {
            background-image: url('images/hero_1.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0 50px 0;
            margin-bottom: 50px;
            color: white;
            text-align: center;
        }
        .profile-img-container {
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid rgba(255,255,255,0.5);
            background: #fff;
        }
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-nav .nav-link {
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid transparent;
        }
        .profile-nav .nav-link.active {
            color: #7971ea;
            border-bottom: 2px solid #7971ea;
            background: none;
        }
        .data-label {
            font-weight: bold;
            color: #7971ea;
            font-size: 0.9em;
            text-transform: uppercase;
        }
        .data-value {
            font-size: 1.1em;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
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
          <div class="site-logo mr-auto w-25"><a href="index.php">Expansão Cultural</a></div>
          <div class="mx-auto text-center">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block m-0 p-0">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="perfil.php" class="nav-link active">Meu Perfil</a></li>
              </ul>
            </nav>
          </div>
          <div class="ml-auto w-25">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                <li class="cta"><a href="logout.php" class="nav-link"><span>Sair</span></a></li>
              </ul>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a>
          </div>
        </div>
      </div>
    </header>

    <div class="profile-header">
        <div class="container">
            <div class="profile-img-container">
                <img src="<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="profile-img">
            </div>
            <h2>Olá, <?php echo !empty($user['nome']) ? $user['nome'] : 'Estudante'; ?>!</h2>
            <p><?php echo $user['EMAIL']; ?></p>
        </div>
    </div>

    <div class="container pb-5">
        
        <?php if(isset($_GET['status'])): ?>
            <?php if($_GET['status'] == 'sucesso'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Dados atualizados com sucesso!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php elseif($_GET['status'] == 'erro_upload'): ?>
                <div class="alert alert-warning" role="alert">
                    Dados salvos, mas houve um erro ao enviar a imagem. Verifique o tamanho ou formato.
                </div>
            <?php elseif($_GET['status'] == 'erro'): ?>
                <div class="alert alert-danger" role="alert">
                    Erro ao atualizar o banco de dados. Tente novamente.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <ul class="nav nav-tabs profile-nav mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="view-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="true">Meus Dados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">Editar Perfil</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="view-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div class="data-label">Nome Completo</div>
                        <div class="data-value"><?php echo $user['nome'] ?: '<span class="text-muted">Não informado</span>'; ?></div>

                        <div class="data-label">CPF</div>
                        <div class="data-value"><?php echo $user['idcpf'] ?: '<span class="text-muted">Não informado</span>'; ?></div>

                        <div class="data-label">Data de Nascimento</div>
                        <div class="data-value"><?php echo $user['dataNascimento'] ? date('d/m/Y', strtotime($user['dataNascimento'])) : '<span class="text-muted">Não informado</span>'; ?></div>

                        <div class="data-label">Sexo</div>
                        <div class="data-value"><?php echo $user['SEXO'] ?: '<span class="text-muted">-</span>'; ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="data-label">Telefone</div>
                        <div class="data-value"><?php echo $user['telefone'] ?: '<span class="text-muted">Não informado</span>'; ?></div>

                        <div class="data-label">Endereço</div>
                        <div class="data-value">
                            <?php 
                                echo $user['endereco'];
                                echo $user['numero'] ? ', '.$user['numero'] : '';
                                echo $user['cidade'] ? ' - '.$user['cidade'] : '';
                                echo $user['UF'] ? '/'.$user['UF'] : '';
                            ?>
                        </div>

                        <div class="data-label">CEP</div>
                        <div class="data-value"><?php echo $user['CEP'] ?: '<span class="text-muted">Não informado</span>'; ?></div>

                        <div class="data-label">Email (Login)</div>
                        <div class="data-value"><?php echo $user['EMAIL']; ?></div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <form action="processa_perfil.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3 text-black">Dados Pessoais</h4>
                            
                            <div class="form-group">
                                <label>Foto de Perfil</label>
                                <input type="file" name="foto" class="form-control-file border">
                                <small class="form-text text-muted">Formatos JPG ou PNG.</small>
                            </div>

                            <div class="form-group">
                                <label>Nome Completo</label>
                                <input type="text" name="nome" class="form-control" value="<?php echo $user['nome']; ?>">
                            </div>

                            <div class="form-group">
                                <label>CPF (Apenas números)</label>
                                <input type="text" name="idcpf" class="form-control" maxlength="11" value="<?php echo $user['idcpf']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Data de Nascimento</label>
                                <input type="date" name="dataNascimento" class="form-control" value="<?php echo $user['dataNascimento']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Sexo</label>
                                <select name="SEXO" class="form-control">
                                    <option value="" <?php echo $user['SEXO'] == '' ? 'selected' : ''; ?>>Selecione</option>
                                    <option value="M" <?php echo $user['SEXO'] == 'M' ? 'selected' : ''; ?>>Masculino</option>
                                    <option value="F" <?php echo $user['SEXO'] == 'F' ? 'selected' : ''; ?>>Feminino</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Telefone / Celular</label>
                                <input type="text" name="telefone" class="form-control" value="<?php echo $user['telefone']; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4 class="mb-3 text-black">Endereço</h4>

                            <div class="form-group">
                                <label>CEP</label>
                                <input type="text" name="CEP" class="form-control" maxlength="8" value="<?php echo $user['CEP']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Endereço (Rua, Av...)</label>
                                <input type="text" name="endereco" class="form-control" value="<?php echo $user['endereco']; ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Número</label>
                                    <input type="text" name="numero" class="form-control" value="<?php echo $user['numero']; ?>">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Cidade</label>
                                    <input type="text" name="cidade" class="form-control" value="<?php echo $user['cidade']; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Estado (UF)</label>
                                <input type="text" name="UF" class="form-control" maxlength="2" style="text-transform:uppercase" value="<?php echo $user['UF']; ?>">
                            </div>

                            <div class="form-group mt-4">
                                <label>Email (Não editável)</label>
                                <input type="text" class="form-control" value="<?php echo $user['EMAIL']; ?>" disabled>
                                <small>Para mudar o email ou senha, contate o suporte.</small>
                            </div>

                            <div class="form-group mt-4">
                                <input type="submit" class="btn btn-primary btn-pill btn-block" value="Salvar Alterações">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <footer class="footer-section bg-white">
      <div class="container">
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
            <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Expansão Cultural</p>
            </div>
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