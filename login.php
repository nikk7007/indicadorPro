<?php 
include "header.php";

if(!isset($_SESSION)) { 
    session_start();
}



?>

<main id="signup">

    <form method="post">
        <h2>Login</h2>
        <?php 
        if (isset($_POST['email'])) {
            $email = strtolower(trim($_POST['email']));
            $password = trim($_POST['password']);

            include "config/connect.php";

            $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    header("Location: index.php");
                } else {
                    echo "<p class='error'>Senha incorreta</p>";
                }
            } else {
                echo "<p class='error'>Email não cadastrado</p>";
            }
        }
        ?>
        <input type="email" name="email" 
        placeholder="Email" required
        <?php 
            if(!isset($_SESSION)) {
                session_start();
            }
            if (isset($_SESSION['email'])) {
                echo "value='".$_SESSION['email']."'";
            }
        ?>>

        <input type="password" name="password" 
        placeholder="Senha" required>

        <button type="submit"><span>Entrar</span></button>
    </form>
    <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>


</main>

</body>
</html>
