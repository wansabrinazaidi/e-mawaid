<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Admin</title>
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
            overflow-y: auto;
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


        .title-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            font-size: 21.5px;
            font-family: 'Nunito', sans-serif;
            color: #4154F1;
            margin-bottom: 23px;
            margin-top: 49px;
            margin-left: 22px;
        }

        .scrollable-sections {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding-bottom: 10px;
        }

        .section-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            margin-left: 19px;
            width: 700px;
            height: 1100px;
        }

        .section-box img {
            width: 90%;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
            margin-top: 20px;
            object-fit: contain;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .section-box p1 {
            font-size: 1.05rem;
            color: #333;
            margin-left: 13px;
        }

        .section-box p {
            font-size: 1.05rem;
            color: #333;
            margin-left: 30px;
            margin-top: 16px;
            margin-bottom: 17px;
        }


        .section-box h1 {
            font-size: 18px;
            color: #012970;
            margin-left: 12px;
            margin-bottom: 15px;
        }

        .highlight {
            color: #012970;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <div class="sidebar">
        <a href="Admin Home.php"><ion-icon name="home-outline"></ion-icon> Halaman Utama</a>
        <a href="Admin Tempah.php"><ion-icon name="calendar-outline"></ion-icon> Tempah</a>
        <a href="Admin Proses.php"><ion-icon name="bookmark-outline"></ion-icon> Proses</a>
        <a href="Admin Manual.php"><ion-icon name="book-outline"></ion-icon> Panduan</a>
        <div class="sidebar-section">
            <span class="section-title">TETAPAN</span>
            <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon> Keluar</a>
        </div>
    </div>

    <div class="content">
        <header>
            <div class="logo">e-Mawāʿid</div>
            <div class="profile">
                <img src="profile.png" alt="Profile Picture">
                <span id="username">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
                </span>
            </div>
        </header>

        <div class="title-container">
            <div class="title">Panduan Penggunaan</div>
        </div>

        <div class="scrollable-sections">
            <div class="section-box">
                <p1>! Jika terdapat sebarang <span class="highlight">masalah</span>, sila hubungi pentadbir/admin untuk bantuan lanjut: </p1>
                <p>1. <span class="highlight">09-7443633 EXT 309</span> (Cik Siti Nur 'Atiah Binti Mohamad)
					<br>2. <span class="highlight">09-7443633 EXT 307</span> (Roslina Binti Dato Hj Ismail)
					<br>3. <span class="highlight">09-7443633 EXT 412</span> (Nur Liyana Binti Kamarudin)
					<br>4. <span class="highlight">09-7443633 EXT 308</span> (Salihatun Alawiah Binti Abdul Hadis@Malik)
                </p>
                <h1>Kapasiti Setiap Dewan:</h1>
                <table class="room-capacity-table">
                    <thead>
                        <tr>
                            <th>BIL</th>
                            <th>TEMPAT</th>
                            <th>KAPASITI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Dewan Besar</td>
                            <td>1400 Orang</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Dewan Saidina Abu Bakar</td>
                            <td>135 Orang</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Dewan Saidina Omar</td>
                            <td>Meja: 65 Orang, Kerusi: 100 Orang</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Dewan Saidina Othman</td>
                            <td>60 Orang</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Dewan Saidina Ali </td>
                            <td>60 Orang</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Dewan Makan Awam</td>
                            <td>120 Orang</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Dewan Makan VIP</td>
                            <td>50 Orang</td>
                        </tr>
                    </tbody>
                </table>
                <br></br>
                <h1>Kadar Sewaan:</h1>
                <table class="room-capacity-table">
                    <thead>
                        <tr>
                            <th>BIL</th>
                            <th>TEMPAT</th>
                            <th>KADAR SEWAAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Dewan Besar</td>
                            <td>- RM1200.00 untuk 4 jam pertama<br>- RM200.00 bagi setiap tambahan sejam<br>- RM4500.00 bagi majlis perkahwinan</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Dewan Saidina Abu Bakar</td>
                            <td>RM550.00 sehari</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Dewan Saidina Omar</td>
                            <td>RM350.00 sehari</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Dewan Saidina Othman</td>
                            <td>RM300.00 sehari</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Dewan Saidina Ali</td>
                            <td>RM300.00 sehari</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Dewan Makan Awam</td>
                            <td>RM150.00 sehari</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Dewan Makan VIP</td>
                            <td>RM100.00 sehari</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Sistem Siaraya (PA System)</td>
                            <td>- PA Sistem Dewan Besar RM 300.00 sehari<br>- PA Sistem Dewan lain RM 150.00 sehari</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <style>
                .room-capacity-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                .room-capacity-table th,
                .room-capacity-table td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                }

                .room-capacity-table th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }

                .room-capacity-table td {
                    font-size: 16px;
                }

                .highlight {
                    font-weight: bold;
                    color: #3730a3;
                }
            </style>
            <div class="section-box">
                <h1>Halaman Utama</h1>
                <p1>1. Terdapatnya <span class="highlight">Kalendar Tempahan</span> yang memaparkan semua tarikh dan masa yang <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; telah ditempah.</p1>
                <p><span class="highlight">Carian Tempahan:</span> Gunakan fungsi carian untuk memilih jenis dewan atau bilk mesyuarat yang ingin ditempah.</p>
                <img src="Manual_img/halamanA1.png" alt="Screenshot 1" class="center">
                <p><span class="highlight">Pilihan Bulan:</span> Juga boleh memilih bulan tertentu untuk melihat jadual tempahan bagi bulan tersebut sahaja.</p>
                <img src="Manual_img/halamanA2.png" alt="Screenshot 2" class="center">
                <p1>2. Kalendar akan menunjukkan <span class="highlight">status tempahan</span> dengan menggunakan warna<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; penunjuk tertentu:</p1>
                <p><span class="highlight">Kuning:</span> Menunjukkan tempat pada waktu tersebut sudah ditempah oleh bahagian lain dan <span class="highlight">telah diluluskan</span> oleh anda sebagai admin.
                    <br><span class="highlight">Biru:</span> Menunjukkan tempahan yang baru dibuat pengguna bahagian lain dan <span class="highlight">masih menunggu kelulusan</span> anda.
                </p>
                <p1>3. Setelah melakukan carian (fungsi search), kalendar akan memaparkan hanya
                    <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; maklumat tempahan yang berkaitan dengan pilihan dewan/bilik mesyuarat anda
                    <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; dan bulan yang anda pilih.
                </p1>
            </div>

            <div class="section-box">
                <h1>Tempah</h1>
                <p1>1. Sebagai admin, anda boleh <span class="highlight">membuat tempahan bagi pihak pengguna lain,</span>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;terutamanya jika melibatkan acara rasmi atau kecemasan. Klik pada <span class="highlight">Tempah</span> untuk
                    <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mula mengisi borang tempahan.
                </p1>
                <img src="Manual_img/tempahA.png" alt="Screenshot 3" class="center">
                <p1>2. Isikan maklumat berikut:</p1>
                <p><span class="highlight">- Jabatan:</span> Pilih jabatan yang anda ingin tempahkan.
                    <br><span class="highlight">- Butiran Tambahan:</span> Isikan jika permohonan dari agensi luar / pejabat agama
                    <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;jajahan.
                    <br><span class="highlight">- Tujuan Tempahan:</span> Nyatakan tujuan membuat tempahan.
                    <br><span class="highlight">- Jenis Tempahan:</span> Pilih sama ada Dewan atau Bilik Mesyuarat.
                    <br><span class="highlight">- Tempat:</span> Pilih nama dewan atau bilik mesyuarat yang sesuai.
                    <br><span class="highlight">- Jenis Sewaan:</span> Pilih sama ada sewa per hari atau per jam.
                    <br><span class="highlight">- Tarikh Tempahan:</span> Pilih tarikh yang diingini.
                    <br><span class="highlight">- Masa Mula dan Masa Tamat:</span> Pilih masa mula dan tamat tempahan.
                </p>
                <p1>3. Setelah mengisi borang, klik <span class="highlight">Hantar</span> untuk memproses permohonan anda.
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tempahan anda akan dihantar untuk kelulusan admin.
                </p1>
            </div>

            <div class="section-box">
                <h1>Proses</h1>
                <p1>1. Sebagai admin, anda boleh melihat permohonan tempahan yang sedang menunggu
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;kelulusan. Klik pada <span class="highlight">Proses</span> untuk melihat senarai permohonan.
                </p1>
                <img src="Manual_img/proses.png" alt="Screenshot 4" class="center">
                <p1>2. Pada halaman ini, pilih sama ada untuk <span class="highlight">meluluskan atau menolak</span> tempahan
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tersebut dengan mengetik butang <span class="highlight">✔</span> atau <span class="highlight">✖</span>.
                </p1>
                <p>- <span class="highlight">Meluluskan:</span> Tempahan akan ditandakan sebagai <span class="highlight">Lulus</span> dan tempat tersebut akan
                    <br>&nbsp;&nbsp;<span class="highlight">dianggap rasmi</span> untuk pemohon.
                    <br>- <span class="highlight">Menolak: </span>Tempahan akan ditandakan sebagai <span class="highlight">Gagal</span>, dan pemohon akan
                    <br>&nbsp;&nbsp;dimaklumkan bahawa <span class="highlight">tempahan mereka tidak diluluskan</span>.
                </p>
                <p1>3. Pastikan anda telah <span class="highlight">menyemak butiran </span>berikut sebelum membuat keputusan:</p1>
                <p>i. Nama pemohon dan jabatan/agensi mereka.
                    <br>ii. Tarikh, masa, dan lokasi tempahan.
                    <br>iii. Tujuan tempahan.
                </p>
                <p1>4. Jika terdapat <span class="highlight">kesilapan atau maklumat yang tidak jelas,</span> admin boleh:</p1>
                <p>i. Menghubungi pemohon untuk mendapatkan penjelasan lanjut.
                    <br>ii. Meminta pemohon untuk memadam dan membuat permohonan baru jika perlu.
                </p>
                <p1>5. Anda juga boleh menggunakan <span class="highlight">fungsi carian (search)</span> untuk mencari rekod
                    <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; tempahan berdasarkan bulan.
                </p1>
                <img src="Manual_img/proses2.png" alt="Screenshot 5" class="center">

            </div>
        </div>
    </div>
</body>

</html>