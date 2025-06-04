<?php
session_start();
require_once '../configdb.php';

// Redirect jika belum login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, fullname, email, photo FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p>User tidak ditemukan.</p>";
    exit;
}

// Handle update username
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_username'])) {
    $new_username = trim($_POST['username']);
    if ($new_username && $new_username !== $user['username']) {
        $stmt = $conn->prepare("UPDATE user SET username = ? WHERE id = ?");
        $stmt->execute([$new_username, $user_id]);
        $user['username'] = $new_username;
        $success = "Username berhasil diupdate!";
    }
}

// Handle update password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_password'])) {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    // Ambil hash password lama
    $stmt = $conn->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->execute([$user_id]);
    $hash = $stmt->fetchColumn();
    if (!password_verify($old, $hash)) {
        $error = "Password lama salah!";
    } elseif (strlen($new) < 6) {
        $error = "Password baru minimal 6 karakter.";
    } elseif ($new !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
        $stmt->execute([password_hash($new, PASSWORD_DEFAULT), $user_id]);
        $success = "Password berhasil diubah!";
    }
}

// Handle add address
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $address = trim($_POST['address']);
    $type = ($_POST['type'] === 'billing') ? 'billing' : 'shipping';
    if ($address) {
        $stmt = $conn->prepare("INSERT INTO address (user_id, address, type) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $address, $type]);
        $success = "Address berhasil ditambahkan!";
    } else {
        $error = "Alamat tidak boleh kosong.";
    }
}

