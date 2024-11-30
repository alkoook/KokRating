
 @extends('admin.adminpanel')

@section('content')

<!-- إضافة نموذج إدخال مستخدم جديد -->
<div class="d-flex">
    <div class="w-25 mr-4">
        <form id="add-admin-form" action="{{route('store_admin')}}" method="POST" class="max-w-md mx-auto" style="top:50px;position: relative;height:100vh;">
            @csrf
            <h2 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Add New Admin</h2>
            <input type="text" id="new-name" class="block w-full mb-4 p-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name')is-invalid @enderror" placeholder="Name" name="name"/>
            @error('name')
            <div class="text-danger invalid-feedback">{{ $message }}</div>
        @enderror
            <input type="email" id="new-email" class="block w-full mb-4 p-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('email')is-invalid @enderror" placeholder="Email" name="email"/>
            @error('email')
            <div class="text-danger invalid-feedback">{{ $message }}</div>
        @enderror
            <input type="password" id="new-password" class="block w-full mb-4 p-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Password" name="password"/>
            @error('password')
            <div class="text-danger invalid-feedback">{{ $message }}</div>
        @enderror
            <button type="submit" class="text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" >Add new Admin</button>

        </form>
    </div>

    <!-- محتوى البحث والجدول -->
    <div class="w-75">
        <!-- نموذج البحث -->
        <form class="max-w-md mx-auto mb-4">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                </div>
                <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search on Email Address" required />
            </div>
        </form>

        @php
            $count = ($admins->currentPage() - 1) * $admins->perPage();
        @endphp

        <!-- جدول عرض المستخدمين -->
        <table class="w-full bg-white shadow-lg rounded-lg" >
            <thead>
                <tr>
                    <th>Count</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach ($admins as $admin)
                <tr id="row-{{ $admin->id }}" >
                    <td ><strong>{{ ++$count }}</strong></td>
                    <td id="name-{{ $admin->id }}"><strong>{{ $admin->name }}</strong></td>
                    <td id="email-{{ $admin->id }}"><strong>{{ $admin->email }}</strong></td>
                    <td><button type="button" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" onclick="editAdmin({{ $admin->id }})" data-id="{{ $admin->id }}">Edit</button></td>
                    <td><button type="button" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" onclick="deleteAdmin({{ $admin->id }})">Delete</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $admins->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function editAdmin(adminId) {
        Swal.fire({
            title: 'Edit Admin',
            html: `
                <input type="text" id="edit-name" class="swal2-input" placeholder="Name" value="${$('#name-' + adminId).text()}">
                <input type="email" id="edit-email" class="swal2-input" placeholder="Email" value="${$('#email-' + adminId).text()}">
            `,
            confirmButtonText: 'Save',
            showCancelButton: true,
            preConfirm: () => {
                const name = Swal.getPopup().querySelector('#edit-name').value;
                const email = Swal.getPopup().querySelector('#edit-email').value;

                if (!name || !email) {
                    Swal.showValidationMessage('Name and Email cannot be empty');
                }
                return { name, email };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { name, email } = result.value;
                $.ajax({
                    url: '{{ route("update.admin") }}',
                    method: 'POST',
                    data: {
                        id: adminId,
                        name,
                        email,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#name-' + adminId).text(name);
                            $('#email-' + adminId).text(email);
                            Swal.fire('Success', 'Admin updated successfully!', 'success');
                        } else {
                            Swal.fire('Error', response.message || 'Update failed.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        });
    }

    function deleteAdmin(adminId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin") }}/' + adminId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#row-' + adminId).remove();
                            Swal.fire('Deleted!', 'Admin has been deleted.', 'success');
                        } else {
                            Swal.fire('Error', response.message || 'Delete failed.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        });
    }

    document.getElementById('default-search').addEventListener('input', function() {
        const searchTerm = this.value;

        $.ajax({
            url: '{{ route("search.admins") }}',
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                renderTable(response);
            }
        });
    });

    function renderTable(filteredData) {
        const tbody = document.getElementById('tbody');
        tbody.innerHTML = '';

        filteredData.forEach((item, index) => {
            const row = document.createElement('tr');
            row.setAttribute('id', 'row-' + item.id);
            row.innerHTML = `
                <td><strong>${index + 1}</strong></td>
                <td id="name-${item.id}"><strong>${item.name}</strong></td>
                <td id="email-${item.id}"><strong>${item.email}</strong></td>
                <td><button type="button" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" onclick="editAdmin(${item.id})" data-id="${item.id}">Edit</button></td>
                <td><button type="button" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" onclick="deleteAdmin(${item.id})">Delete</button></td>`;
            tbody.appendChild(row);
        });
    }

  </script>
  {{-- عرض الرسائل بعد الإضافة --}}
@if(session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'Ok'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: 'Error!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonText: 'Ok'
    });
</script>
@endif

            @endsection


