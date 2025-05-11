<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  </head>
  <body class="bg-light">
    <main class="container">
       <!-- START FORM -->
       <form id="userForm">
  <div class="my-3 p-3 bg-body rounded shadow-sm">
  <h6 class="border-bottom pb-2 mb-0">Form Users</h6>
<!-- Name Field -->
<div class="mb-3 row">
  <label for="name" class="col-sm-2 col-form-label">Name</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="name" id="name">
    <div id="error-name" class="text-danger small mt-1"></div>
  </div>
</div>

<!-- Email Field -->
<div class="mb-3 row">
  <label for="email" class="col-sm-2 col-form-label">Email</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="email" id="email">
    <div id="error-email" class="text-danger small mt-1"></div>
  </div>
</div>

<!-- Age Field -->
<div class="mb-3 row">
  <label for="age" class="col-sm-2 col-form-label">Age</label>
  <div class="col-sm-10">
    <input type="number" class="form-control" name="age" id="age">
    <div id="error-age" class="text-danger small mt-1"></div>
  </div>
</div>

    <div class="mb-3 row">
      <div class="col-sm-2 col-form-label"></div>
      <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">SIMPAN</button></div>
    </div>
  </div>
</form>

        <!-- AKHIR FORM -->
        
        <!-- START DATA -->
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Data Users</h6>
          
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-1">No</th>
                            <th class="col-md-4">Name</th>
                            <th class="col-md-3">Email</th>
                            <th class="col-md-2">Age</th>
                            <th class="col-md-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                    </tbody>
                </table>
          </div>
          <!-- AKHIR DATA -->
    </main>
    <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editUserForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit-id">
          <div class="mb-3">
            <label for="edit-name" class="form-label">Name</label>
            <input type="text" class="form-control" id="edit-name" required>
          </div>
          <div class="mb-3">
            <label for="edit-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="edit-email" required>
          </div>
          <div class="mb-3">
            <label for="edit-age" class="form-label">Age</label>
            <input type="number" class="form-control" id="edit-age">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script>
  function loadUsers() {
  fetch('http://localhost:8000/api/users/list')
    .then(response => response.json())
    .then(data => {
      const tableBody = document.querySelector('#usersTableBody');
      tableBody.innerHTML = '';
      let i = 1;

      if (Array.isArray(data.data)) {
        data.data.forEach(user => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${i++}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.age ?? '-'}</td>
            <td>
              <button class="btn btn-warning btn-sm edit-btn" data-id="${user.id}">Edit</button>
              <a href="#" class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Del</a>
            </td>
          `;
          tableBody.appendChild(row);
        });


        // Attach event listeners to Edit buttons
        document.querySelectorAll('.edit-btn').forEach(button => {
          button.addEventListener('click', function () {
            fetch(`http://localhost:8000/api/users/${this.dataset.id}`)
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  document.getElementById('edit-id').value = data.data.id;
                  document.getElementById('edit-name').value = data.data.name;
                  document.getElementById('edit-email').value = data.data.email;
                  document.getElementById('edit-age').value = data.data.age;
                }
                else {
                  console.error('Unexpected response format: ', data);
                }
              })
            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            modal.show();
          });
        });

      } else {
        console.error('Unexpected response format: ', data);
      }
    })
    .catch(error => {
      console.error('Error fetching users:', error);
    });
}

document.addEventListener('DOMContentLoaded', function () {
  window.deleteUser = function (id) {
    if (!confirm("Are you sure you want to delete this user?")) return;

    fetch(`http://localhost:8000/api/users/${id}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
      }
    })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        loadUsers(); // reload the table
      })
      .catch(error => {
        console.error('Error deleting user:', error);
        alert('Failed to delete user.');
      });
  };

  loadUsers();
});

// Handle Update Submission from Modal
document.getElementById('editUserForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const id = document.getElementById('edit-id').value;
    const name = document.getElementById('edit-name').value.trim();
    const email = document.getElementById('edit-email').value.trim();
    const age = document.getElementById('edit-age').value;

    const xhr = new XMLHttpRequest();
    xhr.open('PUT', `http://localhost:8000/api/users/${id}`, true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onload = function () {
      try {
        const res = JSON.parse(xhr.responseText);

        if (xhr.status === 200 || xhr.status === 204) {
          alert('User updated successfully!');
          bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
          loadUsers(); // Refresh user table
        } else if (res.errors) {
          Object.entries(res.errors).forEach(([field, messages]) => {
            alert(`Error in ${field}: ${messages.join(', ')}`);
          });
        } else {
          alert('Failed to update user. Message: ' + (res.message || 'Unknown error'));
        }
      } catch (err) {
        console.error('Parsing error:', err);
        alert('Failed to update user. Check console for details.');
      }
    };

    xhr.onerror = function () {
      alert('Update request failed. Please check your connection.');
    };

    const data = JSON.stringify({ name, email, age });
    xhr.send(data);
  });

  document.addEventListener('DOMContentLoaded', function () {
    loadUsers(); // Initial load

    const userForm = document.getElementById('userForm');

    userForm.addEventListener('submit', function (e) {
      e.preventDefault();

      // Clear all previous error messages
      ['name', 'email', 'age'].forEach(field => {
        document.getElementById(`error-${field}`).textContent = '';
      });

      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const age = document.getElementById('age').value;

      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'http://localhost:8000/api/users', true);
      xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
      xhr.setRequestHeader('Accept', 'application/json');

      xhr.onload = function () {
        try {
          const res = JSON.parse(xhr.responseText);

          if (xhr.status === 201) {
            alert('User created successfully!');
            userForm.reset();
            loadUsers(); // Refresh the user table
          } else if (res.errors) {
            // Show field-specific errors
            Object.entries(res.errors).forEach(([field, messages]) => {
              const errorDiv = document.getElementById(`error-${field}`);
              if (errorDiv) {
                errorDiv.textContent = messages.join(', ');
              }
            });
          } else if (res.message) {
            alert('Failed to create user. Error: ' + res.message);
          } else {
            alert('An unexpected error occurred.');
          }
        } catch (err) {
          console.error('Error parsing response:', err);
          alert('Failed to create user. Please check the console for details.');
        }
      };

      xhr.onerror = function () {
        alert('Request failed. Please check your connection or server status.');
      };

      const data = JSON.stringify({ name, email, age });
      xhr.send(data);
    });
  });

</script>
</body>
</html>
