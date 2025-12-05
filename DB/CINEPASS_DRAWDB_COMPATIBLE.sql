-- ============================================================================
-- 🗄️ CINEPASS DATABASE - ALL 51 TABLES STRUCTURE (Compatible with DrawDB)
-- ============================================================================
-- Import this file into https://www.drawdb.app/editor
-- This file is compatible with standard SQL syntax
-- ============================================================================

-- CREATE DATABASE cinepass;
-- USE cinepass;

-- ============================================================================
-- 1. PHIM (Movies)
-- ============================================================================
CREATE TABLE phim (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_loai_phim INT,
    ten_phim VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    poster VARCHAR(255),
    danh_gia DECIMAL(3,1),
    nam_phat_hanh INT,
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_loai_phim) REFERENCES loaiphim(id)
);

-- ============================================================================
-- 2. LOAIPHIM (Movie Genres)
-- ============================================================================
CREATE TABLE loaiphim (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================
-- 3. RAP_CHIEU (Cinema Chains/Branches)
-- ============================================================================
CREATE TABLE rap_chieu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ten_rap VARCHAR(255) NOT NULL,
    dia_chi VARCHAR(255),
    so_dien_thoai VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================
-- 4. PHONGCHIEU (Movie Theaters/Rooms)
-- ============================================================================
CREATE TABLE phongchieu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rap INT NOT NULL,
    ten_phong VARCHAR(100),
    so_ghe INT,
    dien_tich DECIMAL(8,2),
    gia_thuong DECIMAL(10,2),
    gia_trung DECIMAL(10,2),
    gia_vip DECIMAL(10,2),
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id)
);

-- ============================================================================
-- 5. PHONG_GHE (Seats)
-- ============================================================================
CREATE TABLE phong_ghe (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_phong INT NOT NULL,
    so_hang INT,
    so_cot INT,
    loai_ghe VARCHAR(50),
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_phong) REFERENCES phongchieu(id)
);

-- ============================================================================
-- 6. KHUNG_GIO_CHIEU (Showtime Slots)
-- ============================================================================
CREATE TABLE khung_gio_chieu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_lich_chieu INT NOT NULL,
    id_phong INT NOT NULL,
    thoi_gian_chieu TIME NOT NULL,
    FOREIGN KEY (id_lich_chieu) REFERENCES lichchieu(id),
    FOREIGN KEY (id_phong) REFERENCES phongchieu(id)
);

-- ============================================================================
-- 7. LICHCHIEU (Movie Showtimes)
-- ============================================================================
CREATE TABLE lichchieu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_phim INT NOT NULL,
    id_rap INT NOT NULL,
    ngay_chieu DATE,
    gia_ban_ve DECIMAL(10,2),
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_phim) REFERENCES phim(id),
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id)
);

-- ============================================================================
-- 8. VE (Tickets)
-- ============================================================================
CREATE TABLE ve (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_lichchieu INT NOT NULL,
    id_phong_ghe INT NOT NULL,
    id_user INT,
    so_luong INT,
    gia_ve DECIMAL(10,2),
    trang_thai VARCHAR(50),
    ma_qr VARCHAR(255),
    tao_boi INT,
    check_in_boi INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_lichchieu) REFERENCES lichchieu(id),
    FOREIGN KEY (id_phong_ghe) REFERENCES phong_ghe(id),
    FOREIGN KEY (id_user) REFERENCES taikhoan(id),
    FOREIGN KEY (tao_boi) REFERENCES taikhoan(id),
    FOREIGN KEY (check_in_boi) REFERENCES taikhoan(id)
);

-- ============================================================================
-- 9. THANH_TOAN (Payments)
-- ============================================================================
CREATE TABLE thanh_toan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_ve INT NOT NULL,
    so_tien DECIMAL(15,2),
    phuong_thuc VARCHAR(50),
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ve) REFERENCES ve(id)
);

-- ============================================================================
-- 10. TAIKHOAN (User Accounts - Multiple Roles)
-- ============================================================================
CREATE TABLE taikhoan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user VARCHAR(100) UNIQUE NOT NULL,
    pass VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    email VARCHAR(100),
    phone VARCHAR(20),
    vai_tro INT DEFAULT 0,
    id_rap INT,
    diem_tich_luy INT DEFAULT 0,
    hang_thanh_vien VARCHAR(50),
    face_template LONGTEXT,
    phu_cap_co_dinh DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id)
);

-- ============================================================================
-- 11. CHAM_CONG (Attendance Records - Face Recognition)
-- ============================================================================
CREATE TABLE cham_cong (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_nv INT NOT NULL,
    id_rap INT NOT NULL,
    ngay DATE,
    gio_vao TIME,
    gio_ra TIME,
    fingerprint DECIMAL(5,2),
    anh_vao LONGTEXT,
    anh_ra LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nv) REFERENCES taikhoan(id),
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id)
);

-- ============================================================================
-- 12. LICH_LAM_VIEC (Employee Work Schedules)
-- ============================================================================
CREATE TABLE lich_lam_viec (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_nhan_vien INT NOT NULL,
    id_rap INT NOT NULL,
    ngay_lam DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nhan_vien) REFERENCES taikhoan(id),
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id)
);

