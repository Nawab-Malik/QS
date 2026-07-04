<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/helpers.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $errors[] = "Email and password are required.";
    } else {
        $db = db_connect();
        $stmt = $db->prepare("SELECT id, password_hash FROM admin_users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user["password_hash"])) {
            $_SESSION["admin_id"] = $user["id"];
            $_SESSION["admin_email"] = $email;
            header("Location: index.php");
            exit;
        }
        $errors[] = "Invalid login credentials.";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login | QS Marketing</title>
    <link rel="shortcut icon" href="https://dummyimage.com/64x64/0ea5e9/ffffff.png?text=QS" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/color.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <style>
        .login-card {
            background: rgba(10, 17, 40, 0.85);
            border: 1px solid rgba(14, 165, 233, 0.2);
            border-radius: 16px;
            padding: 32px;
        }

        .admin-input {
            background: rgba(4, 10, 25, 0.8);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: #dbe6ff;
        }

        .admin-input:focus {
            background: rgba(4, 10, 25, 0.9);
            border-color: #0ea5e9;
            box-shadow: none;
            color: #dbe6ff;
        }
    </style>
</head>

<body class="body-bg">
    <section class="section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="login-card">
                        <h3 class="mb-4">Admin Login</h3>
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <div><?php echo admin_escape($error); ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control admin-input" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control admin-input" required />
                            </div>
                            <button class="theme-btn w-100" type="submit">Login</button>
                        </form>
                        <div class="mt-3 text-muted" style="font-size: 0.9rem;">
                            Run <strong>admin/setup.php</strong> once to create the database and seed data.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>