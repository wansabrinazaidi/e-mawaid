<?php
session_start();
require_once 'mail.php';
$conn = new mysqli("localhost", "root", "", "e-tempahan");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $bahagian = $conn->real_escape_string($_POST['bahagian'] ?? '');
    $lain = $conn->real_escape_string($_POST['lain'] ?? '');
    $tujuan = $conn->real_escape_string($_POST['tujuan'] ?? '');
    $tempahan = $conn->real_escape_string($_POST['tempahan'] ?? '');
    $tempat = $conn->real_escape_string($_POST['tempat'] ?? '');
    $jenis = $conn->real_escape_string($_POST['jenis'] ?? '');
    $tarikh = $conn->real_escape_string($_POST['tarikh'] ?? '');
    $masa_mula = $conn->real_escape_string($_POST['masa_mula'] ?? '');
    $masa_akhir = $conn->real_escape_string($_POST['masa_akhir'] ?? '');
    $status = 'menunggu';

    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo "<script>alert('Ralat: Pengguna tidak log masuk.');</script>";
        exit;
    }

    if (empty($bahagian) || empty($tujuan) || empty($tempahan) || empty($tempat) || empty($jenis) || empty($tarikh)) {
        echo "<script>alert('Sila lengkapkan semua ruangan yang diperlukan.');</script>";
    } else {
        $sql = "INSERT INTO tempahan (user_id, bahagian, lain, tujuan, tempahan, tempat, jenis, tarikh, masa_mula, masa_akhir)
            VALUES ('$user_id', '$bahagian', '$lain', '$tujuan', '$tempahan', '$tempat', '$jenis', '$tarikh', '$masa_mula', '$masa_akhir')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Tempahan berjaya direkodkan!');</script>";

            $to = ADMIN_EMAILS; // Admin emails (set in config.php)
            $subject = "Tempahan Baru Dihantar";
            $message = "
                <html>
                <head>
                    <title>Tempahan Baru</title>
                </head>
                <body>
                    <p>Butiran tempahan baru telah dihantar:</p>
                    <ul>
                        <li>Bahagian: $bahagian</li>
                        <li>Detail: $lain</li>
                        <li>Tujuan: $tujuan</li>
                        <li>Tempat: $tempat</li>
                        <li>Tarikh: $tarikh</li>
                        <li>Masa: $masa_mula hingga $masa_akhir</li>
                    </ul>
                    <p>Sila semak sistem untuk maklumat lanjut.</p>
                </body>
                </html>
            ";

            if (sendEmail($to, $subject, $message)) {
                echo "<script>alert('Email berjaya dihantar kepada penerima.');</script>";
            } else {
                echo "<script>alert('Ralat: Email tidak dapat dihantar.');</script>";
            }
        } else {
            echo "<script>alert('Ralat: " . $conn->error . "');</script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <title>Tempahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #EDF1F7;
            overflow: hidden;
        }

        .sidebar {
            width: 200px;
            background: #ffffff;
            color: #012970;
            height: 100vh;
            padding-top: 60px;
            border-right: 1px solid #ccc;
        }

        .sidebar a {
            text-decoration: none;
            color: #012970;
            padding: 15px 15px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 1.02rem;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #F6F9FF;
            color: #4154F1;
        }

        .sidebar a ion-icon {
            margin-right: 10px;
            font-weight: bold;
        }

        .sidebar-section {
            margin-top: 10px;
        }

        .sidebar-section .section-title {
            font-size: 0.7rem;
            font-weight: bold;
            color: #899BBD;
            display: block;
            margin-bottom: 5px;
            margin-left: 13px;
        }

        .sidebar a {
            text-decoration: none;
            color: #012970;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #F6F9FF;
            color: #4154F1;
        }

        .sidebar a ion-icon {
            margin-right: 10px;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;
            font-size: 1.3rem;
            font-weight: bold;
            color: #2c3e50;
            text-align: left;
            padding: 10px 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            font-size: 1.3rem;
            font-weight: bold;
        }

        header .profile {
            display: flex;
            align-items: center;
        }

        header .profile img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .title {
            font-size: 21px;
            font-family: 'Nunito', sans-serif;
            color: #4154F1;
            margin-bottom: 23px;
            margin-top: 30px;
        }

        form {
            background: #EDF1F7;
            padding: 20px;
            border-radius: 8px;
        }

        form .form-group {
            margin-bottom: 15px;
        }

        form label {
            display: block;
            margin-bottom: 9px;
            font-weight: 500;
        }

        form input,
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
        }


        form input[type="radio"] {
            width: auto;
        }

        .form-group-inline {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .form-sections {
            display: flex;
            justify-content: space-between;
            gap: 70px;
        }

        .form-left,
        .form-right {
            flex: 1;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-actions {
            margin-top: 3px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        form button {
            background: #4f46e5;
            color: white;
            padding: 10px 23px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        form button:hover {
            background: #3730a3;
        }

        #batal {
            background: #ffffff;
            color: #000;
            border: 1px solid #ccc;
            transition: all 0.3s ease-in-out;
        }

        #batal:hover {
            background: #f1f1f1;
            color: #333;
            border-color: #999;
        }

        #hantar {
            background: #4f46e5;
            color: white;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        #hantar:hover {
            background: #3730a3;
        }
    </style>
</head>

<body>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <div class="sidebar">
        <a href="Admin Home.php"><ion-icon name="home-outline"></ion-icon> Halaman Utama</a>
        <a href="Admin Tempah.php"><ion-icon name="calendar-outline"></ion-icon> Tempah</a>
        <a href="Admin Proses.php"><ion-icon name="layers-outline"></ion-icon> Rekod</a>
        <a href="Admin Manual.php"><ion-icon name="book-outline"></ion-icon> Panduan</a>
        <div class="sidebar-section">
            <span class="section-title">TETAPAN</span>
            <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon> Keluar</a>
        </div>
    </div>


    <div class="content">

        <header>
            <div class="logo">e-Mawāʿid </div>
            <div class="profile">
                <img src="profile.png" alt="Gambar Profil">
                <span id="username">
                    <?php echo htmlspecialchars($username); ?>
                </span>
            </div>
        </header>

        <form id="bookingForm" method="POST" action="Admin Tempah.php">
            <div class="title">Permohonan Menempah</div>

            <div class="form-sections">
                <div class="form-left">
                    <div class="form-group">
                        <label for="bahagian">Bahagian <span style="color: red;">*</span></label>
                        <select id="bahagian" name="bahagian" data-mandatory required>
                            <option value="">Sila Pilih</option>
                            <option value="Penyelidikan">Penyelidikan</option>
                            <option value="Undang-Undang Keluarga Islam">Undang-Undang Keluarga Islam</option>
                            <option value="Pengurusan">Pengurusan</option>
                            <option value="Halal">Halal</option>
                            <option value="Halaqat">Halaqat</option>
                            <option value="Dakwah">Dakwah</option>
                            <option value="Penguatkuasa Undang-Undang">Penguatkuasa Undang-Undang</option>
                            <option value="Pejabat Agama Jajahan">Pejabat Agama Jajahan</option>
                            <option value="Agensi Luar">Agensi Luar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lain">Lain-Lain (Jika Berkenaan)</label>
                        <input type="text" id="lain" name="lain">
                    </div>

                    <div class="form-group">
                        <label for="tujuan">Tujuan <span style="color: red;">*</span></label>
                        <input type="text" id="tujuan" name="tujuan" data-mandatory required>
                    </div>

                    <div class="form-group">
                        <label for="tempahan">Tempahan <span style="color: red;">*</span></label>
                        <div class="form-group-inline">
                            <label><input type="radio" name="tempahan" value="dewan" checked> Dewan</label>
                            <label><input type="radio" name="tempahan" value="bilik"> Bilik Mesyuarat</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tempat">Tempat <span style="color: red;">*</span></label>
                        <select id="tempat" name="tempat" data-mandatory>
                        </select>
                    </div>
                </div>

                <div class="form-right">
                    <div class="form-group">
                        <label for="jenis">Jenis Sewaan <span style="color: red;">*</span></label>
                        <div class="form-group-inline">
                            <label><input type="radio" name="jenis" value="hari" checked> Sewaan Per Hari</label>
                            <label><input type="radio" name="jenis" value="jam"> Sewaan Per Jam</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tarikh">Tarikh <span style="color: red;">*</span></label>
                        <input type="date" id="tarikh" name="tarikh" data-mandatory required>
                    </div>

                    <div class="form-group">
                        <label for="masa_mula">Masa Bermula <span style="color: red;">*</span></label>
                        <input type="time" id="masa_mula" name="masa_mula" data-mandatory required>
                    </div>

                    <div class="form-group">
                        <label for="masa_akhir">Masa Berakhir <span style="color: red;">*</span></label>
                        <input type="time" id="masa_akhir" name="masa_akhir" data-mandatory required>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" id="batal" class="btn btn-secondary">Batal</button>
                <button type="submit" id="hantar" class="btn btn-primary">Hantar</button>
            </div>
        </form>
    </div>


    <script>
        const tempatDropdown = document.getElementById('tempat');
        const tempahanRadios = document.getElementsByName('tempahan');

        const dewanOptions = [
            "Dewan Besar",
            "Dewan Saidina Abu Bakar",
            "Dewan Saidina Omar",
            "Dewan Saidina Othman",
            "Dewan Saidina Ali",
            "Dewan Makan Awam",
            "Dewan Makan VVIP",
        ];

        const bilikOptions = [
            "Bilik Mesyuarat Utama",
            "Bilik Mesyuarat Dato' Pengarah",
            "Bilik Mesyuarat Pentadbiran",
            "Bilik Mesyuarat Annex",
        ];

        function populateTempat(options) {
            tempatDropdown.innerHTML = '';
            options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option;
                opt.textContent = option;
                tempatDropdown.appendChild(opt);
            });
        }

        tempahanRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                if (e.target.value === 'dewan') {
                    populateTempat(dewanOptions);
                } else {
                    populateTempat(bilikOptions);
                }
            });
        });
        populateTempat(dewanOptions);

        document.getElementById('hantar').addEventListener('click', function(e) {
            e.preventDefault();

            const mandatoryFields = document.querySelectorAll("[data-mandatory]");
            let isValid = true;

            mandatoryFields.forEach((field) => {
                if (field.value.trim() === "") {
                    isValid = false;
                    field.style.borderColor = "red";
                } else {
                    field.style.borderColor = "";
                }
            });

            if (!isValid) {
                alert("Sila isi semua bahagian wajib sebelum menghantar.");
                return;
            }

            const confirmHantar = confirm('Adakah anda pasti untuk menghantar borang ini?');
            if (confirmHantar) {
                alert('Borang telah dihantar.');
                document.getElementById('bookingForm').submit();
            } else {
                alert('Penghantaran borang dibatalkan.');
            }
        });

        document.getElementById('batal').addEventListener('click', function() {
            const confirmBatal = confirm('Adakah anda pasti untuk membatalkan?');
            if (confirmBatal) {
                alert('Borang telah dibatalkan.');
                document.getElementById('bookingForm').reset();
                const mandatoryFields = document.querySelectorAll("[data-mandatory]");
                mandatoryFields.forEach((field) => (field.style.borderColor = ""));
            }
        });
    </script>
</body>

</html>