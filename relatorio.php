<?php 
include 'header.php';
if (!isset($_SESSION))
    session_start();

if (!isset($_SESSION['id'])) {
    // mensagem de nao logado
    header("Location: index.php");
}

?>


<main id="report">
    
    <form class="card" id="report-generator" method="post" action="baixar_relatorio.php">
        <section class="head">
            <h2>Gerar relatório</h2>
        </section>
        <section class="body">
            <div>
                <label for="">Data inicial</label>
                <input type="date" name="start">
            </div>
            <div>
                <label for="">Data final</label>
                <input type="date" name="end">
            </div>
            <!-- <a href="">Relatorio gerado com sucesso</a> -->
            <button type="submit"><span>Gerar</span></button>
        </section>
    </form>
    
</main>