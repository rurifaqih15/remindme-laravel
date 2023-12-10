<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reminder List</title>
  @vite('resources/css/app.css')
  <script src="https://kit.fontawesome.com/a2f4ca2570.js" crossorigin="anonymous"></script>
  <style>
    .alert {
        @apply fixed bottom-0 right-0 m-4 p-4 bg-green-500 text-white rounded;
    }
</style>
</head>
<body class="bg-gray-100">

  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Reminder List</h1>

    <div class="mb-4 flex justify-end">
      <!-- Tombol untuk menampilkan pop up -->
      <button onclick="openCreateReminderModal()" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 focus:outline-none focus:border-green-700">
        <i class="fas fa-plus"></i> Create Reminder
      </button>
    </div>
    <div id="reminderList">
      <!-- Konten reminder list akan ditambahkan di sini -->
    </div>

   

    <div class="mb-4 flex items-center">
      <label for="limit" class="mr-2">Limit:</label>
      <select id="limit" onchange="changeLimit(this.value)" class="border border-gray-300 p-2 rounded">
        <option value="5">5</option>
        <option value="10" selected="selected">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
      </select>
    </div>
   <!-- Modal untuk membuat pengingat -->
<div id="createReminderModal" class="hidden">
    <div class="modal-content p-4 rounded fixed inset-0 flex items-center justify-center bg-opacity-50 z-50">
        <form class="bg-white p-8 rounded w-96">
            <h2 class="text-2xl font-bold mb-4">Create Reminder</h2>

            <div class="mb-4">
                <label for="reminderTitle" class="block text-sm font-medium text-gray-600">Title:</label>
                <input type="text" id="reminderTitle" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="reminderDescription" class="block text-sm font-medium text-gray-600">Description:</label>
                <textarea id="reminderDescription" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required></textarea>
            </div>

            <div class="mb-4">
                <label for="reminderRemindAt" class="block text-sm font-medium text-gray-600">Remind At:</label>
                <input type="datetime-local" id="reminderRemindAt" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="reminderEventAt" class="block text-sm font-medium text-gray-600">Event At:</label>
                <input type="datetime-local" id="reminderEventAt" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" onclick="createReminder()" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:border-blue-700">
                    Create Reminder
                </button>
                <button type="button" onclick="closeCreateReminderModal()" class="ml-2 bg-red-500 text-white p-2 rounded hover:bg-red-600 focus:outline-none focus:border-red-700">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Modal untuk Edit Reminder -->
<div id="editReminderModal" class="modal hidden">
    <div class="modal-content p-4 rounded fixed inset-0 flex items-center justify-center bg-opacity-50 z-50">
        <div class="bg-white w-1/2 p-4 rounded">
            <span class="close text-2xl font-bold cursor-pointer absolute top-2 right-2" onclick="closeEditReminderModal">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Edit Reminder</h2>
            <form id="editReminderForm">
            <input type="hidden" id="editReminderId">
                <div class="mb-4">
                    <label for="editReminderTitle" class="block text-sm font-medium text-gray-600">Title:</label>
                    <input type="text" id="editReminderTitle" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="editReminderDescription" class="block text-sm font-medium text-gray-600">Description:</label>
                    <textarea id="editReminderDescription" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="editReminderRemindAt" class="block text-sm font-medium text-gray-600">Remind At:</label>
                    <input type="datetime-local" id="editReminderRemindAt" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="editReminderEventAt" class="block text-sm font-medium text-gray-600">Event At:</label>
                    <input type="datetime-local" id="editReminderEventAt" class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" onclick="updateReminder()" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:border-blue-700">Update Reminder</button>
                    <button type="button" onclick="closeEditReminderModal()" class="ml-2 bg-red-500 text-white p-2 rounded hover:bg-red-600 focus:outline-none focus:border-red-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Reminder Modal -->
<div id="deleteReminderModal" class="modal hidden">
    <div class="modal-content p-4 rounded fixed inset-0 flex items-center justify-center bg-opacity-50 z-50">
        <div class="bg-white w-1/4 p-4 rounded">
            <span class="close text-2xl font-bold cursor-pointer absolute top-2 right-2" onclick="closeDeleteReminderModal">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Delete Reminder</h2>
            <p class="mb-4">Are you sure you want to delete this reminder?</p>
            <div class="flex justify-end">
                <button type="button" onclick="confirmDeleteReminder()" class="bg-red-500 text-white p-2 rounded hover:bg-red-600 focus:outline-none focus:border-red-700">Delete</button>
                <button type="button" onclick="closeDeleteReminderModal()" class="ml-2 bg-gray-300 text-gray-600 p-2 rounded hover:bg-gray-400 focus:outline-none focus:border-gray-500">Cancel</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal for showing reminder details -->
