<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Database connection
include('database.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all bookings
$sql = "SELECT bahagian, lain, tempat, tujuan, jenis, tarikh, masa_mula, masa_akhir, status, user_id FROM tempahan";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    // Skip entries with status 'gagal'
    if ($row['status'] === 'gagal') {
        continue;
    }

    // Set the color based on the status
    $color = match ($row['status']) {
        'lulus' => '#FBBC05', // Yellow for 'lulus'
        'menunggu' => '#4285F4', // Blue for 'menunggu' 
        default => '#FFFFFF', // Default color
    };

    // Add the booking to the array
    $bookings[] = [
        'title' => htmlspecialchars($row['tempat']),
        'start' => $row['tarikh'] . 'T' . $row['masa_mula'],
        'end' => $row['tarikh'] . 'T' . $row['masa_akhir'],
        'backgroundColor' => $color,
        'borderColor' => $color,
        'status' => htmlspecialchars($row['status']),
        'bahagian' => htmlspecialchars($row['bahagian']),
        'lain' => isset($row['lain']) ? htmlspecialchars($row['lain']) : '',
        'tujuan' => htmlspecialchars($row['tujuan']),
        'tempat' => htmlspecialchars($row['tempat']),
    ];
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.5/index.global.min.css">
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
            margin-left: 20px;
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

        .title {
            font-size: 21px;
            font-family: 'Nunito', sans-serif;
            color: #4154F1;
            margin-bottom: 10px;
            margin-top: 30px;
            margin-left: -20px;
        }

        form {
            background: #EDF1F7;
            padding: 20px;
            border-radius: 8px;
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #legend {
            display: flex;
            gap: 0.6rem;
            font-family: 'Open Sans', sans-serif;
            margin-top: 25px;
            margin-right: -24px;

        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 6px 8px;
            border-radius: 3px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        select {
            width: 200px;
            height: 30px;
            padding: 5px;
            font-size: 14px;

        }

        #calendar {
            margin-top: 20px;
            height: 2100px;
        }

        .fc-prev-button,
        .fc-next-button {
            background-color: #2c3e50 !important;
            color: #F4F4F5 !important;
            padding: 3px 10px !important;
            transition: all 0.3s ease !important;
        }

        .fc-prev-button:hover,
        .fc-next-button:hover {
            background-color: darkgray !important;
        }

        .fc-toolbar-title {
            font-size: 23px !important;
            color: #2c3e50 !important;
            margin-right: 600px !important;
        }

        #searchButton {
            padding: 5px 10px;
            /* Make the button larger */
            background-color: #4f46e5;
            /* Soft blue background */
            border: none;
            /* Remove border */
            border-radius: 30px;
            /* Add rounded corners */
            color: #ffffff;
            /* White text color */
            cursor: pointer;
            /* Pointer cursor on hover */
            transition: background-color 0.3s ease;
            /* Smooth hover effect */
            margin-left: 10px;
            margin-top: 5px;
        }

        #searchButton:hover {
            background-color: #3730a3;
            /* Slightly darker blue on hover */
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
            <div class="head">
                <div class="title">Halaman Utama</div>
                <div id="legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #4285F4;"></div>
                        <span>Menunggu</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #FBBC05;"></div>
                        <span>Telah Ditempah</span>
                    </div>
                </div>
            </div>
        </form>



        <div>
            <label for="facility">Tempahan:</label>
            <select id="facility" onchange="updateDropdown()">
                <option value="">Sila Pilih</option>
                <option value="dewan">Dewan</option>
                <option value="bilik">Bilik Mesyuarat</option>
            </select>

            <label for="name">Tempat:</label>
            <select id="name" disabled>
                <option value="">Sila Pilih</option>
            </select>

            <label for="month">Pilih Bulan:</label>
            <input type="month" id="month">

            <button type="button" id="searchButton">
                <ion-icon name="search-outline" style="font-size: 20px;"></ion-icon>
            </button>


            <div id="calendar"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.5/index.global.min.js"></script>
    <script>
        const nameDropdown = document.getElementById('name');
        const calendarEl = document.getElementById('calendar');
        const searchButton = document.getElementById('searchButton');
        let calendar;

        const facilities = {
            dewan: [
                'Dewan Besar',
                'Dewan Saidina Abu Bakar',
                'Dewan Saidina Omar',
                'Dewan Saidina Othman',
                'Dewan Saidina Ali',
                'Dewan Makan Awam',
                'Dewan Makan VVIP'
            ],
            bilik: [
                'Bilik Mesyuarat Utama',
                "Bilik Mesyuarat Dato' Pengarah",
                'Bilik Mesyuarat Pentadbiran',
                'Bilik Mesyuarat Annex'
            ]
        };

        const bookings = <?php echo json_encode($bookings); ?>;

        function updateDropdown() {
            const facility = document.getElementById('facility').value;
            nameDropdown.disabled = !facility;
            nameDropdown.innerHTML = '<option value="">Sila Pilih</option>';

            if (facility) {
                facilities[facility].forEach(name => {
                    const option = document.createElement('option');
                    option.value = name;
                    option.textContent = name;
                    nameDropdown.appendChild(option);
                });
            }
        }

        function showPopup(title, messageArray) {
            const popup = document.createElement('div');
            popup.style.position = 'fixed';
            popup.style.top = '50%';
            popup.style.left = '50%';
            popup.style.transform = 'translate(-50%, -50%)';
            popup.style.backgroundColor = '#fff';
            popup.style.padding = '20px';
            popup.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            popup.style.borderRadius = '8px';
            popup.style.zIndex = '1000';
            popup.style.width = '300px';

            const popupTitle = document.createElement('h3');
            popupTitle.textContent = title;
            popupTitle.style.marginBottom = '10px';
            popupTitle.style.color = '#333';

            const popupMessage = document.createElement('ul');
            popupMessage.style.color = '#555';
            popupMessage.style.lineHeight = '1.5';
            popupMessage.style.paddingLeft = '20px';

            // Format messageArray into a list
            messageArray.forEach(item => {
                const listItem = document.createElement('li');
                listItem.textContent = item;
                popupMessage.appendChild(listItem);
            });

            const closeButton = document.createElement('button');
            closeButton.textContent = 'Tutup';
            closeButton.style.marginTop = '15px';
            closeButton.style.padding = '10px 20px';
            closeButton.style.backgroundColor = '#4285F4';
            closeButton.style.color = '#fff';
            closeButton.style.border = 'none';
            closeButton.style.borderRadius = '5px';
            closeButton.style.cursor = 'pointer';

            closeButton.onclick = () => {
                document.body.removeChild(popup);
            };

            popup.appendChild(popupTitle);
            popup.appendChild(popupMessage);
            popup.appendChild(closeButton);
            document.body.appendChild(popup);
        }

        function initializeCalendar() {
            if (calendar) {
                calendar.destroy();
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                slotMinTime: '08:00:00',
                slotMaxTime: '22:00:00',
                slotDuration: '00:10:00',
                allDaySlot: false,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: '',
                },
                events: bookings,
                eventContent: function(info) {
                    const {
                        title,
                        startStr,
                        endStr,
                        extendedProps
                    } = info.event;
                    const {
                        bahagian = '', lain = '', tujuan = ''
                    } = extendedProps; // Safely handle undefined fields

                    const time = startStr.slice(11, 16);
                    const endTime = endStr.slice(11, 16);

                    return {
                        html: `
                    <div>
                        <strong>${time} - ${endTime}</strong><br>
                        <strong>${title || ''}</strong><br>
                        <em>Bahagian: ${bahagian}</em><br>
                        <small>${lain}</small>
                        <span>Tujuan: ${tujuan}</span>
                    </div>
                `,
                    };
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                },
            });

            calendar.render();
        }

        // Ensure calendar is initialized on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCalendar();

            searchButton.addEventListener('click', function() {
                const facility = document.getElementById('facility').value;
                const name = document.getElementById('name').value;
                const month = document.getElementById('month').value;

                if (!facility || !name || !month) {
                    showPopup('Maklumat Tidak Lengkap', ['Sila pilih semua medan yang diperlukan.']);
                    return;
                }

                const selectedMonth = new Date(month);
                const matchingBookings = bookings.filter(booking => {
                    const bookingDate = new Date(booking.start);
                    return (
                        booking.tempat === name &&
                        bookingDate.getMonth() === selectedMonth.getMonth() &&
                        bookingDate.getFullYear() === selectedMonth.getFullYear()
                    );
                });

                if (matchingBookings.length === 0) {
                    showPopup('Tiada Rekod Dijumpai', ['Tiada tempahan dijumpai untuk pilihan anda.']);
                } else {
                    // Clear calendar events and add matching ones
                    calendar.getEvents().forEach(event => event.remove());
                    matchingBookings.forEach(booking => {
                        calendar.addEvent({
                            title: booking.title,
                            start: booking.start,
                            end: booking.end,
                            backgroundColor: booking.backgroundColor || '#FFD700', // Default color fallback
                            borderColor: booking.borderColor || '#FF8C00', // Default color fallback
                            extendedProps: {
                                bahagian: booking.bahagian,
                                tujuan: booking.tujuan,
                                tempat: booking.tempat,
                            },
                        });
                    });

                    // Redirect to the first matching booking's date
                    const firstBooking = matchingBookings[0];
                    calendar.gotoDate(firstBooking.start);

                    // Create popup message for matching bookings
                    const messageArray = matchingBookings.map(booking => {
                        const bookingDate = new Date(booking.start).toLocaleDateString('ms-MY');
                        return `Tempahan pada ${bookingDate}`;
                    });

                    showPopup('Rekod Tempahan Ditemui', messageArray);
                }
            });
        });
    </script>

</body>

</html>