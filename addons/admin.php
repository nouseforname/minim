<?php
// Password administration interface: "admin" (sha512 encoded)
$password = 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec';

// rename the file to another name for more security
// using file name as url, but beeing able using another name than admin
$admin      = basename(__FILE__, '.php');
$text_files = array(
    'html',
    'md',
    'txt',
);
$upload_files = array(
    'jpg',
    'png',
);

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Access denied.');
}
session_start();

if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) === 'admin-logout') {
    unset($_SESSION['login']);
    session_destroy();
    header('Location:./');
}

if (isset($_POST['save_page'])) {
    file_put_contents('./pages/' . $_POST['file'], $_POST['content_page']);
    header('location:' . $_SERVER['REQUEST_URI']);
}

if (isset($_POST['delete_page']) && isset($_POST['delete_confirmation'])) {
    if (file_exists('./pages/' . $_POST['file'])) {
        unlink('./pages/' . $_POST['file']);
    }
    header('location:' . $_SERVER['REQUEST_URI']);
}

if (isset($_POST['save_config'])) {
    file_put_contents('./config/' . $_POST['file'], $_POST['content_config']);
    header('location:' . $_SERVER['REQUEST_URI']);
}

if (isset($_POST['cancel_config'])) {
    header('location:' . $_SERVER['REQUEST_URI']);
}

