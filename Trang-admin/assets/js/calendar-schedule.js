// Enhanced Calendar Assignment System
class CalendarAssignmentManager {
    constructor() {
        this.selectedEmployees = new Set();
        this.selectedTemplate = null;
        this.init();
    }

    init() {
        this.bindEmployeeSelection();
        this.bindTemplateSelection();
        this.bindFormEvents();
        console.log('📅 Calendar Assignment Manager initialized');
    }

    bindEmployeeSelection() {
        const employeeCards = document.querySelectorAll('.employee-card');
        const checkboxes = document.querySelectorAll('.employee-checkbox');

        console.log('🔧 Binding employee selection events...');
        console.log('Employee cards found:', employeeCards.length);
        console.log('Checkboxes found:', checkboxes.length);

        employeeCards.forEach((card, index) => {
            const checkbox = card.querySelector('.employee-checkbox');
            const employeeId = checkbox ? checkbox.value : null;
            
            console.log(`Card ${index + 1}: ID=${employeeId}, Element:`, card);
            
            card.addEventListener('click', (e) => {
                console.log('🖱️ Employee card clicked:', employeeId);
                
                if (!checkbox) {
                    console.error('❌ No checkbox found in card');
                    return;
                }
                
                // Toggle checkbox state
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    this.selectedEmployees.add(employeeId);
                    card.classList.add('selected');
                    console.log('✅ Employee selected:', employeeId);
                } else {
                    this.selectedEmployees.delete(employeeId);
                    card.classList.remove('selected');
                    console.log('❌ Employee deselected:', employeeId);
                }
                
                this.updateSelectedDisplay();
                console.log('👥 Current selected employees:', Array.from(this.selectedEmployees));
            });
        });