-- ============================================================================
-- 13. BANG_LUONG (Salary Records)
-- ============================================================================
CREATE TABLE bang_luong (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_nv INT NOT NULL,
    id_rap INT NOT NULL,
    thang VARCHAR(10),
    so_gio INT,
    luong_theo_gio DECIMAL(15,2),
    phu_cap DECIMAL(15,2),
    khau_tru DECIMAL(15,2),
    thuong DECIMAL(15,2),
    tong_luong DECIMAL(15,2),
    trang_thai VARCHAR(50),
    trang_thai_thanh_toan VARCHAR(50),
    ngay_thanh_toan DATETIME,
    id_nguoi_thanh_toan INT,
    nguoi_duyet INT,
    ngay_duyet DATETIME,
    id_loai_luong INT,
    bhxh DECIMAL(15,2),
    thue_thu_nhap DECIMAL(15,2),
    tong_khau_tru DECIMAL(15,2),
    trang_thai_khoa VARCHAR(10),
    ly_do_thay_doi TEXT,
    ghi_chu_thanh_toan TEXT,
    ghi_chu TEXT,
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nv) REFERENCES taikhoan(id),
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id),
    FOREIGN KEY (id_nguoi_thanh_toan) REFERENCES taikhoan(id),
    FOREIGN KEY (nguoi_duyet) REFERENCES taikhoan(id),
    FOREIGN KEY (id_loai_luong) REFERENCES loai_luong(id)
);

-- ============================================================================
-- 14. BANG_LUONG_CHI_TIET (Salary Line Items)
-- ============================================================================
CREATE TABLE bang_luong_chi_tiet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_bang_luong INT NOT NULL,
    loai VARCHAR(50),
    ten_khoan VARCHAR(100),
    so_tien DECIMAL(15,2),
    ghi_chu TEXT,
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_bang_luong) REFERENCES bang_luong(id) ON DELETE CASCADE
);



-- ============================================================================
-- 17. BANG_LUONG_LICH_SU (Salary Change History)
-- ============================================================================
CREATE TABLE bang_luong_lich_su (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_bang_luong INT NOT NULL,
    nguoi_thuc_hien INT,
    mo_ta TEXT,
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_bang_luong) REFERENCES bang_luong(id) ON DELETE CASCADE,
    FOREIGN KEY (nguoi_thuc_hien) REFERENCES taikhoan(id)
);





-- 26. NGAY_NGHI_PHEP (Holiday Calendar)
-- ============================================================================
CREATE TABLE ngay_nghi_phep (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_nv INT NOT NULL,
    id_rap INT NOT NULL,
    ngay_nghi DATE,
    loai_nghi VARCHAR(50),
    ghi_chu TEXT,
    nguoi_duyet INT,
    ngay_duyet DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nv) REFERENCES taikhoan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id) ON DELETE CASCADE,
    FOREIGN KEY (nguoi_duyet) REFERENCES taikhoan(id)
);

-- ============================================================================
-- 27. NHAN_VIEN_RAP (Employee-Cinema Assignment Details)
-- ============================================================================
CREATE TABLE nhan_vien_rap (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_nv INT NOT NULL,
    id_rap INT NOT NULL,
    chuc_vu VARCHAR(100),
    ngay_bat_dau DATE,
    ngay_ket_thuc DATE,
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nv) REFERENCES taikhoan(id),
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id)
);


-- ============================================================================
-- 29. TONG_HOP_LUONG_THANG (Monthly Salary Summary)
-- ============================================================================
CREATE TABLE tong_hop_luong_thang (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rap INT NOT NULL,
    thang VARCHAR(10),
    tong_nhan_vien INT,
    tong_luong_chi_tra DECIMAL(18,2),
    tong_thue_bhxh DECIMAL(18,2),
    trang_thai VARCHAR(50),
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id) ON DELETE CASCADE
);

-- ============================================================================
-- 30. LICH_SU_THANH_TOAN (Salary Payment History)
-- ============================================================================
CREATE TABLE lich_su_thanh_toan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_nv INT NOT NULL,
    id_bang_luong INT NOT NULL,
    ngay_thanh_toan DATETIME,
    so_tien_da_thanh_toan DECIMAL(15,2),
    phuong_thuc_thanh_toan VARCHAR(50),
    ghi_chu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nv) REFERENCES nhan_vien_rap(id) ON DELETE CASCADE,
    FOREIGN KEY (id_bang_luong) REFERENCES bang_luong(id) ON DELETE CASCADE
);




-- ============================================================================
-- 33. COMBO_DO_AN (Food Combos)
-- ============================================================================
CREATE TABLE combo_do_an (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rap INT,
    ten_combo VARCHAR(255),
    gia_ban DECIMAL(10,2),
    mo_ta TEXT,
    anh_combo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id) ON DELETE SET NULL
);