if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) === $admin) {
    if (isset($_POST['login'])) {
        $_POST['password'] = hash('sha512', $_POST['password']);
        if ($_POST['password'] == $password) {
            $_SESSION['login'] = true;
        } else {
            $_SESSION['login'] = false;
        }
    }

    $category = $admin;
    require_once './system/header.php';
    if ($language == 'de') {
        $translation = array_merge($translation, array(
            'Administration'          => 'Administration',
            'Cancel'                  => 'Abbrechen',
            'Configurations'          => 'Einstellungen',
            'Delete'                  => 'Löschen',
            'Edit'                    => 'Ändern',
            'File already exists'     => 'Datei existiert bereits',
            'File format not allowed' => 'Dateiformat nicht erlaubt',
            'Incorrect password'      => 'Falsches Passwort',
            'Login'                   => 'Einloggen',
            'Logout'                  => 'Ausloggen',
            'New'                     => 'Neuer Eintrag',
            'No file uploaded'        => 'Keine Datei hochgeladen',
            'Pages'                   => 'Seiten',
            'Password'                => 'Passwort',
            'Posts'                   => 'Einträge',
            'Save'                    => 'Speichern',
            'Tag'                     => 'Tag',
            'Upload'                  => 'Hochladen',
            'Uploads'                 => 'Dateien')
        );
    }
    if ($language == 'en') {
        $translation = array_merge($translation, array(
            'Administration'          => 'Administration',
            'Cancel'                  => 'Cancel',
            'Configurations'          => 'Configurations',
            'Delete'                  => 'Delete file',
            'Edit'                    => 'Edit',
            'File already exists'     => 'File already exists',
            'File format not allowed' => 'File format not allowed',
            'Incorrect password'      => 'Incorrect password',
            'Login'                   => 'Login',
            'Logout'                  => 'Logout',
            'New'                     => 'New',
            'No file uploaded'        => 'No file uploaded',
            'Pages'                   => 'Pages',
            'Password'                => 'Password',
            'Posts'                   => 'Posts',
            'Save'                    => 'Save',
            'Tag'                     => 'Tag',
            'Upload'                  => 'Upload',
            'Uploads'                 => 'Uploads')
        );
    }

    if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
        echo '<header><h2>' . translation('Administration') . '</h2><p><a href="./?admin-logout">' . translation('Logout') . '</a></p></header><main><article><h2 id="posts">' . translation('Posts') . '</h2><form action="./?' . $admin . '" method="post" enctype="multipart/form-data"><input type="file" name="file[]" multiple><input type="submit" name="upload_post" value="' . translation('Upload') . '"></form>';

        if (isset($_POST['upload_post'])) {
            for ($i = 0; $i < count($_FILES['file']['size']); $i++) {
                $extension = pathinfo(strtolower($_FILES['file']['name'][$i]), PATHINFO_EXTENSION);
                if (in_array($extension, $text_files)) {
                    move_uploaded_file($_FILES['file']['tmp_name'][$i], './posts/' . $_FILES['file']['name'][$i]);
                    chmod('./posts/' . $_FILES['file']['name'][$i], 0644);
                } else {
                    if ($_FILES['file']['name'][$i] == '') {
                        echo '<p>' . translation('No file uploaded') . '.</p>';
                    } else {
                        echo '<p>' . translation('File format not allowed') . ': <code>' . $_FILES['file']['name'][$i] . '</code></p>';
                    }
                }
            }
        }

        echo '<br><form action="./?' . $admin . '#posts" method="post"><input type="text" name="file" value="' . date('Y-m-d') . '.md" placeholder="' . date('Y-m-d') . '.md"> <input type="submit" name="new_post" value="' . translation('New') . '">';
        $textarea_size = ' rows="20" cols="40"';
        if (isset($_POST['new_post'])) {
            $extension = explode('.', $_POST['file']);
            if (in_array(end($extension), $text_files)) {
                if (!file_exists('./posts/' . $_POST['file'])) {
                    echo '<p><strong>' . $_POST['file'] . '</strong></p><input type="hidden" name="file" value="' . $_POST['file'] . '"><textarea name="content_post"' . $textarea_size . '></textarea><br><input type="submit" name="save_post" value="' . translation('Save') . '"><input type="submit" value="' . translation('Cancel') . '">';
                } else {
                    echo '<p>' . translation('File already exists') . '.</p>';
                }
            } else {
                echo '<p>' . translation('File format not allowed') . '.</p>';
            }
        }
        echo '</form>';

        if (isset($_POST['edit_post'])) {
            if (file_exists('./posts/' . $_POST['file'])) {
                echo '<form action="./?' . $admin . '#posts" method="post"><p><strong>' . $_POST['file'] . '</strong></p><input type="hidden" name="file" value="' . $_POST['file'] . '"><textarea name="content_post"' . $textarea_size . '>' . file_get_contents('./posts/' . $_POST['file']) . '</textarea><br><input type="submit" name="save_post" value="' . translation('Save') . '"><input type="submit" value="' . translation('Cancel') . '"></form>';
            }
        }

        include './system/posts.php';
        if (!empty($posts)) {
            for ($i = count($posts) - 1; $i > 0; $i--) {
                echo '<form action="./?' . $admin . '#posts" method="post"><input type="checkbox" name="delete_confirmation" value="confirmed">[' . date($date, filemtime('./posts/' . $posts[$i])) . '] <a href="./posts/' . $posts[$i] . '" download="' . $posts[$i] . '">' . $posts[$i] . '</a> <input type="hidden" name="file" value="' . $posts[$i] . '"><input type="submit" name="edit_post" value="' . translation('Edit') . '"><input type="submit" name="delete_post" value="' . translation('Delete') . '"></form>';
            }
        }

        echo '</article><article><h2 id="pages">' . translation('Pages') . '</h2><form action="./?' . $admin . '#pages" method="post" enctype="multipart/form-data"><input type="file" name="file[]" multiple><input type="submit" name="upload_page" value="' . translation('Upload') . '"></form>';
        if (isset($_POST['upload_page'])) {
            for ($i = 0; $i < count($_FILES['file']['size']); $i++) {
                $extension = pathinfo(strtolower($_FILES['file']['name'][$i]), PATHINFO_EXTENSION);
                if (in_array($extension, $text_files)) {
                    move_uploaded_file($_FILES['file']['tmp_name'][$i], './pages/' . $_FILES['file']['name'][$i]);
                    chmod('./pages/' . $_FILES['file']['name'][$i], 0644);
                } else {
                    if ($_FILES['file']['name'][$i] == '') {
                        echo '<p>' . translation('No file uploaded') . '.</p>';
                    } else {
                        echo '<p>' . translation('File format not allowed') . ': <code>' . $_FILES['file']['name'][$i] . '</code></p>';
                    }
                }
            }
        }

        echo '<br><form action="./?' . $admin . '#pages" method="post"><input type="text" name="file" value="page.md" placeholder="page.md"> <input type="submit" name="new_page" value="' . translation('New') . '">';
        $textarea_size = ' rows="20" cols="40"';
        if (isset($_POST['new_page'])) {
            $extension = explode('.', $_POST['file']);
            if (in_array(end($extension), $text_files)) {
                if (!file_exists('./pages/' . $_POST['file'])) {
                    echo '<p><strong>' . $_POST['file'] . '</strong></p><input type="hidden" name="file" value="' . $_POST['file'] . '"><textarea name="content_page"' . $textarea_size . '></textarea><br><input type="submit" name="save_page" value="' . translation('Save file') . '"><input type="submit" value="' . translation('Cancel') . '">';
                } else {
                    echo '<p>' . translation('File already exists') . '.</p>';
                }
            } else {
                echo '<p>' . translation('File format not allowed') . '.</p>';
            }
        }
        echo '</form>';

        if (isset($_POST['edit_page'])) {
            if (file_exists('./pages/' . $_POST['file'])) {
                echo '<form action="./?' . $admin . '#pages" method="post"><p><strong>' . $_POST['file'] . '</strong></p><input type="hidden" name="file" value="' . $_POST['file'] . '"><textarea name="content_page"' . $textarea_size . '>' . file_get_contents('./pages/' . $_POST['file']) . '</textarea><br><input type="submit" name="save_page" value="' . translation('Save') . '"><input type="submit" value="' . translation('Cancel') . '"></form>';
            }
        }

        include './system/pages.php';
        if (!empty($pages)) {
            for ($i = count($pages) - 1; $i > 0; $i--) {
                echo '<form action="./?' . $admin . '#pages" method="post"><input type="checkbox" name="delete_confirmation" value="confirmed">[' . date($date, filemtime('./pages/' . $pages[$i])) . '] <a href="./pages/' . $pages[$i] . '" download="' . $pages[$i] . '">' . $pages[$i] . '</a> <input type="hidden" name="file" value="' . $pages[$i] . '"><input type="submit" name="edit_page" value="' . translation('Edit') . '"><input type="submit" name="delete_page" value="' . translation('Delete') . '"></form>';
            }
        }

        echo '</article><article><h2 id="uploads">' . translation('Uploads') . '</h2><form action="./?' . $admin . '#uploads" method="post" enctype="multipart/form-data"><input type="file" name="file[]" multiple><input type="submit" name="upload_file" value="' . translation('Upload') . '"></form>';
        if (isset($_POST['upload_file'])) {
            for ($i = 0; $i < count($_FILES['file']['size']); $i++) {
                $extension = pathinfo(strtolower($_FILES['file']['name'][$i]), PATHINFO_EXTENSION);
                if (in_array($extension, $upload_files)) {
                    move_uploaded_file($_FILES['file']['tmp_name'][$i], './uploads/' . $_FILES['file']['name'][$i]);
                    chmod('./uploads/' . $_FILES['file']['name'][$i], 0644);
                } else {
                    if ($_FILES['file']['name'][$i] == '') {
                        echo '<p>' . translation('No file uploaded') . '.</p>';
                    } else {
                        echo '<p>' . translation('File format not allowed') . ': <code>' . $_FILES['file']['name'][$i] . '</code></p>';
                    }
                }
            }
        }

        if (isset($_POST['delete_file']) && isset($_POST['delete_confirmation'])) {
            $file_explode = explode('/', $_POST['file']);
            if (file_exists('./uploads/' . $_POST['file'])) {
                unlink('./uploads/' . $_POST['file']);
            }
        }

        if ($path = opendir('./uploads/')) {
            while (false !== ($file = readdir($path))) {
                if ($file != '.' && $file != '..') {
                    $files[] = $file;
                }
            }
            closedir($path);
        }

        if (!empty($files)) {
            sort($files);
            for ($i = 0; $i < count($files); $i++) {
                echo '<form action="./?' . $admin . '#uploads" method="post">
        <input type="checkbox" name="delete_confirmation" value="confirmed">[' . date($date, filemtime('./uploads/' . $files[$i])) . '] <a href="./uploads/' . $files[$i] . '" download="' . $files[$i] . '">' . $files[$i] . '</a>
        <input type="hidden" name="file" value="' . $files[$i] . '">
        <input type="submit" name="delete_file" value="' . translation('Delete') . '">
        </form>';
            }
        }
        echo '</article><article><h2 id="configurations">' . translation('Configurations') . '</h2>';

        if (isset($_POST['edit_config'])) {
            if (file_exists('./config/' . $_POST['file'])) {
                echo '<form action="./?' . $admin . '#configurations" method="post"><p><strong>' . $_POST['file'] . '</strong></p>
        <input type="hidden" name="file" value="' . $_POST['file'] . '">
        <textarea name="content_config"' . $textarea_size . '>' . file_get_contents('./config/' . $_POST['file']) . '</textarea>
        <br>
        <input type="submit" name="save_config" value="' . translation('Save') . '">
        <input type="submit" name="cancel_config" value="' . translation('Cancel') . '"></form>';
            }
        }

        $config_files = array('header.md', 'footer.md', 'head.php', 'foot.php');
        foreach ($config_files as $file) {
            if (is_file('./config/' . $file) === false) {
                continue;
            }

            echo '<form action="./?' . $admin . '#configurations" method="post">[' . date($date, filemtime('./config/' . $file)) . ']' . $file . '
        <input type="hidden" name="file" value="' . $file . '"><input type="submit" name="edit_config" value="' . translation('Edit') . '">
        </form>';
        }
        echo '</article></main>';
    } else {
        echo '<header><form action="./?' . $admin . '" method="post"><p><input name="password" type="password" placeholder="' . translation('Password') . '" required autofocus><br><input type="submit" name="login" value="' . translation('Login') . '"></p></form>';
        if (isset($_POST['login'])) {
            sleep(5);
            echo '<p>' . translation('Incorrect password') . '.</p>';
        }
        echo '</header>';
    }
    require_once './system/footer.php';
}
