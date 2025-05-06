<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Website Gloria</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .flash-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            z-index: 1000;
            animation: fadeInOut 1s forwards;
            display: none;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; top: 0; }
            10% { opacity: 1; top: 20px; }
            90% { opacity: 1; top: 20px; }
            100% { opacity: 0; top: 0; }
        }
    </style>
</head>
<body>
    <div id="flashMessage" class="flash-message"></div>
    <nav>
        <div class="nav__content">
            <div class="logo"><a href="index.html">Portfolio</a></div>
            <label for="check" class="checkbox">
                <i class="ri-menu-line"></i>
            </label>
            <input type="checkbox" name="check" id="check" />
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="contact">
        <h1>Get in touch</h1>
        <div class="inner-width">
            <!-- Contact Info -->
            <div class="contact-info">
                <div class="item">
                    <i class="ri-smartphone-line"></i>
                    +62 82375739474
                </div>
                <div class="item">
                    <i class="ri-mail-fill"></i>
                    gloriaelisabeth005@gmail.com
                </div>
                <div class="item">
                    <i class="ri-map-pin-2-fill"></i>
                    Palu, Sulawesi Tengah
                </div>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $db_host = "localhost";
                $db_user = "root";
                $db_pass = "";
                $db_name = "contact_form";
                
                try {
                    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // mengambil data
                    $fullname = htmlspecialchars($_POST['name']);
                    $firstName = explode(" ", $fullname)[0];
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $subject = htmlspecialchars($_POST['subject'] ?? 'No Subject');
                    $message = htmlspecialchars($_POST['message']);

                    // menyimpan ke database
                    $stmt = $conn->prepare("INSERT INTO messages (fullname, email, subject, message) 
                                          VALUES (:fullname, :email, :subject, :message)");
                    $stmt->execute([
                        ':fullname' => $fullname,
                        ':email' => $email,
                        ':subject' => $subject,
                        ':message' => $message
                    ]);
		    
                    session_start();
                    $_SESSION['flash_message'] = "$firstName, Pesanmu berhasil dikirim!";

                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();

                } catch(PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    $_SESSION['flash_message'] = "Terjadi error, coba lagi nanti";
                }
            }

            session_start();
            if (isset($_SESSION['flash_message'])) {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var flash = document.getElementById("flashMessage");
                        flash.textContent = "'.$_SESSION['flash_message'].'";
                        flash.style.display = "block";
                        setTimeout(function() {
                            flash.style.display = "none";
                        }, 5000);
                    });
                </script>';
                unset($_SESSION['flash_message']);
            }

            ?>

            <form method="POST" class="contact-form">
                <input type="text" name="name" class="nameZone" placeholder="Full Name" required>
                <input type="email" name="email" class="emailZone" placeholder="Email" required>
                <input type="text" name="subject" class="subjectZone" placeholder="Subject">
                <textarea name="message" class="messageZone" placeholder="Message" required></textarea>
                <input type="submit" value="Send Message" class="send_btn">
            </form>
        </div>
    </section>
</body>
</html>