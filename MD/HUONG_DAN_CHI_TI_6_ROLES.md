# 🎯 Phân Tích Kỹ Use Case Diagram - Dễ Vẽ Trên Visual Paradigm 17.1

## 📸 Phân Tích Ảnh Bạn Gửi

Ảnh cho thấy một diagram chuẩn:
```
┌─────────────────────────────────────────────────────────────────────┐
│                     HỆ THỐNG ĐẶT VÉ RẠP CHIẾU PHIM               │
│                                                                     │
│  LEFT ACTORS   │       CENTER PACKAGES & USE CASES       │ RIGHT    │
│  (nhóm trái)   │       (gói 7 packages, 100+ UCs)        │ ACTORS   │
│                │                                         │(nhóm p)  │
│  • Admin       │  ┌─────────────────────────────────┐   │ • Nhân   │
│  • Khách       │  │  QUẢN LÝ COMBO                 │   │   viên   │
│  • Thành viên  │  │  ┌─────────────────────────┐   │   │ • Khách  │
│                │  │  │ UC: Chọn combo         │   │   │   hàng   │
│                │  │  │ UC: Thanh toán combo   │   │   │           │
│                │  │  │ UC: Áp dụng khuyến mãi │  │   │           │
│                │  │  └─────────────────────────┘   │   │           │
│                │  └─────────────────────────────────┘   │           │
│                │                                         │           │
│  ┌──────────┐  │  ┌─────────────────────────────────┐   │           │
│  │  Admin   │──┼─→│  QUẢN LÝ PHIM                  │   │           │
│  └──────────┘  │  │  ┌─────────────────────────┐   │   │           │
│                │  │  │ UC: Thêm phim          │   │   │           │
│                │  │  │ UC: Sửa phim           │   │   │           │
│                │  │  │ UC: Xóa phim           │   │   │           │
│                │  │  └─────────────────────────┘   │   │           │
│                │  └─────────────────────────────────┘   │           │
│                │                                         │           │
│                │  ... (nhiều packages khác)              │           │
│                │                                         │           │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🔍 Chi Tiết 6 Roles (Actors)

### **LEFT SIDE (Trái - 4 Actors)**

#### 1️⃣ **Admin** (vai_tro = 2)
```
Vị trí:     X = 50px,   Y = 100px
Tên:        Admin / Quản trị viên
Icon:       🔐 hoặc hình người
Màu:        #FFB366 (Cam nhạt)
Border:     Màu tối, độ dày 2px

Đặc điểm:   - Ở trên cùng bên trái
            - Có nhiều line kết nối đến nhiều UC
            - Toàn quyền quản lý hệ thống
```

#### 2️⃣ **Khách vãng lai** (vai_tro = -1)
```
Vị trí:     X = 50px,   Y = 300px
Tên:        Khách / Guest
Icon:       👤 hoặc hình người
Màu:        #90EE90 (Xanh nhạt)
Border:     Màu tối, độ dày 2px

Đặc điểm:   - Ở giữa bên trái
            - Ít line kết nối (chỉ chức năng công khai)
            - Không cần đăng nhập
```

#### 3️⃣ **Thành viên** (vai_tro = 0)
```
Vị trí:     X = 50px,   Y = 500px
Tên:        Thành viên / Member
Icon:       ⭐ hoặc hình người
Màu:        #FFD700 (Vàng)
Border:     Màu tối, độ dày 2px

Đặc điểm:   - Ở dưới bên trái
            - Nhiều line kết nối
            - Có tích điểm, quản lý hồ sơ
```

#### 4️⃣ **Nhân viên** (vai_tro = 1)
```
Vị trí:     X = 50px,   Y = 700px
Tên:        Nhân viên / Staff
Icon:       👔 hoặc hình người
Màu:        #FF9999 (Đỏ nhạt)
Border:     Màu tối, độ dày 2px

Đặc điểm:   - Ở cuối bên trái
            - Quản lý check-in, bán vé
