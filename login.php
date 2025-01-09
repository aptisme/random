<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
// Load WordPress database configuration
require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-config.php');

// Custom function to insert a user manually
function custom_insert_user($username, $email, $password, $role = 'subscriber') {
    global $wpdb;

    // Check if the user with the given username or email already exists
    $existing_user = $wpdb->get_var(
        $wpdb->prepare("SELECT user_login FROM $wpdb->users WHERE user_login = %s OR user_email = %s", $username, $email)
    );

    if ($existing_user) {
        return new WP_Error('existing_user', __('User with this username or email already exists.', 'your-text-domain'));
    }

    // Generate hashed password
    $hashed_password = wp_hash_password($password);

    // Insert user into the database
    $wpdb->insert(
        $wpdb->users,
        array(
            'user_login' => $username,
            'user_pass'  => $hashed_password,
            'user_email' => $email,
            'user_registered' => current_time('mysql'),
        )
    );

    // Get the inserted user ID
    $user_id = $wpdb->insert_id;

    // Assign role to the user
    $wpdb->insert(
        $wpdb->usermeta,
        array(
            'user_id' => $user_id,
            'meta_key' => $wpdb->prefix . 'capabilities',
            'meta_value' => serialize(array($role => true))
        )
    );

    return $user_id;
}

// Get all roles dynamically from WordPress
global $wp_roles;
$roles = $wp_roles->get_names();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get selected role from the form

    // Insert user
    $result = custom_insert_user($username, $email, $password, $role);

    // Display result
    if (!is_wp_error($result)) {
        echo 'User inserted successfully with ID: ' . $result;
    } else {
        echo 'Error: ' . $result->get_error_message();
    }
}
?>

<!-- Bootstrap 4 form to collect user information -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add New User</h5>
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select class="form-control" id="role" name="role">
                                <?php foreach ($roles as $role_key => $role_value) : ?>
                                    <option value="<?php echo esc_attr($role_key); ?>"><?php echo esc_html($role_value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
