<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>Apadrina un árbol | Colaboradores</title>
</head>
<body>
    <?php
    include("../includes/header.php");
    ?>
    <nav class="breadcrumbs container">
        <a href="/Arbol/index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Colaboradores</span>
    </nav>
    <section class="colaboradores container">
        <header class="colaboradores-text">
            <h1>Protectores de la <span> Naturaleza</span></h1>
            <!-- <p>Reconocimiento a quienes más árboles han apadrinado en 2026</p> -->
        </header>

        <div class="colaboradores-content">
            <article class="colaboradores-row">

                <img src="../assets/img/about/nosotros-img.jpg" alt="Colaborador destacado">
                <h3>Ana Martínez</h3>
                <span class="tree-count">Más donado</span>
                <p>Comprometida con los árboles</p>
            </article>

            <article class="colaboradores-row">
                <img src="../assets/img/about/nosotros-img.jpg" alt="Colaborador">
                <h3>Carlos Ruiz</h3>
                <span class="tree-count">Más donado</span>
                <p>Líder comunitario en proyectos de conservación.</p>
            </article>

            <article class="colaboradores-row">
                <img src="../assets/img/about/nosotros-img.jpg" alt="Colaborador">
                <h3>Dana Gómez</h3>
                <span class="tree-count">Más donado</span>
                <p>Apasionada por la biodiversidad y el clima.</p>
            </article>
            <article class="colaboradores-row">
                <img src="../assets/img/about/nosotros-img.jpg" alt="Colaborador">
                <h3>Gojo Satorou</h3>
                <span class="tree-count">Más donado</span>
                <p>Le gustan los árboles</p>
            </article>
        </div>
    </section>

    <?php
        include("../includes/footer.php");
    ?>
</body>
</html>