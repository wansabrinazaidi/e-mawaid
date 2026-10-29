<?php
session_start();
include('database.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true); // Decode JSON payload
    if (isset($data['action']) && $data['action'] === "update_status") {
        $response = ["success" => false];

        $id = intval($data['id']);
        $keputusan = $conn->real_escape_string($data['keputusan']); // "Lulus" or "Gagal"

        $updateQuery = "UPDATE tempahan SET status = '$keputusan' WHERE id = $id";
        if ($conn->query($updateQuery)) {
            $response["success"] = true;
            $response["message"] = "Keputusan telah disimpan.";
        } else {
            $response["error"] = "Keputusan gagal disimpan: " . $conn->error;
        }
        echo json_encode($response);
        exit();
    }
}

// Fetch data from tempahan table where status is not 'Lulus' or 'Gagal'
$sql = "SELECT * FROM tempahan WHERE status NOT IN ('Lulus', 'Gagal')";
$result = $conn->query($sql);
if (!$result) {
    die("Error retrieving data: " . $conn->error);
}

$tempahanData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tempahanData[] = $row;
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            height: 100%;
            background-color: #EDF1F7;
            overflow: auto;
            flex-direction: row;
        }

        .sidebar {
            width: 200px;
            background: #ffffff;
            color: #012970;
            height: 100vh;
            padding-top: 60px;
            border-right: 1px solid #ccc;
            flex-shrink: 0;
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

        .table-container {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .approve-btn {
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .reject-btn {
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .approve-btn:hover,
        .reject-btn:hover {
            opacity: 0.8;
        }

        .controls {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 10px;
        }

        .pagination button {
            margin: 5px;
            padding: 5px 10px;
            background-color: #4154F1;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .pagination button:hover {
            background-color: #293a8b;
        }

        .pagination button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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
            <div class="logo">e-Mawāʿid </div>
            <div class="profile">
                <img src="profile.png" alt="Profile Picture">
                <span id="username"><?php echo htmlspecialchars($username); ?></span>
            </div>
        </header>

        <form id="bookingForm">
            <div class="title">Keputusan Tempahan</div>
            <div class="table-container">
                <div class="controls">
                    <label for="search-month">Cari Mengikut Bulan :</label>
                    <input type="month" id="search-month" onchange="searchByMonth()">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Bahagian</th>
                            <th>Detail</th>
                            <th>Tujuan</th>
                            <th>Tempat</th>
                            <th>Jenis Sewaan</th>
                            <th>Tarikh</th>
                            <th>Masa Bermula</th>
                            <th>Masa Berakhir</th>
                            <th>Keputusan</th>
                        </tr>
                    </thead>
                    <tbody id="tempahan-body">
                        <!-- Row akan masuk sini-->
                    </tbody>
                </table>
                <div class="pagination" id="pagination">
                    <!-- Utk pagination buttons -->
                </div>
            </div>
        </form>
    </div>

    <script>
        // PHP Data passed as JSON to JavaScript
        const data = <?php echo json_encode($tempahanData); ?>;

        let currentPage = 1;
        const rowsPerPage = 5; // Adjust to 5 rows per page
        let filteredData = [...data]; // Clone backend data for filtering

        function convertTo12HourFormat(time) {
            if (!time) return '-';
            const [hour, minute] = time.split(':'); // Ignore seconds and milliseconds
            const date = new Date();
            date.setHours(parseInt(hour, 10), parseInt(minute, 10));
            return date.toLocaleString('en-US', {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            });
        }

        function renderTable() {
            const tableBody = document.getElementById('tempahan-body');
            tableBody.innerHTML = '';

            const startIndex = (currentPage - 1) * rowsPerPage;
            const paginatedData = filteredData.slice(startIndex, startIndex + rowsPerPage);

            paginatedData.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td>${startIndex + index + 1}</td>
                <td>${row.bahagian}</td>
                <td>${row.lain || '-'}</td>
                <td>${row.tujuan}</td>
                <td>${row.tempat}</td>
                <td>${row.jenis}</td>
                <td>${row.tarikh}</td>
                <td>${convertTo12HourFormat(row.masa_mula)}</td>
                <td>${convertTo12HourFormat(row.masa_akhir)}</td>
                <td>
                    <div class="btn-container">
                        <button class="approve-btn" onclick="updateStatus(${row.id}, 'Lulus', this)">✔</button>
                        <button class="reject-btn" onclick="updateStatus(${row.id}, 'Gagal', this)">✖</button>
                    </div>
                </td>
            `;
                tableBody.appendChild(tr);
            });

            renderPagination();
        }

        function renderPagination() {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';

            const totalPages = Math.ceil(filteredData.length / rowsPerPage);

            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.disabled = i === currentPage;

                button.addEventListener('click', () => {
                    currentPage = i;
                    renderTable();
                });

                paginationDiv.appendChild(button);
            }
        }

        function updateStatus(id, keputusan, buttonElement) {
            const confirmation = confirm(`Adakah anda pasti untuk ${keputusan === 'Lulus' ? 'lulus' : 'gagal'} tempahan ini?`);
            if (!confirmation) return;

            buttonElement.disabled = true;
            buttonElement.style.opacity = 0.6;

            const payload = {
                action: "update_status",
                id: id,
                keputusan: keputusan
            };

            fetch("<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        buttonElement.closest("tr").remove();
                    } else {
                        alert(`Error: ${data.error}`);
                        buttonElement.disabled = false;
                        buttonElement.style.opacity = 1;
                    }
                })
                .catch(error => {
                    alert(`Network error: ${error.message}`);
                    buttonElement.disabled = false;
                    buttonElement.style.opacity = 1;
                });
        }

        function searchByMonth() {
            const selectedMonth = document.getElementById('search-month').value;
            filteredData = data.filter(row => row.tarikh.startsWith(selectedMonth) || selectedMonth === '');
            currentPage = 1;
            renderTable();
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderTable(); // Initial render
        });
    </script>




</body>

</html>