-- ============================================================================
-- 34. CHI_TIET_COMBO (Combo Line Items)
-- ============================================================================
CREATE TABLE chi_tiet_combo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_combo INT NOT NULL,
    ten_item VARCHAR(255),
    so_luong INT,
    mo_ta TEXT,
    FOREIGN KEY (id_combo) REFERENCES combo_do_an(id)
);

-- ============================================================================
-- 35. KHUYEN_MAI (Promotions)
-- ============================================================================
CREATE TABLE khuyen_mai (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rap INT NOT NULL,
    ten_khuyen_mai VARCHAR(255),
    discount_value DECIMAL(10,2),
    discount_type VARCHAR(50),
    ngay_bat_dau DATE,
    ngay_ket_thuc DATE,
    dieu_kien TEXT,
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id) ON DELETE CASCADE
);

-- ============================================================================
-- 36. PHIM_RAP (Movie-Cinema Assignments)
-- ============================================================================
CREATE TABLE phim_rap (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_phim INT NOT NULL,
    id_rap INT NOT NULL,
    ngay_bat_dau DATE,
    ngay_ket_thuc DATE,
    trang_thai VARCHAR(50),
    FOREIGN KEY (id_phim) REFERENCES phim(id),
    FOREIGN KEY (id_rap) REFERENCES rap_chieu(id)
);

-- ============================================================================
-- 37. BINHLUAN (Comments)
-- ============================================================================
CREATE TABLE binhluan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_phim INT NOT NULL,
    id_user INT NOT NULL,
    tieu_de VARCHAR(255),
    noi_dung TEXT,
    danh_gia INT,
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_phim) REFERENCES phim(id),
    FOREIGN KEY (id_user) REFERENCES taikhoan(id)
);

-- ============================================================================
-- 38. TRA_LOI_BINHLUAN (Comment Replies)
-- ============================================================================
CREATE TABLE tra_loi_binhluan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_binhluan INT NOT NULL,
    noi_dung TEXT,
    id_user INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_binhluan) REFERENCES binhluan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES taikhoan(id)
);

-- ===========================================================================
-- ============================================================================
-- 40. THONG_TIN_WEBSITE (Website Configuration)
-- ============================================================================
CREATE TABLE thong_tin_website (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ten_website VARCHAR(255),
    logo VARCHAR(255),
    video_banner VARCHAR(255),
    dia_chi VARCHAR(255),
    so_dien_thoai VARCHAR(20),
    email VARCHAR(100),
    mo_ta TEXT,
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    youtube VARCHAR(255),
    ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================
-- 41. THIET_BI_PHONG (Room Equipment)
-- ============================================================================
CREATE TABLE thiet_bi_phong (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_phong INT NOT NULL,
    ten_thiet_bi VARCHAR(255),
    mo_ta TEXT,
    trang_thai VARCHAR(50),
    FOREIGN KEY (id_phong) REFERENCES phongchieu(id)
);

-- ============================================================================
-- 42. HANG_THANH_VIEN (Member Tiers/Levels)
-- ============================================================================
CREATE TABLE hang_thanh_vien (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ten_hang VARCHAR(50),
    diem_toi_thieu INT,
    phan_tram_giam DECIMAL(5,2),
    mo_ta TEXT
);


-- ============================================================================
-- 44. LICH_SU_DIEM (Points History)
-- ============================================================================
CREATE TABLE lich_su_diem (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_tk INT NOT NULL,
    id_ve INT,
    id_hoa_don INT,
    diem_tich_luy INT,
    diem_sau_tich_luy INT,
    mo_ta TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tk) REFERENCES taikhoan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ve) REFERENCES ve(id) ON DELETE SET NULL,
    FOREIGN KEY (id_hoa_don) REFERENCES hoa_don(id) ON DELETE SET NULL
);

-- ============================================================================
-- 45. HOA_DON (Invoices)
-- ============================================================================
CREATE TABLE hoa_don (
    id INT PRIMARY KEY AUTO_INCREMENT,
    so_hoa_don VARCHAR(50),
    id_tk INT,
    ngay_phat_hanh DATE,
    tong_tien DECIMAL(15,2),
    trang_thai VARCHAR(50),
    anh_hoa_don_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tk) REFERENCES taikhoan(id)
);

-- ============================================================================
-- 46. VOUCHER (Discount Vouchers)
-- ============================================================================
CREATE TABLE voucher (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ma_voucher VARCHAR(50) UNIQUE,
    ten_voucher VARCHAR(255),
    discount_value DECIMAL(10,2),
    discount_type VARCHAR(50),
    ngay_bat_dau DATE,
    ngay_ket_thuc DATE,
    so_luong_tong INT,
    so_luong_da_su_dung INT,
    trang_thai VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- ============================================================================
-- 49. LICH_SU_XEM_PHIM (Movie Viewing History)
-- ============================================================================
CREATE TABLE lich_su_xem_phim (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    id_phim INT NOT NULL,
    ngay_xem DATE,
    xep_hang INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES taikhoan(id),
    FOREIGN KEY (id_phim) REFERENCES phim(id)
);