        checkboxes.forEach((checkbox, index) => {
            console.log(`Checkbox ${index + 1}: ID=${checkbox.value}`);
            
            checkbox.addEventListener('change', (e) => {
                const employeeId = e.target.value;
                const card = e.target.closest('.employee-card');
                
                console.log('☑️ Checkbox changed:', employeeId, 'checked:', e.target.checked);
                
                if (e.target.checked) {
                    this.selectedEmployees.add(employeeId);
                    card.classList.add('selected');
                } else {
                    this.selectedEmployees.delete(employeeId);
                    card.classList.remove('selected');
                }
                
                this.updateSelectedDisplay();
                console.log('👥 Updated selected employees:', Array.from(this.selectedEmployees));
            });
        });
    }

    bindTemplateSelection() {
        const templates = document.querySelectorAll('.template-card');
        
        templates.forEach(template => {
            template.addEventListener('click', () => {
                // Remove previous selection
                templates.forEach(t => t.classList.remove('selected'));
                
                // Add selection to clicked template
                template.classList.add('selected');
                
                this.selectedTemplate = {
                    shift: template.dataset.shift,
                    start: template.dataset.start,
                    end: template.dataset.end
                };
                
                console.log('⏰ Selected template:', this.selectedTemplate);
            });
        });
    }

    bindFormEvents() {
        const saveBtn = document.getElementById('saveAssignment');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => this.saveAssignment());
        }

        // Close modal on overlay click
        const modal = document.getElementById('assignModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal();
                }
            });
        }

        // ESC key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }

    updateSelectedDisplay() {
        const container = document.getElementById('selectedEmployees');
        if (!container) return;

        container.innerHTML = '';
        
        if (this.selectedEmployees.size === 0) {
            container.innerHTML = '<div style="color: #9ca3af; font-style: italic;">Chưa chọn nhân viên nào</div>';
            return;
        }

        this.selectedEmployees.forEach(employeeId => {
            const card = document.querySelector(`[data-employee-id="${employeeId}"]`);
            if (card) {
                const name = card.querySelector('.employee-name').textContent;
                const tag = document.createElement('div');
                tag.className = 'selected-employee-tag';
                tag.textContent = name;
                container.appendChild(tag);
            }
        });
    }

    openAssignModal(date) {
        if (this.selectedEmployees.size === 0) {
            this.showAlert('⚠️ Vui lòng chọn ít nhất một nhân viên trước khi phân công!', 'warning');
            return;
        }

        const modal = document.getElementById('assignModal');
        const assignDate = document.getElementById('assignDate');
        const displayDate = document.getElementById('displayDate');
        
        if (modal && assignDate && displayDate) {
            assignDate.value = date;
            displayDate.value = date;
            
            // Apply selected template if any
            if (this.selectedTemplate) {
                document.getElementById('startTime').value = this.selectedTemplate.start;
                document.getElementById('endTime').value = this.selectedTemplate.end;
                document.getElementById('shiftType').value = this.selectedTemplate.shift;
            }
            
            this.updateSelectedDisplay();
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            console.log('📝 Opening assignment modal for date:', date);
        }
    }

    closeModal() {
        const modal = document.getElementById('assignModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            this.resetForm();
        }
    }

    resetForm() {
        const form = document.getElementById('assignForm');
        if (form) {
            form.reset();
        }
        
        // Clear template selection
        document.querySelectorAll('.template-card').forEach(t => t.classList.remove('selected'));
        this.selectedTemplate = null;
    }

    async saveAssignment() {
        const form = document.getElementById('assignForm');
        if (!form) return;

        const formData = new FormData(form);
        const date = document.getElementById('assignDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;
        const shiftType = document.getElementById('shiftType').value;
        const note = document.getElementById('shiftNote').value;

        if (!date || !startTime || !endTime) {
            this.showAlert('❌ Vui lòng điền đầy đủ thông tin ngày và giờ!', 'error');
            return;
        }

        if (this.selectedEmployees.size === 0) {
            this.showAlert('❌ Vui lòng chọn ít nhất một nhân viên!', 'error');
            return;
        }

        // Validate time
        if (startTime >= endTime) {
            this.showAlert('❌ Giờ bắt đầu phải nhỏ hơn giờ kết thúc!', 'error');
            return;
        }

        try {
            const saveBtn = document.getElementById('saveAssignment');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = '⏳ Đang lưu...';
            saveBtn.disabled = true;

            // Create assignments for each selected employee
            const assignments = [];
            for (const employeeId of this.selectedEmployees) {
                assignments.push({
                    nhanvien_id: employeeId,
                    ngay: date,
                    gio_bat_dau: startTime,
                    gio_ket_thuc: endTime,
                    ca_lam: shiftType,
                    ghi_chu: note
                });
            }

            console.log('📤 Sending assignment data:', { action: 'create_assignments', assignments });

            const response = await fetch('index.php?act=ql_lichlamviec_calendar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'create_assignments',
                    assignments: assignments
                })
            });

            console.log('📡 Response status:', response.status);
            console.log('📡 Response headers:', response.headers);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const responseText = await response.text();
            console.log('📡 Raw response:', responseText);

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('❌ JSON parse error:', e);
                throw new Error('Invalid JSON response: ' + responseText.substring(0, 100));
            }

            console.log('📋 Parsed result:', result);

            if (result.success) {
                this.showAlert(`✅ Đã phân công thành công cho ${this.selectedEmployees.size} nhân viên!`, 'success');
                this.closeModal();
                
                // Reload page to show updated calendar
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                this.showAlert(`❌ Lỗi: ${result.message || 'Không thể lưu phân công'}`, 'error');
            }

        } catch (error) {
            console.error('❌ Save assignment error:', error);
            console.error('❌ Error details:', {
                name: error.name,
                message: error.message,
                stack: error.stack
            });
            this.showAlert(`❌ Có lỗi xảy ra: ${error.message}`, 'error');
        } finally {
            const saveBtn = document.getElementById('saveAssignment');
            saveBtn.textContent = '💾 Lưu phân công';
            saveBtn.disabled = false;
        }
    }

    showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.dynamic-alert');
        existingAlerts.forEach(alert => alert.remove());

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} dynamic-alert`;
        alert.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease;
        `;

        // Set colors based on type
        const colors = {
            success: { bg: '#10b981', text: 'white' },
            error: { bg: '#ef4444', text: 'white' },
            warning: { bg: '#f59e0b', text: 'white' },
            info: { bg: '#3b82f6', text: 'white' }
        };

        const color = colors[type] || colors.info;
        alert.style.backgroundColor = color.bg;
        alert.style.color = color.text;
        alert.textContent = message;

        document.body.appendChild(alert);

        // Auto remove after 4 seconds
        setTimeout(() => {
            alert.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    }
}

// Global functions for template compatibility
let calendarManager;

function selectAllEmployees() {
    const checkboxes = document.querySelectorAll('.employee-checkbox');
    checkboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            checkbox.click();
        }
    });
}

function clearAllEmployees() {
    const checkboxes = document.querySelectorAll('.employee-checkbox');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            checkbox.click();
        }
    });
}

function openAssignModal(date) {
    if (calendarManager) {
        calendarManager.openAssignModal(date);
    }
}

function closeAssignModal() {
    if (calendarManager) {
        calendarManager.closeModal();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    calendarManager = new CalendarAssignmentManager();
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .calendar-grid .day-cell {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .employee-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .template-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    `;
    document.head.appendChild(style);
    
    console.log('🚀 Enhanced Calendar Assignment System loaded successfully!');
});

// Export for potential external use
window.CalendarAssignmentManager = CalendarAssignmentManager;