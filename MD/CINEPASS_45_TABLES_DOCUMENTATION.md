# Tài Liệu Cơ Sở Dữ Liệu Hệ Thống Quản Lý Rạp Chiếu Phim GALAXY

## Mục Lục
1. [Giới thiệu](#giới-thiệu)
2. [Danh sách bảng dữ liệu](#danh-sách-bảng-dữ-liệu)
3. [Mô tả chi tiết các bảng](#mô-tả-chi-tiết-các-bảng)

---

## Giới Thiệu

Tài liệu này mô tả chi tiết 45 bảng cơ sở dữ liệu lõi của hệ thống quản lý rạp chiếu phim GALAXY, bao gồm các lĩnh vực:

- **Phim (Movies)**: Thông tin phim, thể loại, đánh giá
- **Rạp Chiếu (Cinema)**: Thông tin rạp, phòng chiếu, ghế ngồi
- **Lịch Chiếu (Showtimes)**: Lịch chiếu, khung giờ, thời gian chiếu
- **Vé (Tickets)**: Đặt vé, thanh toán, lịch sử xem phim
- **Nhân Viên (Employees)**: Tài khoản, nhân viên, lịch làm việc
- **Lương (Payroll)**: Bảng lương, chi tiết khoản chi, khấu trừ
- **Nội Dung (Content)**: Tin tức, bình luận, feedback

---

## Danh Sách Bảng Dữ Liệu

| STT | Tên Bảng | Mô Tả | Lĩnh Vực |
|-----|----------|-------|---------|
| 1 | bang_luong | Thông tin lương tháng | Payroll |
| 2 | bang_luong_chi_tiet | Chi tiết các khoản lương | Payroll |
| 3 | bang_luong_khautru | Khấu trừ lương | Payroll |
| 4 | bang_luong_lich_su | Lịch sử thay đổi lương | Payroll |
| 5 | bang_luong_phucap | Phụ cấp thêm | Payroll |
| 6 | binhluan | Bình luận về phim | Content |
| 7 | cau_hinh_luong_nv | Cấu hình lương nhân viên | Payroll |
| 8 | cham_cong | Chấm công/điểm danh | Employees |
| 9 | chi_tiet_combo | Chi tiết combo ăn uống | Combo |
| 10 | chi_tiet_khau_tru_thang | Chi tiết khấu trừ hàng tháng | Payroll |
| 11 | chi_tiet_phu_cap_thang | Chi tiết phụ cấp hàng tháng | Payroll |
| 12 | combo_do_an | Combo ăn uống | Cinema |
| 13 | cong_them_gio | Công thêm giờ | Employees |
| 14 | don_nghi_phep | Đơn nghỉ phép | Employees |
| 15 | hoa_don | Hóa đơn thanh toán | Tickets |
| 16 | khung_gio_chieu | Khung giờ chiếu | Showtimes |
| 17 | khuyen_mai | Khuyến mại | Promotions |
| 18 | lichchieu | Lịch chiếu phim | Showtimes |
| 19 | lich_lam_viec | Lịch làm việc | Employees |
| 20 | lich_su_diem | Lịch sử điểm tích lũy | Loyalty |
| 21 | lich_su_phe_duyet_bangluong | Lịch sử phê duyệt bảng lương | Payroll |
| 22 | lich_su_thanh_toan | Lịch sử thanh toán lương | Payroll |
| 23 | lich_su_thay_doi_bangluong | Lịch sử thay đổi bảng lương | Payroll |
| 24 | lich_su_xem_phim | Lịch sử xem phim | Tickets |
| 25 | loaiphim | Thể loại phim | Movies |
| 26 | loai_khau_tru | Loại khấu trừ | Payroll |
| 27 | loai_luong | Loại bảng lương | Payroll |
| 28 | loai_phu_cap | Loại phụ cấp | Payroll |
| 29 | ngay_nghi_phep | Ngày nghỉ phép | Employees |
| 30 | nhan_vien_rap | Nhân viên rạp | Employees |
| 31 | phim | Thông tin phim | Movies |
| 32 | phim_rap | Liên kết phim-rạp | Movies |
| 33 | phongchieu | Phòng chiếu | Cinema |
| 34 | phong_ghe | Ghế ngồi trong phòng | Cinema |
| 35 | quy_tac_tich_diem | Quy tắc tích điểm | Loyalty |
| 36 | rap_chieu | Thông tin rạp chiếu | Cinema |
| 37 | taikhoan | Tài khoản người dùng | Employees |
| 38 | thanh_toan | Thông tin thanh toán | Tickets |
| 39 | thiet_bi_phong | Thiết bị phòng chiếu | Cinema |
| 40 | thong_tin_website | Thông tin website | Settings |
| 41 | thuong_khen_thuong | Thưởng khen thưởng | Employees |
| 42 | tintuc | Tin tức | Content |
| 43 | tong_hop_luong_thang | Tổng hợp lương tháng | Payroll |
| 44 | tra_loi_binhluan | Trả lời bình luận | Content |
| 45 | ve | Vé chiếu phim | Tickets |
| 46 | voucher | Voucher giảm giá | Promotions |
| 47 | yeu_cau_ve | Yêu cầu vé | Tickets |

---

## Mô Tả Chi Tiết Các Bảng

### 1. BANG_LUONG - Bảng Lương Tháng

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã bảng lương | PRIMARY KEY, AUTO_INCREMENT |
| id_nv | int(11) | Mã nhân viên | FK → taikhoan.id |
| id_rap | int(11) | Mã rạp chiếu | FK → rap_chieu.id |
| thang | varchar(7) | Tháng tính lương (YYYY-MM) | NOT NULL |
| so_gio | decimal(10,2) | Số giờ làm việc | |
| luong_theo_gio | decimal(12,2) | Lương tính theo giờ | |
| phu_cap | decimal(12,2) | Phụ cấp thêm | |
| khau_tru | decimal(12,2) | Khấu trừ | |
| thuong | decimal(12,2) | Thưởng | |
| tong_luong | decimal(12,2) | Tổng lương | |
| trang_thai | enum | Trạng thái (dang_tinh, dang_duyet, da_duyet, da_thanh_toan) | |
| bhxh | decimal(12,2) | Bảo hiểm xã hội | |
| thue_thu_nhap | decimal(12,2) | Thuế thu nhập cá nhân | |
| tong_khau_tru | decimal(12,2) | Tổng khấu trừ | |
| trang_thai_khoa | tinyint | Trạng thái khóa bảng lương | |

### 2. BANG_LUONG_CHI_TIET - Chi Tiết Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã chi tiết | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| loai | varchar(50) | Loại khoản chi | |
| ten_khoan | varchar(255) | Tên khoản chi | |
| so_tien | decimal(10,2) | Số tiền | |
| ghi_chu | varchar(255) | Ghi chú | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 3. BANG_LUONG_KHAUTRU - Khấu Trừ Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã khấu trừ | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| loai_khautru | varchar(100) | Loại khấu trừ | |
| mo_ta | text | Mô tả lý do khấu trừ | |
| so_tien | decimal(10,2) | Số tiền khấu trừ | |

### 4. BANG_LUONG_LICH_SU - Lịch Sử Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch sử | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| hanh_dong | varchar(100) | Hành động (sửa, duyệt, từ chối) | |
| gia_tri_cu | text | Giá trị cũ | |
| gia_tri_moi | text | Giá trị mới | |
| nguoi_thuc_hien | int(11) | Người thực hiện | FK → taikhoan.id |
| ngay_thay_doi | datetime | Ngày thay đổi | |
| ghi_chu | text | Ghi chú | |

### 5. BANG_LUONG_PHUCAP - Phụ Cấp Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã phụ cấp | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| loai_phucap | varchar(100) | Loại phụ cấp | |
| so_tien | decimal(10,2) | Số tiền phụ cấp | |

### 6. BINHLUAN - Bình Luận Phim

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã bình luận | PRIMARY KEY, AUTO_INCREMENT |
| id_phim | int(11) | Mã phim | FK → phim.id |
| id_user | int(11) | Mã người bình luận | FK → taikhoan.id |
| noidung | text | Nội dung bình luận | |
| ngaybinhluan | datetime | Ngày bình luận | DEFAULT CURRENT_TIMESTAMP |

### 7. CAU_HINH_LUONG_NV - Cấu Hình Lương Nhân Viên

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã cấu hình | PRIMARY KEY, AUTO_INCREMENT |
| id_nv | int(11) | Mã nhân viên | FK → taikhoan.id ON DELETE CASCADE |
| id_loai_luong | int(11) | Loại bảng lương | FK → loai_luong.id |
| luong_co_ban | decimal(12,2) | Lương cơ bản | |
| he_so_luong | decimal(4,2) | Hệ số nhân lương | |

### 8. CHAM_CONG - Chấm Công/Điểm Danh

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã chấm công | PRIMARY KEY, AUTO_INCREMENT |
| id_nv | int(11) | Mã nhân viên | |
| id_rap | int(11) | Mã rạp | |
| ngay_cham | date | Ngày chấm công | |
| gio_vao | time | Giờ vào | |
| gio_ra | time | Giờ ra | |
| trang_thai | enum | Trạng thái (co_mat, vang, tre) | |
| latitude | decimal(10,8) | Tọa độ vĩ độ | |
| longitude | decimal(11,8) | Tọa độ kinh độ | |
| location_accuracy | float | Độ chính xác vị trí (mét) | |
| fingerprint_vao | longtext | Dữ liệu vân tay vào (JSON) | |
| fingerprint_ra | longtext | Dữ liệu vân tay ra (JSON) | |

### 9. CHI_TIET_COMBO - Chi Tiết Combo

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã chi tiết combo | PRIMARY KEY, AUTO_INCREMENT |
| id_combo | int(11) | Mã combo | FK → combo_do_an.id |
| ten_mon | varchar(255) | Tên món ăn | |
| so_luong | int(11) | Số lượng | |
| don_gia | decimal(10,2) | Đơn giá | |

### 10. CHI_TIET_KHAU_TRU_THANG - Chi Tiết Khấu Trừ Hàng Tháng

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã chi tiết | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| id_loai_khau_tru | int(11) | Loại khấu trừ | FK → loai_khau_tru.id |
| so_lan | int(11) | Số lần | |
| so_gio | decimal(10,2) | Số giờ | |
| so_tien | decimal(10,2) | Số tiền | |
| ghi_chu | text | Ghi chú | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 11. CHI_TIET_PHU_CAP_THANG - Chi Tiết Phụ Cấp Hàng Tháng

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã chi tiết | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| id_loai_phu_cap | int(11) | Loại phụ cấp | FK → loai_phu_cap.id |
| so_lan | int(11) | Số lần | |
| so_tien | decimal(10,2) | Số tiền | |

### 12. COMBO_DO_AN - Combo Ăn Uống

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã combo | PRIMARY KEY, AUTO_INCREMENT |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id ON DELETE SET NULL |
| ten_combo | varchar(255) | Tên combo | NOT NULL |
| mo_ta | text | Mô tả | |
| gia | decimal(10,2) | Giá combo | |
| hinh_anh | varchar(255) | Hình ảnh | |
| trang_thai | tinyint | Trạng thái (0=không dùng, 1=dùng) | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 13. CONG_THEM_GIO - Công Thêm Giờ

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã công thêm | PRIMARY KEY, AUTO_INCREMENT |
| id_nv | int(11) | Mã nhân viên | FK → taikhoan.id ON DELETE CASCADE |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id ON DELETE CASCADE |
| ngay | date | Ngày làm thêm | |
| so_gio | decimal(10,2) | Số giờ thêm | |
| ghi_chu | text | Ghi chú | |

### 14. DON_NGHI_PHEP - Đơn Nghỉ Phép

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã đơn | PRIMARY KEY, AUTO_INCREMENT |
| id_nhan_vien | int(11) | Mã nhân viên | FK → taikhoan.id |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id |
| tu_ngay | date | Từ ngày | |
| den_ngay | date | Đến ngày | |
| ly_do | text | Lý do nghỉ | |
| trang_thai | enum | Trạng thái (cho_duyet, duyet, tu_choi) | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 15. HOA_DON - Hóa Đơn Thanh Toán

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã hóa đơn | PRIMARY KEY, AUTO_INCREMENT |
| ngay_tt | datetime | Ngày thanh toán | |
| trang_thai | enum | Trạng thái (pending, success, cancelled) | |
| thanh_tien | decimal(10,2) | Thành tiền | |
| id_khuyen_mai | int(11) | Mã khuyến mại (nếu có) | |
| tien_giam | decimal(10,2) | Tiền giảm | |
| thanh_toan_cuoi | decimal(10,2) | Thành tiền sau giảm | |

### 16. KHUNG_GIO_CHIEU - Khung Giờ Chiếu

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã khung giờ | PRIMARY KEY, AUTO_INCREMENT |
| id_lich_chieu | int(11) | Mã lịch chiếu | FK → lichchieu.id |
| id_phong | int(11) | Mã phòng chiếu | FK → phongchieu.id |
| thoi_gian_chieu | time | Thời gian chiếu | |

### 17. KHUYEN_MAI - Khuyến Mại/Khuyến Mãi

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã khuyến mại | PRIMARY KEY, AUTO_INCREMENT |
| ten_khuyen_mai | varchar(255) | Tên khuyến mại | |
| ma_khuyen_mai | varchar(50) | Mã code | UNIQUE |
| mo_ta | text | Mô tả | |
| phan_tram_giam | decimal(5,2) | Phần trăm giảm | |
| gia_tri_giam | decimal(10,2) | Giá trị giảm (VNĐ) | |
| loai_giam | enum | Loại giảm (phan_tram, tien_mat) | |
| ngay_bat_dau | date | Ngày bắt đầu | |
| ngay_ket_thuc | date | Ngày kết thúc | |
| dieu_kien_ap_dung | text | Điều kiện áp dụng | |
| trang_thai | tinyint | Trạng thái (0=tắt, 1=bật) | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id ON DELETE CASCADE |

### 18. LICHCHIEU - Lịch Chiếu Phim

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch chiếu | PRIMARY KEY, AUTO_INCREMENT |
| ma_ke_hoach | varchar(100) | Mã kế hoạch | |
| id_phim | int(11) | Mã phim | FK → phim.id |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id |
| ngay_chieu | date | Ngày chiếu | |
| trang_thai_duyet | enum | Trạng thái phê duyệt (cho_duyet, da_duyet, tu_choi) | |
| ghi_chu | text | Ghi chú | |
| nguoi_tao | int(11) | Người tạo | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |
| ngay_gui | datetime | Ngày gửi phê duyệt | |

### 19. LICH_LAM_VIEC - Lịch Làm Việc

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch làm | PRIMARY KEY, AUTO_INCREMENT |
| id_nhan_vien | int(11) | Mã nhân viên | FK → taikhoan.id |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id |
| ngay | date | Ngày làm | |
| gio_bat_dau | time | Giờ bắt đầu | |
| gio_ket_thuc | time | Giờ kết thúc | |
| ca_lam | varchar(50) | Ca làm (Sáng, Chiều, Tối) | |
| ghi_chu | varchar(255) | Ghi chú | |
| trang_thai | enum | Trạng thái (lich, doi, huy) | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 20. LICH_SU_DIEM - Lịch Sử Điểm Tích Lũy

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch sử | PRIMARY KEY, AUTO_INCREMENT |
| id_tk | int(11) | Mã tài khoản | FK → taikhoan.id ON DELETE CASCADE |
| loai_giao_dich | enum | Loại (cong, tru, huy) | |
| so_diem | int(11) | Số điểm | |
| ly_do | varchar(500) | Lý do | |
| id_ve | int(11) | Mã vé (nếu có) | FK → ve.id ON DELETE SET NULL |
| id_hoa_don | int(11) | Mã hóa đơn (nếu có) | FK → hoa_don.id ON DELETE SET NULL |
| nguoi_thuc_hien | varchar(100) | Người thực hiện (system/admin/user) | |
| ngay_tao | datetime | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 21. LICH_SU_PHE_DUYET_BANGLUONG - Lịch Sử Phê Duyệt Bảng Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch sử | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| id_user_duyet | int(11) | Người phê duyệt | FK → taikhoan.id |
| y_kien | text | Ý kiến phê duyệt | |
| ngay_phe_duyet | datetime | Ngày phê duyệt | |

### 22. LICH_SU_THANH_TOAN - Lịch Sử Thanh Toán Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch sử | PRIMARY KEY, AUTO_INCREMENT |
| id_nv | int(11) | Mã nhân viên | FK → nhan_vien_rap.id ON DELETE CASCADE |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| thang | varchar(7) | Tháng (YYYY-MM) | |
| receipt_id | varchar(100) | Mã chứng chỉ thanh toán | |
| so_tien | decimal(12,2) | Số tiền thanh toán | |
| phuong_thuc | varchar(50) | Phương thức thanh toán | |
| ngay_thanh_toan | datetime | Ngày thanh toán | |

### 23. LICH_SU_THAY_DOI_BANGLUONG - Lịch Sử Thay Đổi Bảng Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch sử | PRIMARY KEY, AUTO_INCREMENT |
| id_bang_luong | int(11) | Mã bảng lương | FK → bang_luong.id ON DELETE CASCADE |
| id_user_thay_doi | int(11) | Người thay đổi | FK → taikhoan.id |
| loai_thay_doi | varchar(100) | Loại thay đổi (sửa, duyệt, từ chối) | |
| gia_tri_cu | text | Giá trị cũ | |
| gia_tri_moi | text | Giá trị mới | |
| ngay_thay_doi | datetime | Ngày thay đổi | |
| ly_do | text | Lý do | |

### 24. LICH_SU_XEM_PHIM - Lịch Sử Xem Phim

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã lịch sử | PRIMARY KEY, AUTO_INCREMENT |
| id_user | int(11) | Mã người dùng | FK → taikhoan.id |
| id_phim | int(11) | Mã phim | FK → phim.id |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id |
| ngay_xem | datetime | Ngày xem | |
| danh_gia | tinyint | Đánh giá (1-5 sao) | |
| binh_luan | text | Bình luận | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 25. LOAIPHIM - Thể Loại Phim

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã loại | PRIMARY KEY, AUTO_INCREMENT |
| name | varchar(50) | Tên loại phim | NOT NULL |

### 26. LOAI_KHAU_TRU - Loại Khấu Trừ

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã loại | PRIMARY KEY, AUTO_INCREMENT |
| ten_khau_tru | varchar(255) | Tên loại khấu trừ | |
| mo_ta | text | Mô tả | |

### 27. LOAI_LUONG - Loại Bảng Lương

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã loại | PRIMARY KEY, AUTO_INCREMENT |
| ten_loai | varchar(255) | Tên loại lương | UNIQUE |
| mo_ta | text | Mô tả | |

### 28. LOAI_PHU_CAP - Loại Phụ Cấp

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã loại | PRIMARY KEY, AUTO_INCREMENT |
| ten_phu_cap | varchar(255) | Tên loại phụ cấp | |
| mo_ta | text | Mô tả | |

### 29. NGAY_NGHI_PHEP - Ngày Nghỉ Phép

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã ngày nghỉ | PRIMARY KEY, AUTO_INCREMENT |
| id_nv | int(11) | Mã nhân viên | FK → taikhoan.id ON DELETE CASCADE |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id ON DELETE CASCADE |
| ngay_bat_dau | date | Ngày bắt đầu | |
| ngay_ket_thuc | date | Ngày kết thúc | |
| loai | enum | Loại nghỉ (phep_nam, phep_khong_luong, nghi_tat_nhat) | |
| so_ngay | decimal(5,2) | Số ngày nghỉ | |
| trang_thai | enum | Trạng thái (cho_duyet, duyet, tu_choi) | |
| ly_do | text | Lý do | |
| nguoi_duyet | int(11) | Người phê duyệt | FK → taikhoan.id |
| ngay_duyet | datetime | Ngày duyệt | |
| ngay_tao | datetime | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 30. NHAN_VIEN_RAP - Nhân Viên Rạp

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã nhân viên | PRIMARY KEY, AUTO_INCREMENT |
| id_tai_khoan | int(11) | Mã tài khoản | FK → taikhoan.id |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id |
| chuc_vu | varchar(100) | Chức vụ | NOT NULL |
| ngay_bat_dau | date | Ngày bắt đầu làm việc | |
| luong_co_ban | decimal(10,2) | Lương cơ bản | DEFAULT 0.00 |
| trang_thai | tinyint | Trạng thái (0=nghỉ, 1=đang làm) | DEFAULT 1 |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 31. PHIM - Thông Tin Phim

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã phim | PRIMARY KEY, AUTO_INCREMENT |
| tieu_de | varchar(255) | Tiêu đề phim | NOT NULL |
| daodien | varchar(255) | Tên đạo diễn | |
| dienvien | varchar(255) | Tên diễn viên | |
| img | varchar(255) | Hình ảnh poster | |
| mo_ta | text | Mô tả nội dung | |
| date_phat_hanh | date | Ngày phát hành | |
| thoi_luong_phim | int(11) | Thời lượng (phút) | |
| id_loai | int(11) | Mã loại phim | FK → loaiphim.id |
| quoc_gia | varchar(255) | Quốc gia sản xuất | |
| gia_han_tuoi | int(11) | Giới hạn tuổi | |
| link_trailer | varchar(5000) | Link trailer | |
| trang_thai_duyet | enum | Trạng thái phê duyệt (cho_duyet, da_duyet, tu_choi) | |

### 32. PHIM_RAP - Liên Kết Phim-Rạp

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã liên kết | PRIMARY KEY, AUTO_INCREMENT |
| id_phim | int(11) | Mã phim | FK → phim.id, UNIQUE với id_rap |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id, UNIQUE với id_phim |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 33. PHONGCHIEU - Phòng Chiếu

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã phòng | PRIMARY KEY, AUTO_INCREMENT |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id |
| name | varchar(50) | Tên phòng (P101, P102...) | NOT NULL |
| so_ghe | int(11) | Số lượng ghế | DEFAULT 0 |
| loai_phong | enum | Loại phòng (2D, 3D, 4DX, IMAX) | DEFAULT '2D' |
| dien_tich | decimal(10,2) | Diện tích (m²) | DEFAULT 0.00 |
| gia_thuong | decimal(10,2) | Giá ghế thường | DEFAULT 50000.00 |
| gia_trung | decimal(10,2) | Giá ghế trung bình | DEFAULT 70000.00 |
| gia_vip | decimal(10,2) | Giá ghế VIP | DEFAULT 100000.00 |

### 34. PHONG_GHE - Ghế Ngồi Phòng Chiếu

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã ghế | PRIMARY KEY, AUTO_INCREMENT |
| id_phong | int(11) | Mã phòng | FK → phongchieu.id |
| row_label | varchar(4) | Hàng (A, B, C...) | |
| seat_number | int(11) | Số ghế | |
| code | varchar(16) | Mã ghế (A1, B5...) | UNIQUE với id_phong |
| tier | enum | Loại ghế (cheap, middle, expensive) | DEFAULT 'cheap' |
| active | tinyint | Trạng thái (0=tắt, 1=bật) | DEFAULT 1 |

### 35. QUY_TAC_TICH_DIEM - Quy Tắc Tích Điểm

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã quy tắc | PRIMARY KEY, AUTO_INCREMENT |
| ten_quy_tac | varchar(255) | Tên quy tắc | |
| loai | enum | Loại (dat_ve, combo, thuong, su_kien, sinh_nhat) | |
| ti_le_quy_doi | decimal(5,2) | Tỷ lệ quy đổi (VD: 0.01 = 1 điểm/100đ) | |
| diem_co_dinh | int(11) | Điểm cố định | |
| ngay_bat_dau | date | Ngày bắt đầu | |
| ngay_ket_thuc | date | Ngày kết thúc | |
| trang_thai | tinyint | Trạng thái (0=tắt, 1=bật) | DEFAULT 1 |
| created_at | datetime | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 36. RAP_CHIEU - Thông Tin Rạp Chiếu

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã rạp | PRIMARY KEY, AUTO_INCREMENT |
| ten_rap | varchar(255) | Tên rạp | NOT NULL |
| dia_chi | text | Địa chỉ | NOT NULL |
| so_dien_thoai | varchar(15) | Số điện thoại | NOT NULL |
| email | varchar(255) | Email | NOT NULL |
| trang_thai | tinyint | Trạng thái (0=đóng, 1=hoạt động) | DEFAULT 1 |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |
| mo_ta | text | Mô tả | |
| logo | varchar(255) | Logo rạp | |

### 37. TAIKHOAN - Tài Khoản Người Dùng

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã tài khoản | PRIMARY KEY, AUTO_INCREMENT |
| name | varchar(255) | Họ tên | NOT NULL |
| user | varchar(255) | Tên đăng nhập | NOT NULL |
| pass | varchar(255) | Mật khẩu (hash) | NOT NULL |
| email | varchar(255) | Email | NOT NULL |
| phone | varchar(10) | Số điện thoại | |
| dia_chi | varchar(255) | Địa chỉ | |
| vai_tro | int(1) | Vai trò (0=khách, 1=nhân viên, 2=admin, 3=quản lý rạp, 4=quản lý cụm) | |
| id_rap | int(11) | Mã rạp (nếu là nhân viên) | |
| img | varchar(255) | Ảnh đại diện | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |
| luong_co_ban | decimal(12,2) | Lương cơ bản/giờ | DEFAULT 30000.00 |
| phu_cap_co_dinh | decimal(12,2) | Phụ cấp cố định | DEFAULT 0.00 |
| he_so_luong | decimal(4,2) | Hệ số nhân lương | DEFAULT 1.00 |
| diem_tich_luy | int(11) | Điểm tích lũy hiện tại | DEFAULT 0 |
| tong_diem_tich_luy | int(11) | Tổng điểm đã tích lũy | DEFAULT 0 |
| hang_thanh_vien | enum | Hạng thành viên (dong, bac, vang, kim_cuong) | DEFAULT 'dong' |
| ngay_cap_nhat_diem | datetime | Ngày cập nhật điểm | DEFAULT CURRENT_TIMESTAMP |
| face_template | longtext | Template nhận diện khuôn mặt (JSON) | |
| face_registered_at | datetime | Ngày đăng ký khuôn mặt | |
| id_loai_luong | int(11) | Loại bảng lương | |
| bhxh_dong | decimal(12,2) | BHXH người lao động đóng | DEFAULT 0.00 |
| ngay_bat_dau_lam | date | Ngày bắt đầu làm việc | |
| trang_thai_lam_viec | enum | Trạng thái (dang_lam, nghi_phep, nghi_viec) | DEFAULT 'dang_lam' |

### 38. THANH_TOAN - Thông Tin Thanh Toán

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã thanh toán | PRIMARY KEY, AUTO_INCREMENT |
| id_hoa_don | int(11) | Mã hóa đơn | FK → hoa_don.id |
| phuong_thuc | enum | Phương thức (momo, vnpay, zalopay, bank_transfer, qr_code, cash) | |
| ma_giao_dich | varchar(255) | Mã giao dịch | |
| so_tien | decimal(10,2) | Số tiền thanh toán | NOT NULL |
| trang_thai | enum | Trạng thái (pending, success, failed, cancelled) | DEFAULT 'pending' |
| thong_tin_thanh_toan | text | Thông tin chi tiết thanh toán | |
| ngay_thanh_toan | datetime | Ngày thanh toán | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 39. THIET_BI_PHONG - Thiết Bị Phòng Chiếu

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã thiết bị | PRIMARY KEY, AUTO_INCREMENT |
| id_phong | int(11) | Mã phòng | FK → phongchieu.id |
| ten_thiet_bi | varchar(100) | Tên thiết bị | NOT NULL |
| so_luong | int(11) | Số lượng | DEFAULT 1 |
| tinh_trang | enum | Tình trạng (tot, can_bao_tri, hong) | DEFAULT 'tot' |
| ghi_chu | varchar(255) | Ghi chú | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 40. THONG_TIN_WEBSITE - Thông Tin Website

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã thông tin | PRIMARY KEY, AUTO_INCREMENT |
| ten_website | varchar(255) | Tên website | NOT NULL |
| logo | varchar(255) | Logo website | |
| video_banner | varchar(255) | Video banner | |
| dia_chi | text | Địa chỉ | |
| so_dien_thoai | varchar(15) | Số điện thoại | |
| email | varchar(255) | Email liên hệ | |
| mo_ta | text | Mô tả website | |
| facebook | varchar(255) | Link Facebook | |
| instagram | varchar(255) | Link Instagram | |
| youtube | varchar(255) | Link YouTube | |
| ngay_cap_nhat | timestamp | Ngày cập nhật | DEFAULT CURRENT_TIMESTAMP |

### 41. THUONG_KHEN_THUONG - Thưởng/Khen Thưởng

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã thưởng | PRIMARY KEY, AUTO_INCREMENT |
| id_nv | int(11) | Mã nhân viên | FK → taikhoan.id ON DELETE CASCADE |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id ON DELETE CASCADE |
| ten_thuong | varchar(255) | Tên loại thưởng | |
| so_tien | decimal(10,2) | Số tiền thưởng | |
| ly_do | text | Lý do thưởng | |
| ngay_phat_thuong | date | Ngày phát thưởng | |
| id_user_duyet | int(11) | Người phê duyệt | FK → taikhoan.id |
| ngay_duyet | datetime | Ngày duyệt | |
| trang_thai | enum | Trạng thái (cho_duyet, duyet, tu_choi) | DEFAULT 'cho_duyet' |

### 42. TINTUC - Tin Tức

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã tin tức | PRIMARY KEY, AUTO_INCREMENT |
| tieu_de | varchar(255) | Tiêu đề tin | NOT NULL |
| tom_tat | text | Tóm tắt nội dung | |
| noi_dung | text | Nội dung đầy đủ | |
| hinh_anh | varchar(255) | Hình ảnh | |
| ngay_dang | datetime | Ngày đăng | DEFAULT CURRENT_TIMESTAMP |

### 43. TONG_HOP_LUONG_THANG - Tổng Hợp Lương Tháng

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã tổng hợp | PRIMARY KEY, AUTO_INCREMENT |
| id_rap | int(11) | Mã rạp | FK → rap_chieu.id ON DELETE CASCADE |
| thang | varchar(7) | Tháng (YYYY-MM) | UNIQUE với id_rap |
| so_nv | int(11) | Số nhân viên | |
| tong_gio_cong | decimal(15,2) | Tổng giờ công | |
| tong_luong_co_ban | decimal(15,2) | Tổng lương cơ bản | |
| tong_phu_cap | decimal(15,2) | Tổng phụ cấp | |
| tong_khau_tru | decimal(15,2) | Tổng khấu trừ | |
| tong_thuong | decimal(15,2) | Tổng thưởng | |
| tong_thuc_lanh | decimal(15,2) | Tổng thực lãnh | |
| trang_thai | enum | Trạng thái (dang_tinh, dang_duyet, da_duyet, da_thanh_toan) | |
| ngay_tao | datetime | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |
| ngay_cap_nhat | datetime | Ngày cập nhật | ON UPDATE CURRENT_TIMESTAMP |

### 44. TRA_LOI_BINHLUAN - Trả Lời Bình Luận

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã trả lời | PRIMARY KEY, AUTO_INCREMENT |
| id_binhluan | int(11) | Mã bình luận gốc | FK → binhluan.id ON DELETE CASCADE |
| id_user | int(11) | Mã người trả lời | FK → taikhoan.id |
| noidung | text | Nội dung trả lời | NOT NULL |
| ngay_tao | datetime | Ngày tạo trả lời | DEFAULT CURRENT_TIMESTAMP |

### 45. VE - Vé Chiếu Phim

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã vé | PRIMARY KEY, AUTO_INCREMENT |
| id_phim | int(11) | Mã phim | NOT NULL |
| id_rap | int(11) | Mã rạp | |
| id_thoi_gian_chieu | int(11) | Mã thời gian chiếu | |
| id_ngay_chieu | int(11) | Mã lịch chiếu ngày | |
| id_tk | int(11) | Mã tài khoản người mua | FK → taikhoan.id |
| ghe | varchar(255) | Danh sách ghế (A1,B5...) | NOT NULL |
| combo | text | Combo kèm theo (JSON) | |
| price | varchar(10) | Giá vé | NOT NULL |
| id_hd | int(11) | Mã hóa đơn | |
| trang_thai | tinyint | Trạng thái (0=chưa sử dụng, 1=đã sử dụng) | DEFAULT 0 |
| ngay_dat | datetime | Ngày đặt | NOT NULL |
| ma_ve | varchar(32) | Mã vé (QR code) | UNIQUE |
| check_in_luc | datetime | Thời gian check-in | |
| check_in_boi | int(11) | Người check-in | FK → taikhoan.id |
| tao_boi | int(11) | Người tạo vé | FK → taikhoan.id |

### 46. VOUCHER - Voucher Giảm Giá

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã voucher | PRIMARY KEY, AUTO_INCREMENT |
| ma_voucher | varchar(20) | Mã code voucher | NOT NULL, UNIQUE |
| ten_voucher | varchar(255) | Tên voucher | NOT NULL |
| gia_tri | decimal(10,2) | Giá trị giảm | NOT NULL |
| loai_giam | enum | Loại giảm (phan_tram, tien_mat) | DEFAULT 'tien_mat' |
| dieu_kien_su_dung | text | Điều kiện sử dụng | |
| ngay_het_han | date | Ngày hết hạn | NOT NULL |
| so_luong | int(11) | Số lượng (-1=không giới hạn) | DEFAULT -1 |
| da_su_dung | int(11) | Số lượng đã sử dụng | DEFAULT 0 |
| trang_thai | tinyint | Trạng thái (0=tắt, 1=bật) | DEFAULT 1 |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

### 47. YEU_CAU_VE - Yêu Cầu Vé

| Tên Cột | Kiểu Dữ Liệu | Mô Tả | Rằng Buộc |
|---------|--------------|-------|----------|
| id | int(11) | Mã yêu cầu | PRIMARY KEY, AUTO_INCREMENT |
| id_user | int(11) | Mã người yêu cầu | |
| id_phim | int(11) | Mã phim | |
| id_rap | int(11) | Mã rạp | |
| ngay_muon | date | Ngày muốn xem | |
| so_ve | int(11) | Số vé muốn đặt | |
| trang_thai | enum | Trạng thái (cho_duyet, duyet, tu_choi, da_huy) | DEFAULT 'cho_duyet' |
| ghi_chu | text | Ghi chú | |
| ngay_tao | timestamp | Ngày tạo | DEFAULT CURRENT_TIMESTAMP |

---

## Ghi Chú Quan Trọng

1. **Bảng được loại bỏ** (6 bảng không sử dụng):
   - `ghe_ngoi` - Được thay thế bởi `phong_ghe`
   - `hang_thanh_vien` - Không có logic thực tế
   - `quy_tac_tich_diem` - Chưa được sử dụng hoàn toàn
   - `chat_analytics` - Không hoạt động
   - `chat_history` - Không hoạt động
   - `doi_hoan` - Chưa được implement

2. **Khóa chính**: Tất cả bảng có khóa chính `id` với AUTO_INCREMENT

3. **Khóa ngoại**: Tất cả FK có thiết lập ON DELETE CASCADE hoặc SET NULL phù hợp

4. **Index**: Các bảng quan trọng có index trên cột thường xuyên truy vấn

5. **Mã hóa**: Tất cả bảng dùng UTF8MB4 character set

---

**Phiên bản**: 1.0  
**Ngày cập nhật**: 2025-12-01  
**Cơ sở dữ liệu**: CINEPASS  
**Hệ thống**: Quản lý Rạp Chiếu Phim GALAXY
