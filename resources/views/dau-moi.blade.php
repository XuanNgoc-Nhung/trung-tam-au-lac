@extends('layouts.app')
@section('content')
<div class="container" style="width: 100% !important;margin:0 !important;padding:0 !important; max-width: 100% !important;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Quản lý đầu mối
                    </h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDauMoiModal">
                        <i class="fas fa-plus me-2"></i>Thêm đầu mối
                    </button>
                </div>
                <div class="card-body">
                    <!-- Alert Messages -->
                    <div id="alertContainer"></div>

                    <!-- Results Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th>Mã đầu mối</th>
                                    <th>Tên đầu mối</th>
                                    <th>Số lượng thí sinh</th>
                                    <th class="text-center">Ngày tạo</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="dauMoiTableBody">
                                @forelse($dauMois as $index => $dauMoi)
                                <tr data-ma-dau-moi="{{ $dauMoi->ma_dau_moi }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $dauMoi->ma_dau_moi }}</td>
                                    <td>{{ $dauMoi->ten_dau_moi }}</td>
                                    <td>{{ $dauMoi->so_luong_thi_sinh }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($dauMoi->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" 
                                                data-ma-dau-moi="{{ $dauMoi->ma_dau_moi }}"
                                                data-ten-dau-moi="{{ $dauMoi->ten_dau_moi }}"
                                                data-so-luong="{{ $dauMoi->so_luong_thi_sinh }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                data-ma-dau-moi="{{ $dauMoi->ma_dau_moi }}"
                                                data-ten-dau-moi="{{ $dauMoi->ten_dau_moi }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> Không có đầu mối nào
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-bar"></i>
                            <strong>Tổng số đầu mối: {{ $dauMois->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Dau Moi Modal -->
<div class="modal fade" id="addDauMoiModal" tabindex="-1" aria-labelledby="addDauMoiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDauMoiModalLabel">
                    <i class="fas fa-plus me-2"></i>Thêm đầu mối mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDauMoiForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ma_dau_moi" class="form-label">Mã đầu mối <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ma_dau_moi" name="ma_dau_moi" required>
                        <div class="invalid-feedback" id="ma_dau_moi_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="ten_dau_moi" class="form-label">Tên đầu mối <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ten_dau_moi" name="ten_dau_moi" required>
                        <div class="invalid-feedback" id="ten_dau_moi_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="so_luong_thi_sinh" class="form-label">Số lượng thí sinh <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="so_luong_thi_sinh" name="so_luong_thi_sinh" min="0" required>
                        <div class="invalid-feedback" id="so_luong_thi_sinh_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Dau Moi Modal -->
<div class="modal fade" id="editDauMoiModal" tabindex="-1" aria-labelledby="editDauMoiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDauMoiModalLabel">
                    <i class="fas fa-edit me-2"></i>Sửa đầu mối
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDauMoiForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_ma_dau_moi_old" name="ma_dau_moi_old">
                    <div class="mb-3">
                        <label for="edit_ma_dau_moi" class="form-label">Mã đầu mối <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_ma_dau_moi" name="ma_dau_moi" required>
                        <div class="invalid-feedback" id="edit_ma_dau_moi_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_ten_dau_moi" class="form-label">Tên đầu mối <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_ten_dau_moi" name="ten_dau_moi" required>
                        <div class="invalid-feedback" id="edit_ten_dau_moi_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_so_luong_thi_sinh" class="form-label">Số lượng thí sinh <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_so_luong_thi_sinh" name="so_luong_thi_sinh" min="0" required>
                        <div class="invalid-feedback" id="edit_so_luong_thi_sinh_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDauMoiModal" tabindex="-1" aria-labelledby="deleteDauMoiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDauMoiModalLabel">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa đầu mối <strong id="deleteDauMoiName"></strong>?</p>
                <p class="text-muted">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Xóa
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .table th {
        background-color: #343a40;
        color: white;
        border-color: #454d55;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .btn {
        margin-right: 5px;
    }

    .alert {
        border-radius: 0.25rem;
    }

    .modal-header {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .modal-header .btn-close {
        filter: invert(1);
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
    }

    .invalid-feedback {
        display: block;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertContainer = document.getElementById('alertContainer');
    let currentDeleteMaDauMoi = null;

    // Show alert function
    function showAlert(message, type = 'success') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        alertContainer.innerHTML = alertHtml;
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    // Clear form validation
    function clearValidation(formId) {
        const form = document.getElementById(formId);
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        const errorDivs = form.querySelectorAll('.invalid-feedback');
        errorDivs.forEach(div => {
            div.textContent = '';
        });
    }

    // Add Dau Moi Form
    document.getElementById('addDauMoiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        clearValidation('addDauMoiForm');

        const formData = new FormData(this);
        
        fetch('{{ route("dau-moi.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                ma_dau_moi: formData.get('ma_dau_moi'),
                ten_dau_moi: formData.get('ten_dau_moi'),
                so_luong_thi_sinh: formData.get('so_luong_thi_sinh')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                document.getElementById('addDauMoiModal').querySelector('.btn-close').click();
                this.reset();
                // Reload page to show new data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = document.getElementById(field);
                        const errorDiv = document.getElementById(field + '_error');
                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.textContent = data.errors[field][0];
                        }
                    });
                } else {
                    showAlert(data.message, 'danger');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi thêm đầu mối', 'danger');
        });
    });

    // Edit Dau Moi
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const maDauMoi = this.getAttribute('data-ma-dau-moi');
            const tenDauMoi = this.getAttribute('data-ten-dau-moi');
            const soLuong = this.getAttribute('data-so-luong');

            document.getElementById('edit_ma_dau_moi_old').value = maDauMoi;
            document.getElementById('edit_ma_dau_moi').value = maDauMoi;
            document.getElementById('edit_ten_dau_moi').value = tenDauMoi;
            document.getElementById('edit_so_luong_thi_sinh').value = soLuong;

            clearValidation('editDauMoiForm');
            
            const editModal = new bootstrap.Modal(document.getElementById('editDauMoiModal'));
            editModal.show();
        });
    });

    // Edit Dau Moi Form
    document.getElementById('editDauMoiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        clearValidation('editDauMoiForm');

        const maDauMoiOld = document.getElementById('edit_ma_dau_moi_old').value;
        const formData = new FormData(this);
        
        fetch(`{{ url('dau-moi') }}/${maDauMoiOld}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                ma_dau_moi: formData.get('ma_dau_moi'),
                ten_dau_moi: formData.get('ten_dau_moi'),
                so_luong_thi_sinh: formData.get('so_luong_thi_sinh')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                document.getElementById('editDauMoiModal').querySelector('.btn-close').click();
                // Reload page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = document.getElementById('edit_' + field);
                        const errorDiv = document.getElementById('edit_' + field + '_error');
                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.textContent = data.errors[field][0];
                        }
                    });
                } else {
                    showAlert(data.message, 'danger');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi cập nhật đầu mối', 'danger');
        });
    });

    // Delete Dau Moi
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const maDauMoi = this.getAttribute('data-ma-dau-moi');
            const tenDauMoi = this.getAttribute('data-ten-dau-moi');
            
            currentDeleteMaDauMoi = maDauMoi;
            document.getElementById('deleteDauMoiName').textContent = tenDauMoi;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteDauMoiModal'));
            deleteModal.show();
        });
    });

    // Confirm Delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!currentDeleteMaDauMoi) return;

        fetch(`{{ url('dau-moi') }}/${currentDeleteMaDauMoi}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                document.getElementById('deleteDauMoiModal').querySelector('.btn-close').click();
                // Reload page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi xóa đầu mối', 'danger');
        });
    });

    // Clear form when modal is closed
    document.getElementById('addDauMoiModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('addDauMoiForm').reset();
        clearValidation('addDauMoiForm');
    });

    document.getElementById('editDauMoiModal').addEventListener('hidden.bs.modal', function() {
        clearValidation('editDauMoiForm');
    });
});
</script>
@endpush
@endsection