// Ambil address user
$stmt = $conn->prepare("SELECT address, type FROM address WHERE user_id = ?");
$stmt->execute([$user_id]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Foto profil default jika belum ada
$profile_img = $user['photo']
    ? '../Tazkya-HTML/images/' . htmlspecialchars($user['photo'])
    : 'https://upload.wikimedia.org/wikipedia/commons/2/2c/Default_pfp.svg';

include '../views/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Account - NailStudio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-pink': '#fce7f3',
                        'custom-blue': '#d6e0ff',
                    },
                },
            },
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
    <style>
        body { 
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #fce7f3 0%, #d6e0ff 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="min-h-screen relative bg-gradient-to-br from-custom-pink to-custom-blue">    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-900">Your Account</h1>
            <form action="logout.php" method="post" class="inline-block">
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#fff1f3] px-5 py-2 text-sm text-[#4a4a4a] font-semibold hover:bg-pink-100 transition">
                    <i class="fas fa-sign-out-alt text-base"></i>
                    Log out
                </button>
            </form>
        </div>

        <!-- Profile Quick Info -->
        <div class="bg-white rounded-xl p-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="<?= $profile_img ?>" alt="Profile" 
                         class="w-16 h-16 rounded-full object-cover border-2 border-pink-200">
                    <!-- Edit Icon di luar foto profil -->
                    <form method="post" enctype="multipart/form-data" 
                          class="absolute" 
                          style="right:-12px; bottom:-12px;">
                        <label class="cursor-pointer bg-white hover:bg-gray-100 text-pink-500 rounded-full p-2 shadow flex items-center justify-center border-2 border-white transition-opacity opacity-90" title="Edit Photo" style="font-size:16px;">
                            <i class="fas fa-pencil-alt"></i>
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
                <div>
                    <h2 class="font-semibold text-lg"><?= htmlspecialchars($user['fullname']) ?></h2>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Order History -->
                <div class="bg-white rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Order history</h2>
                    <p class="text-xs text-gray-700">You haven't placed any orders yet.</p>
                </div>

                <!-- Account Settings -->
                <div class="bg-white rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Settings</h2>
                    
                    <!-- Username Display & Change -->
                    <?php if (isset($_GET['edit_username'])): ?>
                        <form method="post" class="space-y-4 mb-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">New Username</label>
                                <div class="flex gap-2">
                                    <input type="text" name="username"
                                        value="<?= htmlspecialchars($user['username']) ?>"
                                        class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
                                        required>
                                    <button type="submit" name="edit_username"
                                        class="bg-pink-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-pink-600 transition">
                                        Save
                                    </button>
                                    <a href="profile.php" class="text-gray-500 hover:underline px-2 py-2 text-sm">Cancel</a>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
    <div class="mb-6">
        <label class="block text-xs font-medium text-gray-700 mb-1">Username</label>
        <div class="flex items-center justify-between">
            <span class="text-base text-gray-800 font-medium"><?= htmlspecialchars($user['username']) ?></span>
            <a href="profile.php?edit_username=1" class="text-blue-600 underline text-sm hover:text-blue-800 transition ml-4">
                Change username
            </a>
        </div>
    </div>
<?php endif; ?>

                    <!-- Email Display & Change -->
                    <?php if (isset($_GET['edit_email'])): ?>
                        <form method="post" class="space-y-4 mb-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">New Email</label>
                                <div class="flex gap-2">
                                    <input type="email" name="email"
                                        value="<?= htmlspecialchars($user['email']) ?>"
                                        class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
                                        required>
                                    <button type="submit" name="edit_email"
                                        class="bg-pink-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-pink-600 transition">
                                        Save
                                    </button>
                                    <a href="profile.php" class="text-gray-500 hover:underline px-2 py-2 text-sm">Cancel</a>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="mb-6">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                            <div class="flex items-center justify-between">
                                <span class="text-base text-gray-800 font-medium"><?= htmlspecialchars($user['email']) ?></span>
                                <a href="profile.php?edit_email=1" class="text-blue-600 underline text-sm hover:text-blue-800 transition ml-4">
                                    Change email
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off" class="space-y-4">
                        <h3 class="text-sm font-medium text-gray-700">Change Password</h3>
                        <div class="relative">
                            <input type="password" id="old_password" name="old_password" placeholder="Current password"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400" onclick="togglePassword('old_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="password" id="new_password" name="new_password" placeholder="New password"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400" onclick="togglePassword('new_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400" onclick="togglePassword('confirm_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <button type="submit" name="edit_password"
                                class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                            Update Password
                        </button>
                    </form>
                </div>

                <!-- Address Book -->
                <div class="bg-white rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
                    
                    <form method="post" class="flex gap-2 mb-4">
                        <input type="text" name="address" placeholder="Add new shipping address" 
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
                               required>
                        <input type="hidden" name="type" value="shipping">
                        <button type="submit" name="add_address" 
                                class="bg-pink-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-pink-600 transition">
                            Add
                        </button>
                    </form>

                    <?php if (empty($addresses)): ?>
                        <p class="text-xs text-gray-700">No addresses found. Add a new address.</p>
                    <?php else: ?>
                        <ul class="space-y-2">
                            <?php foreach ($addresses as $addr): ?>
                                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg shadow-sm">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($addr['address']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars(ucfirst($addr['type'])) ?> Address</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="text-pink-600 hover:underline text-xs">
                                            Edit
                                        </button>
                                        <button class="text-red-600 hover:underline text-xs">
                                            Delete
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="bg-white rounded-xl p-6 text-xs text-gray-900 space-y-4 h-fit">
                <div>
                    <p class="font-semibold text-sm">Shipping Address</p>
                    <p>
                        <?php
                        // Tampilkan alamat shipping pertama user, jika ada
                        $shipping = array_filter($addresses, fn($a) => $a['type'] === 'shipping');
                        if (!empty($shipping)) {
                            echo htmlspecialchars(reset($shipping)['address']);
                        } else {
                            echo '<span class="text-gray-400">No shipping address</span>';
                        }
                        ?>
                    </p>
                </div>
                <a href="#" class="text-[10px] text-pink-600 hover:underline">
                    View addresses (<?= count(array_filter($addresses, fn($a) => $a['type'] === 'shipping')) ?>)
                </a>
            </div>
        </div>

        <!-- Bottom Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 max-w-4xl">
            <a href="../pages/favorite.php" 
               class="flex-1 flex justify-between items-center bg-white rounded-xl px-6 py-4 text-gray-900 font-semibold text-lg hover:bg-gray-50 transition">
                My Wishlist
                <i class="far fa-heart text-base"></i>
            </a>
            <button type="button" 
                    class="flex-1 flex justify-between items-center bg-white rounded-xl px-6 py-4 text-gray-900 font-semibold text-lg hover:bg-gray-50 transition">
                Resolution Centre
                <i class="fas fa-info-circle text-base"></i>
            </button>
        </div>

        <?php if (!empty($success)): ?>
            <div class="fixed bottom-4 right-4 bg-green-50 text-green-700 px-4 py-2 rounded-lg shadow-lg">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="fixed bottom-4 right-4 bg-red-50 text-red-700 px-4 py-2 rounded-lg shadow-lg">
                <?= $error ?>
            </div>
        <?php endif; ?>
    </div>
</body>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</html>
<?php include '../views/footers.php'; ?>