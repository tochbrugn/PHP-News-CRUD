<!DOCTYPE html>
<?php
include 'config/config.php';

// Handle delete article request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $articleId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$articleId]);
    header("Location: dashboard.php?deleted=1");
    exit;
}

// Handle success messages
$successMessage = '';
if (isset($_GET['success'])) {
    $successMessage = 'Article added successfully!';
} elseif (isset($_GET['deleted'])) {
    $successMessage = 'Article deleted successfully!';
} elseif (isset($_GET['updated'])) {
    $successMessage = 'Article updated successfully!';
} elseif (isset($_GET['error'])) {
    $errorMessage = 'Article not found!';
}
?>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>News CMS - Article Management</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="icon"
        href="https://cdn.dribbble.com/userupload/46115772/file/ba2d5f2051ee6cb786a98f8815156efe.jpg?format=webp&resize=400x300&vertical=center"
        type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2064EE",
                        "background-light": "#f8f6f6",
                        "background-dark": "#221610",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="w-64 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark/50 hidden md:flex flex-col sticky top-0 h-screen">
            <div class="p-6 flex items-center gap-3">
                <div class="bg-primary size-10 rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">newspaper</span>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none">News CMS</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Content Manager</p>
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-1">
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                    href="#">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 bg-primary/10 text-primary rounded-lg transition-colors"
                    href="#">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1">description</span>
                    <span class="font-medium text-sm">Articles</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                    href="#">
                    <span class="material-symbols-outlined">layers</span>
                    <span class="font-medium text-sm">Categories</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                    href="#">
                    <span class="material-symbols-outlined">image</span>
                    <span class="font-medium text-sm">Media Library</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                    href="#">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-medium text-sm">Users</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                    href="#">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-medium text-sm">Settings</span>
                </a>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header
                class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-10 px-6 flex items-center justify-between">
                <div class="flex items-center gap-4 flex-1">
                    <div class="relative w-full max-w-md group">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">search</span>
                        <input
                            class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                            placeholder="Search articles, authors or tags..." type="text" />
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-primary rounded-full"></span>
                    </button>
                    <div class="h-8 w-[1px] bg-slate-200 dark:border-slate-800 mx-2"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold leading-none">
                                <?php echo htmlspecialchars($currentUser['name'] ?? 'User'); ?></p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">
                                <?php echo htmlspecialchars($currentUser['role'] ?? 'User'); ?></p>
                        </div>
                        <img class="size-9 rounded-full bg-slate-200 object-cover border border-slate-200 dark:border-slate-700"
                            data-alt="User avatar for <?php echo htmlspecialchars($currentUser['name'] ?? 'User'); ?>"
                            src="<?php echo htmlspecialchars($currentUser['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($currentUser['name'] ?? 'User') . '&background=0D8ABC&color=fff'); ?>" />
                    </div>
                </div>
            </header>
            <div class="p-6 md:p-8 flex-1">
                <!-- Page Title Area -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">Articles</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Manage, edit and publish your news stories.
                        </p>
                    </div>
                    <a href="add_article.php"
                        class="bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-transform active:scale-95 shadow-lg shadow-primary/20 inline-block">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Add New Article
                    </a>
                </div>
                <?php if (isset($errorMessage)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>
                <?php if ($successMessage): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>
                <!-- Tabs -->
                <div class="mb-6 flex items-center gap-1 border-b border-slate-200 dark:border-slate-800">
                    <button class="px-4 py-3 text-sm font-bold border-b-2 border-primary text-primary">All
                        Articles</button>
                    <button
                        class="px-4 py-3 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Published</button>
                    <button
                        class="px-4 py-3 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Drafts</button>
                    <button
                        class="px-4 py-3 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Archived</button>
                </div>
                <!-- Content Table -->
                <div
                    class="bg-white dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4">Article Title</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">Author</th>
                                    <th class="px-6 py-4">Date Published</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php
                                $articles = $pdo->query("
                                    SELECT a.*, u.name as author_name, u.avatar as author_avatar, u.role as author_role 
                                    FROM articles a 
                                    LEFT JOIN users u ON a.author_id = u.id 
                                    ORDER BY a.created_at DESC
                                ")->fetchAll();
                                foreach ($articles as $article):
                                    ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-3">
                                                <img class="size-12 rounded-lg object-cover"
                                                    src="<?php echo htmlspecialchars($article['thumbnail'] ?: 'https://ui-avatars.com/api/?name=' . urlencode(substr($article['title'], 0, 2)) . '&background=0D8ABC&color=fff&size=150'); ?>"
                                                    alt="Article thumbnail">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-bold text-slate-900 dark:text-slate-100 text-sm line-clamp-1"><?php echo htmlspecialchars($article['title']); ?></span>
                                                    <span class="text-xs text-slate-400 mt-1">ID:
                                                        #<?php echo $article['id']; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span
                                                class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold"><?php echo htmlspecialchars(ucfirst($article['category'])); ?></span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-2">
                                                <img class="size-6 rounded-full" data-alt="Author avatar"
                                                    src="<?php echo htmlspecialchars($article['author_avatar'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($article['author_name'] ?? 'Admin') . '&background=0D8ABC&color=fff'); ?>" />
                                                <span
                                                    class="text-sm text-slate-600 dark:text-slate-400"><?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-slate-500 dark:text-slate-400">
                                            <?php echo date('M d, Y', strtotime($article['created_at'])); ?></td>
                                        <td class="px-6 py-5">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 text-xs font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Published
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="edit_article.php?id=<?php echo $article['id']; ?>"
                                                    class="p-2 hover:bg-primary/10 text-slate-400 hover:text-primary rounded-lg transition-colors"
                                                    title="Edit Article">
                                                    <span class="material-symbols-outlined text-xl">edit</span>
                                                </a>
                                                <button
                                                    class="p-2 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-lg transition-colors"
                                                    title="Delete Article"
                                                    onclick="if(confirm('Are you sure you want to delete this article?')) { window.location.href = 'dashboard.php?delete=<?php echo $article['id']; ?>'; }">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <!-- Static rows (commented out) -->
                                <!-- Row 1 -->

                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div
                        class="px-6 py-4 flex items-center justify-between border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Showing 1 to 4 of 24
                            articles</span>
                        <div class="flex items-center gap-1">
                            <button
                                class="p-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 disabled:opacity-50 transition-colors"
                                disabled="">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <button
                                class="size-7 flex items-center justify-center rounded-lg bg-primary text-white text-xs font-bold">1</button>
                            <button
                                class="size-7 flex items-center justify-center rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-medium">2</button>
                            <button
                                class="size-7 flex items-center justify-center rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-medium">3</button>
                            <span class="text-slate-400 px-1">...</span>
                            <button
                                class="size-7 flex items-center justify-center rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-medium">6</button>
                            <button class="p-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Navigation Bottom Bar (visible only on small screens) -->
            <div
                class="md:hidden sticky bottom-0 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark/95 flex justify-around items-center h-16 px-4">
                <button class="flex flex-col items-center gap-1 text-slate-400">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-[10px]">Home</span>
                </button>
                <button class="flex flex-col items-center gap-1 text-primary">
                    <span class="material-symbols-outlined">description</span>
                    <span class="text-[10px]">Articles</span>
                </button>
                <button
                    class="size-10 bg-primary text-white rounded-full flex items-center justify-center -translate-y-4 shadow-lg shadow-primary/30">
                    <span class="material-symbols-outlined">add</span>
                </button>
                <button class="flex flex-col items-center gap-1 text-slate-400">
                    <span class="material-symbols-outlined">image</span>
                    <span class="text-[10px]">Media</span>
                </button>
                <button class="flex flex-col items-center gap-1 text-slate-400">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-[10px]">Settings</span>
                </button>
            </div>
        </main>
    </div>
</body>

</html>