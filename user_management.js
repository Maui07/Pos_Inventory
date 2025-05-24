// Grab elements
const userForm = document.getElementById('user-form');
const userIdInput = document.getElementById('user-id');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const roleSelect = document.getElementById('role');
const formTitle = document.getElementById('form-title');
const submitButton = userForm.querySelector('input[type="submit"]');

// Show the Add User form
function resetForm() {
  formTitle.textContent = 'Add New User';
  userForm.action = 'create_user.php';
  userIdInput.value = '';
  usernameInput.value = '';
  passwordInput.value = '';
  roleSelect.value = '';
  submitButton.value = 'Add User';
  userForm.style.display = 'block';
}

// Show the form pre-filled for editing a user
function editUser(id) {
  const row = document.querySelector(`tr[data-id='${id}']`);
  const username = row.getAttribute('data-username');
  const role = row.getAttribute('data-role');

  formTitle.textContent = 'Edit User';
  userForm.action = 'update_user.php';
  userIdInput.value = id;
  usernameInput.value = username;
  passwordInput.value = ''; // Optional: keep blank on edit
  roleSelect.value = role;
  submitButton.value = 'Update User';
  userForm.style.display = 'block';
}

// Delete a user with confirmation
function deleteUser(id) {
  if (confirm('Are you sure you want to delete this user?')) {
    window.location.href = `delete_user.php?id=${id}`;
  }
}

// Hide the form initially
document.addEventListener('DOMContentLoaded', () => {
  userForm.style.display = 'none';
});
