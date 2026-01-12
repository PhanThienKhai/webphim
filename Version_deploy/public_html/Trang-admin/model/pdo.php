<?php
// Prevent redeclaration
if (!function_exists('pdo_get_connection')) {
    function pdo_get_connection(){
        $servername = "localhost";
        $username = "u508775056_cinepass";
        $password = "Kpy123456@@";
        try {
            // FIX: Thêm charset=utf8mb4 để đảm bảo dữ liệu UTF-8 từ DB
            $conn = new PDO("mysql:host=$servername;dbname=u508775056_cinepass;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

if (!function_exists('pdo_execute')) {
    function pdo_execute($sql){
        $sql_args=array_slice(func_get_args(),1);
        try{
            $conn=pdo_get_connection();
            $stmt=$conn->prepare($sql);
            $stmt->execute($sql_args);

        }
        catch(PDOException $e){
            throw $e;
        }
        finally{
            unset($conn);
        }
    }
}

if (!function_exists('pdo_query')) {
    // truy vấn nhiều dữ liệu
    function pdo_query($sql){
        $sql_args=array_slice(func_get_args(),1);

        try{
            $conn=pdo_get_connection();
            $stmt=$conn->prepare($sql);
            $stmt->execute($sql_args);
            $rows=$stmt->fetchAll();
            return $rows;
        }
        catch(PDOException $e){
            throw $e;
        }
        finally{
            unset($conn);
        }
    }
}

if (!function_exists('pdo_query_one')) {
    // truy vấn  1 dữ liệu
    function pdo_query_one($sql){
        $sql_args=array_slice(func_get_args(),1);
        try{
            $conn=pdo_get_connection();
            $stmt=$conn->prepare($sql);
            $stmt->execute($sql_args);
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            // đọc và hiển thị giá trị trong danh sách trả về
            return $row;
        }
        catch(PDOException $e){
            throw $e;
        }
        finally{
            unset($conn);
        }
    }
}

if (!function_exists('pdo_get_connection')) {
    pdo_get_connection();
}