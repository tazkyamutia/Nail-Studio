<?php include 'configdb.php'; ?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Reviews</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-pink-200 shadow">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-pink-800">Cosmetic Capital</h1>
            <nav class="space-x-4">
                <a href="#" class="text-pink-700 hover:underline">Home</a>
                <a href="#" class="text-pink-700 hover:underline">Products</a>
                <a href="reviews.php" class="text-pink-700 font-semibold underline">Reviews</a>
                <a href="#" class="text-pink-700 hover:underline">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl mx-auto px-4 py-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Customer Reviews</h2>
            <a href="add-review.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                + Add Review
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Rating</th>
                        <th class="px-4 py-3 text-left">Review</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr class="border-t">
                        <td class="px-4 py-3 font-medium"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="px-4 py-3 text-yellow-500"><?= str_repeat('★', $row['rating']) ?></td>
                        <td class="px-4 py-3"><?= nl2br(htmlspecialchars($row['review'])) ?></td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="edit-review.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                            <a href="delete-review.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="4" class="text-center px-4 py-6 text-gray-500">No reviews found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include 'footers.php'; ?>

</body>
</html>
