-- Update cham_cong table to add photo columns for face detection
-- Run this to update existing database

-- Add photo columns if they don't exist
ALTER TABLE `cham_cong` 
ADD COLUMN `anh_vao` varchar(500) DEFAULT NULL COMMENT 'Đường dẫn ảnh check-in' AFTER `gio_vao`,
ADD COLUMN `anh_ra` varchar(500) DEFAULT NULL COMMENT 'Đường dẫn ảnh check-out' AFTER `gio_ra`;

-- Alternative: If columns already exist, this won't error
-- ALTER TABLE `cham_cong` 
-- MODIFY COLUMN `gio_vao` time DEFAULT NULL,
-- MODIFY COLUMN `gio_ra` time DEFAULT NULL;