<div id="showReminderModal" class="hidden">
    <div class="modal-content p-4 rounded fixed inset-0 flex items-center justify-center bg-opacity-50 z-50">
        <div class="bg-white p-8 rounded w-96">
            <h2 class="text-2xl font-bold mb-4">Reminder Details</h2>

            <!-- Display reminder details here -->
            <div id="reminderDetails" class="mb-4">
                <p><strong>Title:</strong> <span id="showTitle"></span></p>
                <p><strong>Description:</strong> <span id="showDescription"></span></p>
                <p><strong>Remind At:</strong> <span id="showRemindAt"></span></p>
                <p><strong>Event At:</strong> <span id="showEventAt"></span></p>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeShowReminderModal()" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:border-blue-700">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>



</div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
        axios.get('/api/reminders', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('access_token')}`,
        },
      })
      .then(function (response) {
    var reminderListElement = document.getElementById('reminderList');
    var reminders = response.data.data.reminders;

    if (reminders.length > 0) {
        reminderListElement.innerHTML = `
            <table class="table-auto w-full border-collapse border text-sm">
                <thead>
                    <tr>
                      <th class="border-2">Id</th>
                        <th class="border-2">Title</th>
                        <th class="border-2">Description</th>
                        <th class="border-2">Remind At</th>
                        <th class="border-2">Event At</th>
                        <th class="border-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    ${reminders.map(function (reminder) {
                        const formattedRemindAt = reminder.remind_at
                            ? new Date(reminder.remind_at * 1000).toLocaleString()
                            : 'N/A';

                        const formattedEventAt = reminder.event_at
                            ? new Date(reminder.event_at * 1000).toLocaleString()
                            : 'N/A';

                        return `
                            <tr class="border-2">
                                <td class="border-2">${reminder.id}</td>
                                <td class="border-2">${reminder.title}</td>
                                <td class="border-2">${reminder.description}</td>
                                <td class="border-2">${formattedRemindAt}</td>
                                <td class="border-2">${formattedEventAt}</td>
                                <td class="border-2 flex items-center">
                                    <button onclick="editReminder(${reminder.id})"
                                        class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:border-blue-700 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteReminder(${reminder.id})"
                                        class="bg-red-500 text-white p-2 rounded hover:bg-red-600 focus:outline-none focus:border-red-700 mr-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button onclick="viewReminder(${reminder.id})"
                                        class="bg-green-500 text-white p-2 rounded hover:bg-green-600 focus:outline-none focus:border-green-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>`;
                    }).join('')}
                </tbody>
            </table>`;
    } else {
        // Tampilkan pesan jika tidak ada pengingat
        reminderListElement.innerHTML = '<p>No reminders found.</p>';
    }
})

      .catch(function (error) {
        console.error('Error fetching reminders:', error);
      });
  </script>

<script>
  // Fungsi untuk menangani perubahan limit
  function changeLimit(newLimit) {
    // Lakukan permintaan ke endpoint api/reminders dengan parameter limit baru
    axios.get(`/api/reminders?limit=${newLimit}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('access_token')}`,
      },
    })
    .then(function (response) {
      var reminderListElement = document.getElementById('reminderList');
      var reminders = response.data.data.reminders;

      if (reminders.length > 0) {
        reminderListElement.innerHTML = `
            <table class="table-auto w-full border-collapse border text-sm">
                <thead>
                    <tr>
                        <th class="border-2">ID</th>
                        <th class="border-2">Title</th>
                        <th class="border-2">Description</th>
                        <th class="border-2">Remind At</th>
                        <th class="border-2">Event At</th>
                        <th class="border-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    ${reminders.map(function (reminder) {
                        const formattedRemindAt = reminder.remind_at
                            ? new Date(reminder.remind_at * 1000).toLocaleString()
                            : 'N/A';

                        const formattedEventAt = reminder.event_at
                            ? new Date(reminder.event_at * 1000).toLocaleString()
                            : 'N/A';

                        return `
                            <tr class="border-2">
                                <td class="border-2">${reminder.id}</td>
                                <td class="border-2">${reminder.title}</td>
                                <td class="border-2">${reminder.description}</td>
                                <td class="border-2">${formattedRemindAt}</td>
                                <td class="border-2">${formattedEventAt}</td>
                                <td class="border-2 flex items-center">
                                    <button onclick="editReminder(${reminder.id})"
                                        class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:border-blue-700 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteReminder(${reminder.id})"
                                        class="bg-red-500 text-white p-2 rounded hover:bg-red-600 focus:outline-none focus:border-red-700 mr-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button onclick="viewReminder(${reminder.id})"
                                        class="bg-green-500 text-white p-2 rounded hover:bg-green-600 focus:outline-none focus:border-green-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>`;
                    }).join('')}
                </tbody>
            </table>`;
    } else {
        // Tampilkan pesan jika tidak ada pengingat
        reminderListElement.innerHTML = '<p>No reminders found.</p>';
    }
    })
    .catch(function (error) {
      console.error('Error fetching reminders:', error);
    });
  }
</script>

<script>
  
  function viewReminder(reminderId) {
    axios.get(`/api/reminders/${reminderId}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('access_token')}`,
            },
        })
        .then(function (response) {
            // Populate modal content with reminder details
            const formattedRemindAt = response.data.data.remind_at
            ? new Date(response.data.data.remind_at * 1000).toLocaleString()
            : 'N/A';

        const formattedEventAt =response.data.data.event_at
            ? new Date(response.data.data.event_at * 1000).toLocaleString()
            : 'N/A';

            document.getElementById('showTitle').innerText = response.data.data.title;
            document.getElementById('showDescription').innerText = response.data.data.description;
            document.getElementById('showRemindAt').innerText =formattedRemindAt
            document.getElementById('showEventAt').innerText = formattedEventAt;

            // Show the modal
            document.getElementById('showReminderModal').classList.remove('hidden');
        })
        .catch(function (error) {
            console.error('Error fetching reminder details:', error);
        });
      }
    function closeShowReminderModal() {
        // Close the modal
        document.getElementById('showReminderModal').classList.add('hidden');
    }
  // Fungsi untuk membuka modal
  function openCreateReminderModal() {
    var modal = document.getElementById('createReminderModal');
    modal.style.display = 'block';
  }

  // Fungsi untuk menutup modal
  function closeCreateReminderModal() {
    var modal = document.getElementById('createReminderModal');
    modal.style.display = 'none';
  }

  // Fungsi untuk mengirimkan permintaan POST ke endpoint api/reminders
  function createReminder() {
    var title = document.getElementById('reminderTitle').value;
    var description = document.getElementById('reminderDescription').value;
    var remindAt = new Date(document.getElementById('reminderRemindAt').value).getTime() / 1000;
    var eventAt = new Date(document.getElementById('reminderEventAt').value).getTime() / 1000;

    // Payload untuk POST request
    var payload = {
      title: title,
      description: description,
      remind_at: remindAt,
      event_at: eventAt,
    };

    // Lakukan permintaan POST ke endpoint api/reminders
    axios.post('/api/reminders', payload, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('access_token')}`,
      },
    })
    .then(function (response) {
      // Tambahkan logika jika berhasil
      console.log('Reminder created successfully:', response.data);
      closeCreateReminderModal();
    })
    .catch(function (error) {
      console.error('Error creating reminder:', error);
    });
  }
