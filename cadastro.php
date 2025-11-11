<?php 
include "header.php";
?>

<main id="signup">

    <form method="post">
        <h2>Cadastre-se</h2>
        <?php 
            if(isset($_POST['username'])) {
                include "config/funcs.php";
                $username = trim($_POST['username']);
                $email = strtolower(trim($_POST['email']));
                $password = trim($_POST['password']);

                $verification = verifyInfos($email, $password, $username);
                if ($verification === true) {
                    $cadastrado = cadastrarUsuario($email, $password, $username);
                    if ($cadastrado) {
                        echo "<p class='success'>Cadastro realizado com sucesso</p>";
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        $_SESSION['email'] = $email;
                        echo "<script>alert('Cadastro realizado com sucesso');
                        window.location.href = 'login.php';</script>";
                    } else {
                        echo "<p class='error' >Email ja está em uso</p>";
                    }
                } else {
                    echo $verification;
                }
            }
        ?>
        <input type="text" name="username" 
        placeholder="Nome de usuário" required
        <?php 
        if(isset($username)) echo "value='$username'";
        ?>>

        <input type="email" name="email" 
        placeholder="Email" required
        <?php 
        if(isset($email)) echo "value='$email'";
        ?>>

        <input type="password" name="password" 
        placeholder="Senha" required
        <?php 
        if(isset($password)) echo "value='$password'";
        ?>>

        <button type="submit"><span>Cadastrar</span></button>
    </form>
    <p>Já tem conta? <a href="login.php">Login</a></p>

</main>
    
</body>
</html>
