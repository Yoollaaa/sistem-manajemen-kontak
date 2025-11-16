<?php
session_start();

if (!isset($_SESSION['contacts']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_to_edit = $_GET['id'];
$contact_to_edit = null;
$contact_index = null; 

foreach ($_SESSION['contacts'] as $index => $contact) {
    if ($contact['id'] == $id_to_edit) {
        $contact_to_edit = $contact;
        $contact_index = $index;
        break;
    }
}

if ($contact_to_edit === null) {
    header("Location: index.php");
    exit();
}

$errors = [];
$nama = $contact_to_edit['nama'];
$email = $contact_to_edit['email'];
$telepon = $contact_to_edit['telepon'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);

    if (empty($nama)) {
        $errors[] = "Nama harus diisi";
    }
    if (empty($email)) {
        $errors[] = "Email harus diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }
    if (empty($telepon)) {
        $errors[] = "Nomor telepon harus diisi";
    } elseif (!preg_match("/^[0-9\-\+\s\(\)]*$/", $telepon)) {
        $errors[] = "Format nomor telepon tidak valid";
    }

    
    if (empty($errors)) {
       
        $_SESSION['contacts'][$contact_index]['nama'] = $nama;
        $_SESSION['contacts'][$contact_index]['email'] = $email;
        $_SESSION['contacts'][$contact_index]['telepon'] = $telepon;

       
        header("Location: index.php");
        exit();
    }
    
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kontak</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Kontak</h1>
        <p><a href="index.php">&larr; Kembali ke Daftar Kontak</a></p>

        <div class="form-container">
            <h2>Edit: <?php echo htmlspecialchars($contact_to_edit['nama']); ?></h2>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <strong>Error:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?php echo $contact_to_edit['id']; ?>">
                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="form-group">
                    <label for="telepon">No. Telepon:</label>
                    <input type="text" id="telepon" name="telepon" value="<?php echo htmlspecialchars($telepon); ?>">
                </div>
                <div class="form-group">
                    <button type="submit">Update Kontak</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>