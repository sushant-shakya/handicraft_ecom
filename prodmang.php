<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 20px;
    }

    h1 {
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #ff9900;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .actions {
      position: relative;
    }

    .action-menu {
      display: none;
      position: absolute;
      background: white;
      border: 1px solid #ccc;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .action-menu button {
      width: 100%;
      padding: 10px;
      border: none;
      background: white;
      text-align: left;
      cursor: pointer;
    }

    .action-menu button:hover {
      background: #f4f4f4;
    }

    .add-button {
      background-color: #ff9900;
      color: white;
      padding: 10px 15px;
      border: none;
      cursor: pointer;
    }

    .add-button:hover {
      background-color: #ff9900;
    }
  </style>
</head>
<body>
  <h1>Manage Products</h1>
  <a href="add-product-form.php">
    <button class="add-button">Add</button>
</a>
  <table>
    <thead>
      <tr>
        <th>PName</th>
        <th>Subtitle</th>
        <th>Price</th>
        <th>Dimension</th>
        <th>Material</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Green Tara</td>
        <td>Tara</td>
        <td>30000</td>
        <td>Height: 12" Weight: 1kg</td>
        <td>Crafted from wood</td>
        <td>Handcrafted statue of Green Tara</td>
        <td class="actions">
          <button onclick="toggleMenu(this)">...</button>
          <div class="action-menu">
            <button onclick="editProduct(this)">Edit</button>
            <button onclick="deleteProduct(this)">Delete</button>
          </div>
        </td>
      </tr>
      <tr>
        <td>Shakyamuni Buddha</td>
        <td>Buddha</td>
        <td>20000</td>
        <td>Height: 10" Weight: 1.2kg</td>
        <td>Crafted from brass</td>
        <td>Depicts Shakyamuni Buddha in meditation</td>
        <td class="actions">
          <button onclick="toggleMenu(this)">...</button>
          <div class="action-menu">
            <button onclick="editProduct(this)">Edit</button>
            <button onclick="deleteProduct(this)">Delete</button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <script>
function toggleMenu(button) {
    let menu = button.nextElementSibling;
    document.querySelectorAll('.action-menu').forEach(menuItem => {
        if (menuItem !== menu) {
            menuItem.style.display = 'none';
        }
    });
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function editProduct(button) {
    alert('Edit action triggered');
}

function deleteProduct(button) {
    if (confirm('Are you sure you want to delete this?')) {
        alert('Deleted!');
    }
}

// Close the menu when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.matches('button')) {
        document.querySelectorAll('.action-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});

</script>
</body>
</html>