</script>
<script>
    function showReminder(reminderId) {
        axios.get(`/api/reminders/${reminderId}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('access_token')}`,
            },
        })
        .then(function (response) {
            // Populate modal content with reminder details
            document.getElementById('showTitle').innerText = response.data.title;
            document.getElementById('showDescription').innerText = response.data.description;
            document.getElementById('showRemindAt').innerText = response.data.remind_at;
            document.getElementById('showEventAt').innerText = response.data.event_at;

            // Show the modal
            document.getElementById('showReminderModal').classList.remove('hidden');
        })
        .catch(function (error) {
            console.error('Error fetching reminder details:', error);
        });
    }

    function closeShowReminderModal() {
        // Close the modal
        document.getElementById('showReminderModal').classList.add('hidden');
    }

    // Function to open the edit reminder modal
function editReminder(id) {
    // Make a request to get the reminder details by ID
    axios.get(`/api/reminders/${id}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('access_token')}`,
        },
    })
    .then(function (response) {
        var reminder = response.data.data;
      // Set the values in the edit reminder form
        document.getElementById('editReminderId').value = reminder.id;
        document.getElementById('editReminderTitle').value = reminder.title;
        document.getElementById('editReminderDescription').value = reminder.description;
        document.getElementById('editReminderRemindAt').value = new Date(reminder.remind_at * 1000).toISOString().slice(0, -8);
        document.getElementById('editReminderEventAt').value = new Date(reminder.event_at * 1000).toISOString().slice(0, -8);

        // Open the edit reminder modal
        document.getElementById('editReminderModal').classList.remove('hidden');
    })
    .catch(function (error) {
        console.error('Error fetching reminder details for editing:', error);
    });
}

