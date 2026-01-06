<?php
session_start();

// Get role from query parameter
$role = $_GET['role'] ?? 'user'; // default to 'user' if not provided

$servername = "localhost";
$username = "root";
$password = "";
$database = "recipe_manager";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ===================== INSERT =====================
if (isset($_POST['add']) && $role === 'admin') {
    $name = $_POST['name'];
    $ingredients = $_POST['ingredients'];

    $stmt = $conn->prepare("INSERT INTO recipes (name, ingredients, instructions, cooking_time) VALUES (?, ?, '', '')");
    $stmt->bind_param("ss", $name, $ingredients);
    $stmt->execute();
    $stmt->close();

    header("Location: recipe_food.php?role=admin");
    exit();
}

// ===================== UPDATE =====================
if (isset($_POST['update']) && $role === 'admin') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $ingredients = $_POST['ingredients'];

    $stmt = $conn->prepare("UPDATE recipes SET name=?, ingredients=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $ingredients, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: recipe_food.php?role=admin");
    exit();
}

// ===================== DELETE =====================
if (isset($_GET['delete']) && $role === 'admin') {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM recipes WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: recipe_food.php?role=admin");
    exit();
}

// ===================== FETCH ALL =====================
$result = $conn->query("SELECT * FROM recipes");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recipe Manager</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f4f9;
            color: #333;
            text-align: center;
        }
        h1 { margin-top: 40px; font-size: 48px; color: #4CAF50; }

        /* Buttons */
        .menu-buttons { margin: 30px 0; }
        .menu-buttons button {
            width: 180px;
            height: 50px;
            margin: 10px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
            border: none;
            background: #4CAF50;
            color: white;
            transition: 0.3s;
        }
        .menu-buttons button:hover { background: #45a049; }

        /* Forms */
        .form-container { margin-top: 20px; padding: 20px; }
        input[type=text], textarea {
            width: 80%;
            max-width: 600px;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button[type=submit] {
            padding: 12px 25px;
            font-size: 18px;
            border-radius: 6px;
            border: none;
            background: #2196F3;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        button[type=submit]:hover { background: #0b7dda; }

        /* Tables */
        table { margin: 20px auto; border-collapse: collapse; width: 80%; max-width: 900px; background: white; box-shadow: 0px 4px 8px rgba(0,0,0,0.1);}
        th, td { border: 1px solid #ddd; padding: 12px; font-size: 16px; text-align: center; }
        th { background: #4CAF50; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        a { text-decoration: none; color: #f44336; font-weight: bold; }
        a:hover { text-decoration: underline; }

        /* Sections */
        #insert, #view, #update, #delete {
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
        }
        h2 { color: #333; }
    </style>
</head>
<body>

<h1>Recipe Manager</h1>

<div class="menu-buttons">
    <?php if($role === 'admin'): ?>
        <button onclick="showSection('insert')">Insert Recipe</button>
        <button onclick="showSection('update')">Update Recipe</button>
    <?php endif; ?>
    <button onclick="showSection('view')">View Recipe</button>
    <?php if($role === 'admin'): ?>
        <button onclick="showSection('delete')">Delete Recipe</button>
    <?php endif; ?>
</div>

<div class="form-container">

<!-- INSERT -->
<?php if($role === 'admin'): ?>
<div id="insert" style="display:none;">
    <h2>Insert Your Recipe</h2>
    <form method="post" action="">
        <input type="text" name="name" placeholder="Recipe Name" required><br>
        <textarea name="ingredients" placeholder="Ingredients" rows="6" required></textarea><br>
        <button type="submit" name="add">Add Recipe</button>
    </form>
</div>
<?php endif; ?>

<!-- VIEW -->
<div id="view" style="display:none;">
    <h2>View Recipes</h2>
    <?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Ingredients</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['ingredients']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>No recipes found.</p>
    <?php endif; ?>
</div>

<!-- UPDATE -->
<?php if($role === 'admin'): ?>
<div id="update" style="display:none;">
    <h2>Update Recipe</h2>

    <?php
    // Step 1: Show ID input if no ID submitted yet
    if (!isset($_POST['fetch'])):
    ?>
        <form method="post" action="">
            <input type="number" name="id" placeholder="Enter Recipe ID" required>
            <button type="submit" name="fetch">Enter</button>
        </form>
    <?php
    // Step 2: Show the recipe form if ID submitted
    elseif(isset($_POST['fetch'])):
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("SELECT * FROM recipes WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result_recipe = $stmt->get_result();

        if($result_recipe->num_rows > 0):
            $recipe = $result_recipe->fetch_assoc();
    ?>
        <!-- Recipe form appears below after entering ID -->
        <form method="post" action="" style="margin-top:20px;">
            <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">
            <input type="text" name="name" value="<?php echo $recipe['name']; ?>" required><br>
            <textarea name="ingredients" rows="4" required><?php echo $recipe['ingredients']; ?></textarea><br>
            <button type="submit" name="update">Update Recipe</button>
        </form>
    <?php
        else:
            echo "<p>Recipe with ID $id not found.</p>";
        endif;
        $stmt->close();
    endif;
    ?>
</div>
<?php endif; ?>


<!-- DELETE -->
<?php if($role === 'admin'): ?>
<div id="delete" style="display:none;">
    <h2>Delete Recipes</h2>
    <?php
    $delete_result = $conn->query("SELECT * FROM recipes");
    if ($delete_result->num_rows > 0):
    ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Ingredients</th>
            <th>Action</th>
        </tr>
        <?php while($row = $delete_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['ingredients']; ?></td>
            <td><a href="?role=admin&delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>

        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>No recipes to delete.</p>
    <?php endif; ?>
</div>
<?php endif; ?>

</div>

<script>
function showSection(sectionId) {
    const sections = ['insert','view','update','delete'];
    sections.forEach(id => {
        const el = document.getElementById(id);
        if(el) el.style.display = 'none';
    });
    const active = document.getElementById(sectionId);
    if(active) active.style.display = 'block';
}
</script>

</body>
</html>
<button onclick="window.location.href='entry.php'" 
style="background-color: navy; color: white; padding: 8px 15px; border-radius: 8px; border: none; cursor: pointer; margin: 10px;">
⬅ Back
</button>
