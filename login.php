<?php if(!session_id()) session_start();

    if(isset($_SESSION['auth'])){
        header('Location: index.php');
        exit();
    }

    if(isset($_POST) && isset($_POST['signin'])){
        $_SESSION['err'] = array();
        if(!isset($_POST['email'])){
            $_SESSION['err'][] = 'Email is required!';
        }

        if(!isset($_POST['password'])){
            $_SESSION['err'][] = 'Password is required!';
        }

        if(!count($_SESSION['err'])){
            // password_hash('Indi@_2011Admin');
            $email = 'rnk022@gmail.com';
            $hash = '$2y$10$JPXTnYQRwWZbLEjZF7BIhuzah5VFWwk7yi/J6L.fv4O20B2uT26zy';

            if ($_POST['email'] == $email && password_verify($_POST['password'], $hash)) {
                $_SESSION['auth'] = $email;
                header('Location: index.php');
                exit();
            } else {
               $_SESSION['err'][] = 'Invalid Email/Password!';
            }
        }
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="File manager, Online Editor, Image crop, Resize, filter and document viewer">
        <meta name="author" content="RN Kushwaha">
        <title>fileMagician: Adding Awesomeness to the web</title>
        <link rel="stylesheet" href="vendors/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="vendors/cruzersoftwares/css/style.css" />
        <style type="text/css">:root{--input-padding-x:.75rem;--input-padding-y:.75rem}html,body{height:100%}body{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;padding-top:40px;padding-bottom:40px;background-color:#f5f5f5}.form-signin{width:100%;max-width:420px;padding:15px;margin:auto}.form-label-group{position:relative;margin-bottom:1rem}.form-label-group > input,.form-label-group > label{padding:var(--input-padding-y) var(--input-padding-x)}.form-label-group > label{position:absolute;top:0;left:0;display:block;width:100%;margin-bottom:0;line-height:1.5;color:#495057;border:1px solid transparent;border-radius:.25rem;transition:all .1s ease-in-out}.form-label-group input::-webkit-input-placeholder{color:transparent}.form-label-group input:-ms-input-placeholder{color:transparent}.form-label-group input::-ms-input-placeholder{color:transparent}.form-label-group input::-moz-placeholder{color:transparent}.form-label-group input::placeholder{color:transparent}.form-label-group input:not(:placeholder-shown){padding-top:calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));padding-bottom:calc(var(--input-padding-y) / 3)}.form-label-group input:not(:placeholder-shown) ~ label{padding-top:calc(var(--input-padding-y) / 3);padding-bottom:calc(var(--input-padding-y) / 3);font-size:12px;color:#777}.form-label-group input::-ms-input-placeholder{color:#777}@media all and (-ms-high-contrast: none),(-ms-high-contrast: active){.form-label-group > label{display:none}.form-label-group input:-ms-input-placeholder{color:#777}}</style>
    </head>
    
    <body>
    <form class="form-signin" method="post" accept="utf-8">
      <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">fileMagician</h1>
        <p>Sign In to feel the awesomeness of <a href="https://cruzersoftwares.github.io/fileMagician/" target="_blank">fileMagician</a></p>
        <?php if(isset($_SESSION['err']) && $_SESSION['err']!=''){?>
            <p><?php foreach($_SESSION['err'] as $msg) {
                echo $msg.PHP_EOL;
            }
            $_SESSION['err']=array();?></p>
        <?php }?>
      </div>

      <div class="form-label-group">
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputEmail">Email address</label>
      </div>

      <div class="form-label-group">
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <label for="inputPassword">Password</label>
      </div>

      <button class="btn btn-lg btn-primary btn-block" type="submit" name="signin" value="y">Sign in</button>
      <p class="mt-5 mb-3 text-muted text-center">&copy; <?php echo date('Y');?></p>
    </form>
</body>
</html>
