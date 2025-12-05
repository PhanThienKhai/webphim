<?php
/**
 * Calendar Schedule Management Handler
 * Xử lý các action: tạo, sửa, xóa lịch làm việc
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? null;
    
    // ============================================
    // CREATE MULTIPLE ASSIGNMENTS
    // ============================================
    if ($action === 'create_assignments') {
        $assignments = $input['assignments'] ?? [];
        
        if (empty($assignments)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không có dữ liệu phân công']);
            exit;
        }
        
        $success_count = 0;
        $error_count = 0;
        $errors = [];
        
        try {
            // Assuming you have a database connection available
            // Adjust according to your database setup
            
            foreach ($assignments as $assign) {
                try {
                    $nhanvien_id = (int)$assign['nhanvien_id'];
                    $ngay = trim($assign['ngay']);
                    $gio_bat_dau = trim($assign['gio_bat_dau']);
                    $gio_ket_thuc = trim($assign['gio_ket_thuc']);
                    $ca_lam = trim($assign['ca_lam']);
                    $ghi_chu = trim($assign['ghi_chu'] ?? '');
                    
                    // Validate inputs
                    if (!$nhanvien_id || !$ngay || !$gio_bat_dau || !$gio_ket_thuc || !$ca_lam) {
                        throw new Exception('Dữ liệu không đầy đủ');
                    }
                    
                    if ($gio_bat_dau >= $gio_ket_thuc) {
                        throw new Exception('Giờ kết thúc phải sau giờ bắt đầu');
                    }
                    
                    // Check for duplicate entry
                    $checkSql = "SELECT COUNT(*) as cnt FROM chamcong 
                               WHERE nhanvien_id = ? AND ngay = ? AND gio_bat_dau = ? AND gio_ket_thuc = ?";
                    $checkStmt = $GLOBALS['conn']->prepare($checkSql);
                    $checkStmt->bind_param('isss', $nhanvien_id, $ngay, $gio_bat_dau, $gio_ket_thuc);
                    $checkStmt->execute();
                    $result = $checkStmt->get_result()->fetch_assoc();
                    $checkStmt->close();
                    
                    if ($result['cnt'] > 0) {
                        throw new Exception('Lịch này đã tồn tại');
                    }
                    
                    // Insert new record
                    $insertSql = "INSERT INTO chamcong (nhanvien_id, ngay, gio_bat_dau, gio_ket_thuc, ca_lam, ghi_chu) 
                                 VALUES (?, ?, ?, ?, ?, ?)";
                    $insertStmt = $GLOBALS['conn']->prepare($insertSql);
                    $insertStmt->bind_param('isssss', $nhanvien_id, $ngay, $gio_bat_dau, $gio_ket_thuc, $ca_lam, $ghi_chu);
                    
                    if ($insertStmt->execute()) {
                        $success_count++;
                    } else {
                        throw new Exception($insertStmt->error);
                    }
                    $insertStmt->close();
                    
                } catch (Exception $e) {
                    $error_count++;
                    $errors[] = $e->getMessage();
                }
            }
            
            // Determine response
            if ($success_count > 0 && $error_count === 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Phân công thành công',
                    'success_count' => $success_count
                ]);
            } elseif ($success_count > 0 && $error_count > 0) {
                echo json_encode([
                    'success' => false,
                    'partial_success' => true,
                    'message' => 'Một phần thành công',
                    'success_count' => $success_count,
                    'error_count' => $error_count,
                    'errors' => $errors
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể phân công',
                    'error_count' => $error_count,
                    'errors' => $errors
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
        }
        exit;
    }
    
    // ============================================
    // UPDATE SHIFT
    // ============================================
    elseif ($action === 'update_shift') {
        try {
            $id = (int)$input['id'];
            $gio_bat_dau = trim($input['gio_bat_dau']);
            $gio_ket_thuc = trim($input['gio_ket_thuc']);
            $ca_lam = trim($input['ca_lam']);
            $ghi_chu = trim($input['ghi_chu'] ?? '');
            
            if (!$id || !$gio_bat_dau || !$gio_ket_thuc || !$ca_lam) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không đầy đủ']);
                exit;
            }
            
            if ($gio_bat_dau >= $gio_ket_thuc) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Giờ kết thúc phải sau giờ bắt đầu']);
                exit;
            }
            
            // Update record
            $updateSql = "UPDATE chamcong SET gio_bat_dau = ?, gio_ket_thuc = ?, ca_lam = ?, ghi_chu = ? WHERE id = ?";
            $updateStmt = $GLOBALS['conn']->prepare($updateSql);
            $updateStmt->bind_param('ssssi', $gio_bat_dau, $gio_ket_thuc, $ca_lam, $ghi_chu, $id);
            
            if ($updateStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);
            } else {
                throw new Exception($updateStmt->error);
            }
            $updateStmt->close();
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
    
    // ============================================
    // DELETE SHIFT
    // ============================================
    elseif ($action === 'delete_shift') {
        try {
            $id = (int)$input['id'];
            
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
                exit;
            }
            
            // Delete record
            $deleteSql = "DELETE FROM chamcong WHERE id = ?";
            $deleteStmt = $GLOBALS['conn']->prepare($deleteSql);
            $deleteStmt->bind_param('i', $id);
            
            if ($deleteStmt->execute()) {
                if ($deleteStmt->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Xóa thành công']);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Không tìm thấy bản ghi']);
                }
            } else {
                throw new Exception($deleteStmt->error);
            }
            $deleteStmt->close();
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? null;
    
    // ============================================
    // GET SHIFT DETAILS
    // ============================================
    if ($action === 'get_shift') {
        try {
            $id = (int)($_GET['id'] ?? 0);
            
            if (!$id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
                exit;
            }
            
            // Fetch shift details
            $sql = "SELECT c.id, c.nhanvien_id, nv.name as ten_nv, c.ngay, c.gio_bat_dau, c.gio_ket_thuc, c.ca_lam, c.ghi_chu 
                    FROM chamcong c
                    LEFT JOIN taikhoan nv ON c.nhanvien_id = nv.id
                    WHERE c.id = ?";
            
            $stmt = $GLOBALS['conn']->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy shift']);
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
}

// Default response
http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