```

---

### **RIGHT SIDE (Phải - 2 Actors)**

#### 5️⃣ **Quản lý rạp** (vai_tro = 3)
```
Vị trí:     X = 1200px,  Y = 400px
Tên:        Quản lý rạp / Cinema Manager
Icon:       🎭 hoặc hình người
Màu:        #87CEEB (Xanh da trời)
Border:     Màu tối, độ dày 2px

Đặc điểm:   - Ở giữa bên phải
            - Kết nối đến package "CINEMA_MANAGER"
            - Quản lý lịch chiếu, nhân viên 1 rạp
```

#### 6️⃣ **Quản lý cụm rạp** (vai_tro = 4)
```
Vị trí:     X = 1200px,  Y = 600px
Tên:        Quản lý cụm / Cluster Manager
Icon:       🏢 hoặc hình người
Màu:        #DA70D6 (Tím nhạt)
Border:     Màu tối, độ dày 2px

Đặc điểm:   - Ở dưới bên phải
            - Kết nối đến package "CLUSTER_MANAGER"
            - Quản lý nhiều rạp, chiến lược toàn cụm
```

---

## 📦 7 Packages (Gói Chức Năng) - Ở Giữa

### **Layout Packages**

```
HÀNG 1 (Y = 150px):
┌──────────────────────────────────────────────────────────────────┐
│ PUBLIC (9)  │ GUEST (9)  │ MEMBER (20) │ STAFF (10) │ ADMIN (8) │
└──────────────────────────────────────────────────────────────────┘

HÀNG 2 (Y = 500px):
┌────────────────────────────┬─────────────────────────────┐
│ CINEMA_MANAGER (26 UCs)    │ CLUSTER_MANAGER (24 UCs)   │
└────────────────────────────┴─────────────────────────────┘
```

---

### **Package 1: PUBLIC (9 Use Cases)**
```
Vị trí:        X = 200px,  Y = 150px
Kích thước:    Width = 120px,  Height = 200px
Tên package:   "PUBLIC\n(9 UCs)"
Màu nền:       #E8F8E8 (Xanh nhạt nhất)
Border:        #339933 (Xanh đậm), 2px
Font:          Bold, 11px

Các UC bên trong (vẽ oval nhỏ):
1. Xem danh sách phim
2. Xem chi tiết phim
3. Xem trailer
4. Xem thông tin rạp
5. Xem lịch chiếu
6. Xem khuyến mãi
7. Xem tin tức
8. Liên hệ
9. Chat Bot

Relationship:  Khách + Thành viên → PUBLIC
```

---

### **Package 2: GUEST (9 Use Cases)**
```
Vị trí:        X = 360px,  Y = 150px
Kích thước:    Width = 120px,  Height = 200px
Tên package:   "GUEST\n(9 UCs)"
Màu nền:       #E0F2FE (Xanh lam nhạt)
Border:        #0066CC (Xanh lam), 2px
Font:          Bold, 11px

Các UC bên trong:
1. Đặt vé nhanh
2. Nhập thông tin
3. Chọn phim
4. Chọn rạp & suất
5. Chọn ghế
6. Chọn combo
7. Thanh toán
8. Nhận vé Email
9. Nhận mã QR

Relationship:  Khách → GUEST
Include:       UC1 <<include>> UC2, UC3, UC4, UC5, UC6, UC7
```

---

### **Package 3: MEMBER (20 Use Cases)**
```
Vị trí:        X = 520px,  Y = 150px
Kích thước:    Width = 150px,  Height = 200px
Tên package:   "MEMBER\n(20 UCs)"
Màu nền:       #FFFACD (Vàng rất nhạt)
Border:        #FFD700 (Vàng), 2px
Font:          Bold, 11px

Các UC bên trong (chọn chính):
1. Đăng ký
2. Đăng nhập
3. Đặt vé có tích điểm
4. Tích điểm
5. Đổi điểm thành voucher
6. Áp dụng voucher
7. Xem hạng thành viên
8. Lịch sử điểm
9. Lịch sử giao dịch
10. Danh sách vé
... (10 UC khác)

