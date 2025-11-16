<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

if (!isset($_SESSION['contacts_data'][$current_user_id])) {
    $_SESSION['contacts_data'][$current_user_id] = [];
}

$errors = []; 
$nama = "";
$email = "";
$telepon = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        
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
            $new_contact = [
                'id' => uniqid(), 
                'nama' => $nama,
                'email' => $email,
                'telepon' => $telepon
            ];
            
            $_SESSION['contacts_data'][$current_user_id][] = $new_contact;

            header("Location: index.php");
            exit();
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id_to_delete = $_POST['id_kontak'];

        foreach ($_SESSION['contacts_data'][$current_user_id] as $index => $contact) {
            if ($contact['id'] == $id_to_delete) {
                unset($_SESSION['contacts_data'][$current_user_id][$index]);
                break;
            }
        }

        $_SESSION['contacts_data'][$current_user_id] = array_values($_SESSION['contacts_data'][$current_user_id]);

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Manajemen Kontak</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        
        <div class="header-nav">
            <span>Selamat datang, <strong><?php echo htmlspecialchars($current_user_id); ?></strong>!</span>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>

        <h1>Sistem Manajemen Kontak</h1>

        <div class="form-container">
            <h2>Tambah Kontak Baru</h2>

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

            <form method="POST" action="index.php">
                <input type="hidden" name="action" value="add">
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
                    <button type="submit">Tambah Kontak</button>
                </div>
            </form>
        </div>

        <hr>

        <div class="list-container">
            <h2>Daftar Kontak (Milik <?php echo htmlspecialchars($current_user_id); ?>)</h2>
            
            <?php if (empty($_SESSION['contacts_data'][$current_user_id])): ?>
                <p>Belum ada kontak yang disimpan.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['contacts_data'][$current_user_id] as $contact): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contact['nama']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['telepon']); ?></td>
                                <td class="action-links">
                                    <a href="edit.php?id=<?php echo $contact['id']; ?>">Edit</a>
                                    <form method="POST" action="index.php" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_kontak" value="<?php echo $contact['id']; ?>">
                                        <button type="submit" class="delete-button">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>