// Function to update the reminder
function updateReminder() {
    // Get the reminder ID
    // (Assuming you have a variable to store the currently edited reminder ID)
    const reminderId = document.getElementById('editReminderId').value;

    // Get the values from the edit reminder form
    const title = document.getElementById('editReminderTitle').value;
    const description = document.getElementById('editReminderDescription').value;
    const remindAt = new Date(document.getElementById('editReminderRemindAt').value).getTime() / 1000;
    const eventAt = new Date(document.getElementById('editReminderEventAt').value).getTime() / 1000;

    // Make a request to update the reminder
    axios.put(`/api/reminders/${reminderId}`, {
        title: title,
        description: description,
        remind_at: remindAt,
        event_at: eventAt,
    }, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('access_token')}`,
        },
    })
    .then(function (response) {
        console.log('Reminder updated successfully:', response.data);

        // Close the edit reminder modal
        closeEditReminderModal();

        // You may also refresh the reminder list or perform other actions
    })
    .catch(function (error) {
        console.error('Error updating reminder:', error);
    });
}

// Function to close the edit reminder modal
function closeEditReminderModal() {
    document.getElementById('editReminderModal').classList.add('hidden');
}

function deleteReminder(id) {
        // Open the delete reminder modal
        document.getElementById('deleteReminderModal').classList.remove('hidden');

        // Confirm the deletion when the user clicks the "Delete" button
        document.getElementById('deleteReminderModal').addEventListener('click', function (event) {
            if (event.target.classList.contains('bg-red-500')) {
                // Make a request to delete the reminder by ID
                axios.delete(`/api/reminders/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('access_token')}`,
                    },
                })
                .then(function () {
                    // Display a success alert
                    showSuccessAlert('Reminder deleted successfully');

                    // Close the delete reminder modal
                    closeDeleteReminderModal();

                    // Reload the reminders data and update the list
                    fetchReminders();
                })
                .catch(function (error) {
                    console.error('Error deleting reminder:', error);
                });
            }
        });
    }

    function showSuccessAlert(message) {
        // Create an alert element with Tailwind CSS styling
        const alertElement = document.createElement('div');
        alertElement.classList.add('alert');
        alertElement.textContent = message;

        // Append the alert to the body
        document.body.appendChild(alertElement);

        // Remove the alert after a few seconds (adjust the timeout as needed)
        setTimeout(() => {
            alertElement.remove();
        }, 3000); // 3000 milliseconds (3 seconds)
    }

    function fetchReminders() {
        // This function should contain the logic to fetch reminders from the API
        // and update the reminder list. Replace it with your actual implementation.
        // For example, you can use a similar structure as in your initial code.
        axios.get('/api/reminders', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('access_token')}`,
            },
        })
        .then(function (response) {
          showSuccessAlert('Reminder deleted successfully');
          location.reload(); 
        })
        .catch(function (error) {
            console.error('Error fetching reminders:', error);
        });
    }

    function updateReminderList(reminders) {
        const reminderListElement = document.getElementById('reminderList');

        if (reminders.length > 0) {
            reminderListElement.innerHTML = `
                <table class="table-auto w-full border-collapse border text-sm">
                    <!-- ... (rest of the table structure) ... -->
                </table>`;
        } else {
            reminderListElement.innerHTML = '<p>No reminders found.</p>';
        }
    }

function confirmDeleteReminder() {
    // This function is intentionally left empty
    // It will be triggered when the user clicks the "Delete" button in the modal
}

function closeDeleteReminderModal() {
    // Close the delete reminder modal
    document.getElementById('deleteReminderModal').classList.add('hidden');
}



</script>
</body>
</html>
