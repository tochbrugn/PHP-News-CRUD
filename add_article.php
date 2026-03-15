<!DOCTYPE html>
<?php include 'config/config.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $content = trim($_POST['content']);
    $thumbnail = trim($_POST['thumbnail']);

    // Handle file upload
    if (!empty($_FILES['thumbnailFile']['name'])) {
        $target_dir = "assets/images/";
        $file_name = basename($_FILES["thumbnailFile"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name; // Add timestamp to avoid conflicts
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image
        $check = getimagesize($_FILES["thumbnailFile"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["thumbnailFile"]["tmp_name"], $target_file)) {
                $thumbnail = $target_file;
            }
        }
    }

    // Insert into database
    if (!empty($title) && !empty($category) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, category, content, thumbnail, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$title, $category, $content, $thumbnail]);
        
        // Redirect to dashboard
        header("Location: dashboard.php?success=1");
        exit;
    }
}
?>

<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>News CMS - Add New Article</title>
    
    <link rel="icon" href="https://cdn.dribbble.com/userupload/46115772/file/ba2d5f2051ee6cb786a98f8815156efe.jpg?format=webp&resize=400x300&vertical=center" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
        <aside class="w-64 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark/50 hidden md:flex flex-col sticky top-0 h-screen">
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
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" href="dashboard.php">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 bg-primary/10 text-primary rounded-lg transition-colors" href="dashboard.php">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1">description</span>
                    <span class="font-medium text-sm">Articles</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" href="#">
                    <span class="material-symbols-outlined">layers</span>
                    <span class="font-medium text-sm">Categories</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" href="#">
                    <span class="material-symbols-outlined">image</span>
                    <span class="font-medium text-sm">Media Library</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" href="#">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-medium text-sm">Users</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" href="#">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-medium text-sm">Settings</span>
                </a>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-10 px-6 flex items-center justify-between">
                <div class="flex items-center gap-4 flex-1">
                    <div class="relative w-full max-w-md group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">search</span>
                        <input class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Search articles, authors or tags..." type="text" />
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
                            <p class="text-xs font-bold leading-none">TOCH BRUGN</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Editor in Chief</p>
                        </div>
                        <img class="size-9 rounded-full bg-slate-200 object-cover border border-slate-200 dark:border-slate-700" data-alt="User avatar for Alex Rivera" src="./assets/images/TOCHBRUGN.jpg" />
                    </div>
                </div>
            </header>
            <div class="p-6 md:p-8 flex-1">
                <!-- Page Title Area -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">Add New Article</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Create a new news article.</p>
                    </div>
                    <a href="dashboard.php" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-transform active:scale-95">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                        Back to Articles
                    </a>
                </div>
                <!-- Form -->
                <div class="bg-white dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                    <form action="#" method="post" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Article Title</label>
                            <input type="text" id="title" name="title" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Enter article title" required>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Category</label>
                            <select id="category" name="category" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none" required>
                                <option value="">Select a category</option>
                                <option value="finance">Finance</option>
                                <option value="technology">Technology</option>
                                <option value="politics">Politics</option>
                                <option value="lifestyle">Lifestyle</option>
                            </select>
                        </div>
                        <div>
                            <label for="content" class="block text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Content</label>
                            <textarea id="content" name="content" rows="10" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Write your article content here..." required></textarea>
                        </div>
                        <div>
                            <label for="thumbnail" class="block text-sm font-bold text-slate-900 dark:text-slate-100 mb-2">Thumbnail Image</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Image URL</label>
                                    <input type="url" id="thumbnail" name="thumbnail" placeholder="https://example.com/image.jpg" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Upload file</label>
                                    <input type="file" id="thumbnailFile" name="thumbnailFile" accept="image/*" class="w-full text-sm text-slate-700 dark:text-slate-200" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Preview</div>
                                <div class="w-40 h-40 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                    <img id="thumbnailPreview" src="https://picsum.photos/150/150" alt="Thumbnail preview" class="w-full h-full object-cover" />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-xl font-bold text-sm flex items-center gap-2 transition-transform active:scale-95 shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined text-lg">save</span>
                                Save Article
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Mobile Navigation Bottom Bar -->
            <div class="md:hidden sticky bottom-0 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark/95 flex justify-around items-center h-16 px-4">
                <button class="flex flex-col items-center gap-1 text-slate-400">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-[10px]">Home</span>
                </button>
                <button class="flex flex-col items-center gap-1 text-primary">
                    <span class="material-symbols-outlined">description</span>
                    <span class="text-[10px]">Articles</span>
                </button>
                <button class="size-10 bg-primary text-white rounded-full flex items-center justify-center -translate-y-4 shadow-lg shadow-primary/30">
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
    <script>
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailFile = document.getElementById('thumbnailFile');
        const thumbnailPreview = document.getElementById('thumbnailPreview');

        thumbnailInput.addEventListener('input', () => {
            if (thumbnailInput.value) {
                thumbnailPreview.src = thumbnailInput.value;
            }
        });

        thumbnailFile.addEventListener('change', () => {
            const file = thumbnailFile.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                thumbnailPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>