Relationship:  Thành viên → MEMBER
Include:       UC3 <<include>> UC4, UC5, UC7, UC8, UC9
```

---

### **Package 4: STAFF (10 Use Cases)**
```
Vị trí:        X = 710px,  Y = 150px
Kích thước:    Width = 120px,  Height = 200px
Tên package:   "STAFF\n(10 UCs)"
Màu nền:       #FFE0E0 (Đỏ rất nhạt)
Border:        #CC0000 (Đỏ), 2px
Font:          Bold, 11px

Các UC bên trong:
1. Đăng nhập
2. Bán vé tại quầy
3. Scan QR check-in
4. Check-in thủ công
5. Combo tại quầy
6. Phục vụ combo
7. Chấm công
8. Lịch làm việc
9. Đăng ký nghỉ phép
10. Báo cáo ca

Relationship:  Nhân viên → STAFF
Include:       UC2 <<include>> UC7
              UC3 <<include>> UC4
```

---

### **Package 5: ADMIN (8 Use Cases)**
```
Vị trí:        X = 870px,  Y = 150px
Kích thước:    Width = 120px,  Height = 200px
Tên package:   "ADMIN\n(8 UCs)"
Màu nền:       #FFE8D8 (Cam rất nhạt)
Border:        #FF6600 (Cam), 2px
Font:          Bold, 11px

Các UC bên trong:
1. Quản lý phim
2. Quản lý rạp
3. Quản lý nhân viên
4. Quản lý khuyến mãi
5. Quản lý tài khoản
6. Backup/Restore
7. Xem log hệ thống
8. Cấu hình

Relationship:  Admin → ADMIN
Include:       UC2 <<include>> UC1
              UC3 <<include>> UC4
```

---

### **Package 6: CINEMA_MANAGER (26 Use Cases)**
```
Vị trí:        X = 200px,  Y = 500px
Kích thước:    Width = 350px,  Height = 300px
Tên package:   "CINEMA_MANAGER\n(26 UCs)"
Màu nền:       #E0F8FF (Xanh da trời rất nhạt)
Border:        #0099CC (Xanh da trời), 2px
Font:          Bold, 12px

Các UC bên trong (grid 3 cột × 9 hàng):
Hàng 1: Thêm lịch chiếu, Sửa lịch chiếu, Xóa lịch chiếu
Hàng 2: Quản lý giá vé, Thêm nhân viên, Phân ca làm
Hàng 3: Duyệt nghỉ phép, Quản lý lương, Xem chấm công
Hàng 4: Quản lý phòng, Quản lý ghế, Quản lý máy chiếu
Hàng 5: Báo cáo thiết bị hỏng, Thêm combo, Quản lý tồn kho
Hàng 6: Cập nhật giá combo, Khuyến mãi riêng, Báo cáo doanh thu
Hàng 7: Chi tiết doanh thu vé, Doanh thu combo, Thống kê khách
Hàng 8: Phản hồi khách, Trả lời, Duyệt đổi vé
Hàng 9: Duyệt hoàn vé, Thống kê đổi/hoàn, ...

Relationship:  Quản lý rạp → CINEMA_MANAGER
Include:       Thêm lịch <<include>> Quản lý phòng + Quản lý ghế
              Báo cáo doanh thu <<include>> Chi tiết doanh thu vé + Doanh thu combo
```

---

### **Package 7: CLUSTER_MANAGER (24 Use Cases)**
```
Vị trí:        X = 600px,  Y = 500px
Kích thước:    Width = 400px,  Height = 300px
Tên package:   "CLUSTER_MANAGER\n(24 UCs)"
Màu nền:       #F0E8FF (Tím rất nhạt)
Border:        #6600CC (Tím), 2px
Font:          Bold, 12px

