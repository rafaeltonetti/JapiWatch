<?php
include 'conexao.php';

$mobile = FALSE;
$user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");
foreach($user_agents as $user_agent){
    if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
        if (isset($_SESSION["Nome_Completo"])) {
            $text_lado = $_SESSION["Nome_Completo"];
            $botao = "<a class='nav-link' href='logout.php'>Sair</a>";
        } else {
            $text_lado = "";
            $botao = "<a class='nav-link' href='login.php'>Login</a>";
        }
    } else {
        if (isset($_SESSION["Nome_Completo"])) {
            $text_lado = $_SESSION["Nome_Completo"];
            $botao = "<a class='btn btn-outline-danger' href='logout.php'>Sair</a>";
        } else {
            $text_lado = "";
            $botao = "<a class='btn btn-outline-success' href='login.php'>Login</a>";
        }
    }
}

$total_posts = $conn->query("SELECT COUNT(*) as total FROM postagem")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - JapiWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .hero-about {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('img/serra-bg.jpg');
            background-size: cover;
            color: white;
            padding: 5rem 0;
        }
        .creator-card {
            transition: transform 0.3s;
        }
        .creator-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-fluid gap-3">
            <a class="navbar-brand" style="margin-left: 30px" href="index.php">
                <img src="img/logo.png" alt="Logo" width="120" height="55" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item active">
                    <a class="nav-link" href="feed.php">Galeria</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="user.php">Meu Perfil</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3"><?= $text_lado ?></span>
                    <?= $botao ?>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="content">
            <div class="container col-md-5 col-sm-12 text-center">
                <h1>Sobre o JapiWatch</h1>
                <p>Conectando pessoas à biodiversidade da Serra do Japi</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a class="btn btn-success" href="feed.php">Explorar Galeria</a>
                    <a class="btn btn-success" href="form-img.php">Registrar Espécie</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="mb-4">Nosso Propósito</h2>
                <p class="fs-5">
                    O JapiWatch é uma plataforma colaborativa criada para documentar e preservar a rica biodiversidade 
                    da Serra do Japi. Através dos registros de cidadãos cientistas, pesquisadores e amantes da natureza, 
                    construímos um banco de dados vivo que auxilia em pesquisas e conservação ambiental.
                </p>
                
                <div class="card border-0 shadow-lg bg-dark text-white mt-5">
                    <div class="card-body py-5">
                        <h3 class="text-success">
                            <i class="bi bi-database"></i> 
                            <span id="counter"><?= $total_posts ?></span>+ registros
                        </h3>
                        <p class="mb-0">Contribuições feitas pela comunidade</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center mb-5">
                <h2>Desenvolvedores</h2>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card creator-card h-100 border-0 shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Rafael+C&background=0d6efd&color=fff&size=200" 
                         class="card-img-top rounded-circle mx-auto mt-3" style="width: 150px;" alt="Carlos">
                    <div class="card-body text-center">
                        <h5 class="card-title">Rafael Tonetti Cardoso</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card creator-card h-100 border-0 shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Caio+L&background=198754&color=fff&size=200" 
                         class="card-img-top rounded-circle mx-auto mt-3" style="width: 150px;" alt="Ana">
                    <div class="card-body text-center">
                        <h5 class="card-title">Caio Oshima de Lima</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card creator-card h-100 border-0 shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Mateus+C&background=fd7e14&color=fff&size=200" 
                         class="card-img-top rounded-circle mx-auto mt-3" style="width: 150px;" alt="Ricardo">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mateus Santos Carnaúba</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card creator-card h-100 border-0 shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Pedro+P&background=6f42c1&color=fff&size=200" 
                         class="card-img-top rounded-circle mx-auto mt-3" style="width: 150px;" alt="Mariana">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pedro Henrique de Jesus Pereira</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-4">
                <h5 class="text-uppercase mb-4">Sobre o JapiWatch</h5>
                <p>
                    Plataforma colaborativa para monitoramento da biodiversidade na Serra do Japi. 
                    Junte-se a nós nessa missão de preservação!
                </p>
                <div class="mt-3">
                    <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Links Rápidos</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Galeria</a></li>
                    <li class="mb-2"><a href="form-img.php" class="text-white text-decoration-none">Registrar Espécie</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Sobre o Projeto</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Termos de Uso</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Contato</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i> contato@japiwatch.com.br</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i> (11) 1234-5678</li>
                    <li><i class="bi bi-geo-alt me-2"></i> Jundiaí - SP, Brasil</li>
                </ul>
            </div>
        </div>

        <hr class="my-4 bg-light">

        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> JapiWatch. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const counter = document.getElementById('counter');
        const target = <?= $total_posts ?>;
        const duration = 0;
        const step = target / (duration / 16);
        
        let current = 0;
        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    </script>
</body>
</html>
