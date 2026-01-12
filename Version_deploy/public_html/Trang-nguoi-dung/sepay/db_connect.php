<?php

    /*
    File db_connect.php
    File này dùng cho các file khác include vào. Mục đích để khởi tạo kết nối CSDL
    */
    
    
    // Khai báo cấu hình kết nối CSDL. Tuỳ chỉnh ở đây nếu tham số kết nối CSDL của bạn khác
    $servername = "localhost";
    $username = "u508775056_cinepass";
    $password = "Kpy123456@@";
    $dbname = "u508775056_cinepass";

    // Kết nối CSDL sử dụng MySQLi.
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        echo json_encode(['success'=>FALSE, 'message' => 'MySQL connection failed: '. $conn->connect_error]);
        die();
    }
    
?>