Các UC bên trong (grid 3-4 cột):
Quản lý cụm:     Thêm rạp, Sửa rạp, Xóa rạp, Phân quyền
Nhân viên:       Điều phối nhân viên
Lịch chiếu:      Phân bổ phim, Tối ưu lịch
Giá vé:          Chính sách giá toàn cụm, Quản lý giá cụm
Marketing:       Quản lý khuyến mãi cụm, Chiến dịch marketing
Phân tích:       Doanh thu toàn cụm, So sánh rạp, Báo cáo, Xu hướng, Dự báo
Ngân sách:       Quản lý ngân sách, Phân bổ chi phí, Theo dõi
Hợp tác:         Nhà cung cấp, Hợp đồng phim, Đàm phán
Hỗ trợ:          Hỗ trợ quản lý rạp, Đào tạo nhân viên

Relationship:  Quản lý cụm → CLUSTER_MANAGER
Include:       Phân bổ phim <<include>> Thêm lịch chiếu (từ CINEMA)
```

---

## 🔗 Relationships Chi Tiết

### **Association Lines (Solid)**
```
Vẽ từ Actor tới Package/UC:
- Từ: Actor circle
- Tới: UC ellipse hoặc Package boundary
- Style: Solid line, Black, 1.5-2px
- Arrow: Standard (→)
- Label: None hoặc tên UC

Ví dụ:
Admin ————→ ADMIN package
Khách ————→ PUBLIC package + GUEST package
Thành viên ————→ MEMBER package
Nhân viên ————→ STAFF package
Quản lý rạp ————→ CINEMA_MANAGER package
Quản lý cụm ————→ CLUSTER_MANAGER package
```

---

### **Include Relationships (Dashed Orange)**
```
Vẽ giữa UC hoặc UC-Package:
- Từ: UC gọi (UC cha)
- Tới: UC được gọi (UC con)
- Style: Dashed line, Orange (#FF6600), 2px
- Arrow: Open arrow (△)
- Label: "<<include>>"

Ví dụ:
"Đặt vé nhanh" <<include>> "Chọn phim"
                <<include>> "Chọn rạp & suất"
                <<include>> "Chọn ghế"
                <<include>> "Chọn combo"
                <<include>> "Thanh toán"

"Đặt vé có tích điểm" <<include>> "Tích điểm"
                     <<include>> "Áp dụng voucher"

"Thêm lịch chiếu" <<include>> "Quản lý phòng"
                  <<include>> "Quản lý ghế"
```

---

### **Extend Relationships (Dashed Red)**
```
Vẽ giữa UC:
- Từ: UC mở rộng (UC con)
- Tới: UC được mở rộng (UC cha)
- Style: Dashed line, Red (#FF0000), 2px
- Arrow: Open arrow (△)
- Label: "<<extend>>"
- Điều kiện (optional): [condition]

Ví dụ:
"Áp dụng khuyến mãi" <<extend>> "Thanh toán" [nếu có voucher]
"Phục vụ combo" <<extend>> "Doanh thu combo"
"Duyệt hoàn vé" <<extend>> "Báo cáo doanh thu"
```

---

## 🎨 Bảng Màu Chuẩn

| Phần tử | Màu Nền | Border | Hex Border | Border Size |
|--------|---------|--------|-----------|------------|
| Actor | Theo vai_tro | Saddle Brown | #8B4513 | 2px |
| PUBLIC | #E8F8E8 | Dark Green | #339933 | 2px |
| GUEST | #E0F2FE | Blue | #0066CC | 2px |
| MEMBER | #FFFACD | Gold | #FFD700 | 2px |
| STAFF | #FFE0E0 | Red | #CC0000 | 2px |
| ADMIN | #FFE8D8 | Orange | #FF6600 | 2px |
| CINEMA_MGR | #E0F8FF | Cyan | #0099CC | 2px |
| CLUSTER_MGR | #F0E8FF | Purple | #6600CC | 2px |
| System Boundary | #F0F8FF | Navy | #000080 | 2px |

---

## 📐 Tọa Độ & Kích Thước (VP Units)

### **Actors Coordinates**
```
LEFT SIDE:
Admin          X=50,    Y=100,   Width=80, Height=80
Khách          X=50,    Y=300,   Width=80, Height=80
Thành viên     X=50,    Y=500,   Width=80, Height=80
Nhân viên       X=50,    Y=700,   Width=80, Height=80

RIGHT SIDE:
Quản lý rạp    X=1200,  Y=400,   Width=80, Height=80
Quản lý cụm    X=1200,  Y=600,   Width=80, Height=80
```

### **Packages Coordinates**
```
ROW 1 (Y=150):
PUBLIC              X=200,   Y=150,   W=120,  H=200
GUEST               X=360,   Y=150,   W=120,  H=200
MEMBER              X=520,   Y=150,   W=150,  H=200
STAFF               X=710,   Y=150,   W=120,  H=200
ADMIN               X=870,   Y=150,   W=120,  H=200

ROW 2 (Y=500):
CINEMA_MANAGER      X=200,   Y=500,   W=350,  H=300
CLUSTER_MANAGER     X=600,   Y=500,   W=400,  H=300
```

### **System Boundary**
```
X = 100,   Y = 80,   Width = 1150,  Height = 750
Border: 2px Navy Blue (#000080)
Fill: Light Blue (#F0F8FF) - Semi-transparent
```

---

## 📝 Font & Text Style

```
Package Names:
- Font: Arial
- Size: 11-12px
- Style: Bold
- Align: Center
- Color: #333

Use Case Names:
- Font: Arial
- Size: 9-10px
- Style: Regular
- Align: Center
- Color: #000

Actor Names:
- Font: Arial
- Size: 10px
- Style: Bold
- Align: Center
- Color: White (nếu nền tối) hoặc #000

Relationship Labels:
- Font: Arial
- Size: 8-9px
- Style: Italic
- Color: Theo loại (Orange, Red)
```

---

## 🚀 Hướng Dẫn Vẽ Từng Bước Trên Visual Paradigm 17.1

### **Bước 1: Tạo Diagram**
```
1. File → New → Model → UML
2. Chọn "Use Case Diagram"
3. Canvas size: A2 Landscape (hoặc Custom 1200×800)
4. OK
```

### **Bước 2: Vẽ System Boundary**
```
1. Trên diagram, Insert → Shape → Rectangle
2. Điều chỉnh kích thước: X=100, Y=80, W=1150, H=750
3. Properties:
   - Fill Color: #F0F8FF
   - Border: 2px, Navy Blue (#000080)
   - Add Text: "CinePass Cinema Management System" (top-left)
```

### **Bước 3: Vẽ 6 Actors**
```
1. Insert → Actor (hoặc drag từ Toolbox)
2. Đặt vị trí theo tọa độ ở trên
3. Rename: Chuột phải → Properties → Name
4. Set màu:
   - Chuột phải → Properties → Appearance
   - Background Color: Theo bảng trên
   - Border Color: #8B4513, 2px
5. Thêm text vai_tro dưới tên (optional)
```

### **Bước 4: Vẽ 7 Packages**
```
1. Insert → Package (Diagram → Package)
2. Đặt vị trí & kích thước theo tọa độ
3. Rename theo tên package
4. Set màu:
   - Background: Theo bảng
   - Border: Theo bảng, 2px
5. Thêm text "(X UCs)" dưới tên
```

### **Bước 5: Vẽ Use Cases**
```
1. Inside package, Insert → Use Case
2. Position trong package (grid layout)
3. Rename: Tên UC
4. Set màu:
   - Background: #F0F0F0
   - Border: #333, 1px
5. Font: 9px, Regular

Lặp lại cho tất cả 107 UCs (hoặc 50 cái chính)
```

### **Bước 6: Vẽ Association**
```
1. Insert → Association (từ Toolbox)
2. Click từ Actor → Click đến Package/UC
3. Properties:
   - Line: Solid
   - Color: Black, 2px
   - Arrow: Standard
4. Repeat cho tất cả actors
```

### **Bước 7: Vẽ Include Relationships**
```
1. Insert → Dependency (hoặc generalization)
2. Click từ UC cha → Click đến UC con
3. Properties:
   - Type: Include (từ stereotype)
   - Line: Dashed
   - Color: Orange (#FF6600), 2px
   - Arrow: Open triangle
4. Add label: "<<include>>"
```

### **Bước 8: Vẽ Extend Relationships**
```
1. Insert → Dependency
2. Click từ UC mở rộng → Click đến UC gốc
3. Properties:
   - Type: Extend
   - Line: Dashed
   - Color: Red (#FF0000), 2px
   - Arrow: Open triangle
4. Add label: "<<extend>>"
```

### **Bước 9: Layout & Cleanup**
```
1. View → Zoom Fit (để xem toàn cảnh)
2. Diagram → Arrange → Auto Layout (nếu cần)
3. Kiểm tra:
   - Các line có giao nhau lộn xộn không?
   - Text có bị che phủ không?
   - Spacing có đều không?
4. Điều chỉnh thủ công nếu cần
```

### **Bước 10: Export**
```
1. File → Export
2. Format: PNG / SVG / PDF
3. DPI: 300 (cho chất lượng cao)
4. Save
```

---

## ⚡ Mẹo Vẽ Nhanh

### **Lặp lại đối tượng**
```
1. Vẽ 1 Actor xong
2. Chuột phải → Clone
3. Drag đến vị trí mới
4. Rename & đổi màu
→ Tiết kiệm thời gian!
```

### **Align Objects**
```
1. Chọn nhiều UC: Ctrl+Click
2. Format → Align → Distribute Evenly
3. Spacing sẽ tự động đều!
```

### **Zoom & Pan**
```
View → Zoom: 75% (để thấy toàn cảnh)
       Zoom: 100% (vẽ chi tiết)
       Fit Page (tự động fit)
```

### **Undo/Redo**
```
Ctrl+Z: Undo
Ctrl+Y: Redo
→ Quên gì cũng sửa được!
```

---

## ✅ Checklist Trước Khi Submit

- [ ] System Boundary có viền Navy Blue 2px?
- [ ] 6 Actors đều có tên & màu đúng?
- [ ] 7 Packages có đúng vị trí & màu?
- [ ] Tất cả 107 UCs đều hiển thị (hoặc 50 chính)?
- [ ] Association lines từ Actor đến Package?
- [ ] Include relationships (Dashed Orange)?
- [ ] Extend relationships (Dashed Red)?
- [ ] Text không bị che phủ?
- [ ] Diagram zoom fit trên A4/A3?
- [ ] Export PNG 300 DPI?
- [ ] File VP (.vpp) lưu giữ?

---

## 📊 Tóm Tắt Nhanh

**6 Actors:**
```
LEFT:  Admin, Khách, Thành viên, Nhân viên
RIGHT: Quản lý rạp, Quản lý cụm
```

**7 Packages:**
```
PUBLIC (9) → Khách xem
GUEST (9) → Khách đặt vé nhanh
MEMBER (20) → Thành viên tích điểm
STAFF (10) → Nhân viên bán vé
ADMIN (8) → Admin quản lý
CINEMA_MANAGER (26) → Quản lý 1 rạp
CLUSTER_MANAGER (24) → Quản lý nhiều rạp
```

**107 Use Cases:** Phân bố trong 7 packages

**Relationships:**
- **Association:** Khách/Thành viên → Packages
- **Include:** UC bắt buộc gọi UC khác
- **Extend:** UC mở rộng chức năng UC khác

---

## 🎓 Vậy Là Bạn Có Đủ Info!

**Chuẩn bị:**
1. ✅ Mở Visual Paradigm 17.1
2. ✅ Tạo New Diagram
3. ✅ Follow 10 bước ở trên
4. ✅ Copy tọa độ, màu sắc từ hướng dẫn này
5. ✅ Export PNG/PDF
6. ✅ Nộp!

**Bạn đã có:**
- 📐 Tọa độ chính xác
- 🎨 Bảng màu chuẩn
- 🔗 Relationships rõ ràng
- 📝 Font & style
- ✅ Checklist

**Giờ chỉ cần vẽ thôi! 🚀**

---

*Chúc bạn vẽ thành công! Nếu gặp khó khăn, hãy quay lại tài liệu này!* 🎓✨

