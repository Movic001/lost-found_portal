<?php
//check if the session is started, if not, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../server/config/db.php';          // Your DB connection
require_once '../../server/classes/postItem_class.php';  // The item class

// Initialize the database connection
$database = new Database();
$db = $database->connect();


if (!isset($item)) {
    die("Unauthorized access. This page must be accessed through the claim route.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Global Styles */
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --secondary-color: #2c3e50;
            --light-color: #ecf0f1;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --border-radius: 8px;
            --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header and Navigation */
        .navbar {
            background-color: var(--secondary-color);
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--box-shadow);
        }

        .navbar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 10px;
        }

        .navbar-menu {
            display: flex;
            list-style: none;
        }

        .navbar-item {
            margin-left: 20px;
        }

        .navbar-link {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .navbar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-link.active {
            background-color: var(--primary-color);
        }

        .hamburger {
            display: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
        }

        /* Form Styles */
        form {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
        }

        .form-title {
            font-size: 1.8rem;
            color: var(--secondary-color);
            margin-bottom: 25px;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-color);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
            outline: none;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .security-question {
            background-color: var(--light-color);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 15px;
        }

        .security-question strong {
            color: var(--secondary-color);
        }

        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: block;
            width: 100%;
            font-weight: 600;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: var(--primary-dark);
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .admin-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-approve {
            background-color: var(--success-color);
            color: white;
        }

        .btn-approve:hover {
            background-color: #27ae60;
        }

        .btn-reject {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-reject:hover {
            background-color: #c0392b;
        }

        .btn-back {
            background-color: var(--light-color);
            color: var(--secondary-color);
        }

        .btn-back:hover {
            background-color: #ddd;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 70px;
                left: 20px;
                right: 20px;
                background-color: var(--secondary-color);
                border-radius: var(--border-radius);
                padding: 20px;
                z-index: 100;
                box-shadow: var(--box-shadow);
            }

            .navbar-menu.active {
                display: flex;
            }

            .navbar-item {
                margin: 10px 0;
                width: 100%;
            }

            .navbar-link {
                display: block;
                text-align: center;
            }

            .hamburger {
                display: block;
            }

            .form-footer {
                flex-direction: column;
                gap: 15px;
            }

            .admin-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            form {
                padding: 20px;
            }

            .form-title {
                font-size: 1.5rem;
            }

            input[type="text"],
            textarea,
            button[type="submit"] {
                padding: 10px;
            }
        }

        /* Accessibility Enhancements */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* UI Enhancement for focus state for accessibility */
        a:focus,
        button:focus,
        input:focus,
        textarea:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Status Indicators */
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .status-pending {
            background-color: var(--warning-color);
            color: white;
        }

        /* Form Validation Styles */
        .error-message {
            color: var(--danger-color);
            font-size: 0.9rem;
            margin-top: 5px;
            display: none;
        }

        input.error,
        textarea.error {
            border-color: var(--danger-color);
        }
    </style>
</head>

<body>
    <!-- Responsive Navigation Bar -->
    <nav class="navbar">
        <a href="./dashboard.php" class="navbar-brand">
            <i class="fas fa-search-location"></i>
            Lost & Found
        </a>
        <button class="hamburger" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="../../frontend/pages/dashboard.php" class="navbar-link">Dashboard</a>
            </li>
            <li class="navbar-item">
                <a href="#" class="navbar-link active">Claims</a>
            </li>
            <li class="navbar-item">
                <a href="../../frontend/pages/view_items.php" class="navbar-link">Items</a>
            </li>
        </ul>
    </nav>

    <!-- Main Claim Form -->
    <form action="../../server/routes/claimRoute.php" method="POST" id="claimForm">
        <h1 class="form-title">Claim Request Review</h1>

        <div class="status-badge status-pending">
            <i class="fas fa-clock"></i> Pending Review
        </div>

        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']) ?>">

        <div class="form-group">
            <label for="description">Describe the lost item:</label>
            <textarea id="description" name="description" required placeholder="Describe the lost item in detail"></textarea>
            <div class="error-message" id="description-error">Please provide a detailed description of the lost item.</div>
        </div>

        <div class="form-group">
            <label for="location_lost">Where did you lose it?</label>
            <input type="text" id="location_lost" name="location_lost" required placeholder="Location lost">
            <div class="error-message" id="location-error">Please specify where the item was lost.</div>
        </div>

        <div class="form-group">
            <div class="security-question">
                <strong>Security Question:</strong> <?= htmlspecialchars($item['unique_question']) ?>
            </div>

            <label for="security_answer">Answer:</label>
            <input type="text" id="security_answer" name="security_answer" required placeholder="Answer to security question">
            <div class="error-message" id="security-error">Please answer the security question.</div>
        </div>


        <button type="submit" name="claim_request" id="submitBtn">Submit Claim</button>
    </form>

    <script>
        // JavaScript for enhanced functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation toggle for mobile
            const hamburger = document.querySelector('.hamburger');
            const navMenu = document.querySelector('.navbar-menu');

            hamburger.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.navbar') && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                }
            });

            // Form validation
            const form = document.getElementById('claimForm');
            const description = document.getElementById('description');
            const locationLost = document.getElementById('location_lost');
            const securityAnswer = document.getElementById('security_answer');

            // Error elements
            const descriptionError = document.getElementById('description-error');
            const locationError = document.getElementById('location-error');
            const securityError = document.getElementById('security-error');

            form.addEventListener('submit', function(event) {
                let valid = true;

                // Reset errors
                description.classList.remove('error');
                locationLost.classList.remove('error');
                securityAnswer.classList.remove('error');

                descriptionError.style.display = 'none';
                locationError.style.display = 'none';
                securityError.style.display = 'none';

                // Validate description
                if (description.value.trim() === '') {
                    description.classList.add('error');
                    descriptionError.style.display = 'block';
                    valid = false;
                }

                // Validate location
                if (locationLost.value.trim() === '') {
                    locationLost.classList.add('error');
                    locationError.style.display = 'block';
                    valid = false;
                }

                // Validate security answer
                if (securityAnswer.value.trim() === '') {
                    securityAnswer.classList.add('error');
                    securityError.style.display = 'block';
                    valid = false;
                }

                if (!valid) {
                    event.preventDefault();
                }
            });

            // Admin action buttons
            const approveBtn = document.getElementById('approveBtn');
            const rejectBtn = document.getElementById('rejectBtn');
            const submitBtn = document.getElementById('submitBtn');

            approveBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to approve this claim?')) {
                    // Add approve action
                    const approveInput = document.createElement('input');
                    approveInput.type = 'hidden';
                    approveInput.name = 'admin_action';
                    approveInput.value = 'approve';
                    form.appendChild(approveInput);
                    form.submit();
                }
            });

            rejectBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reject this claim?')) {
                    // Add reject action
                    const rejectInput = document.createElement('input');
                    rejectInput.type = 'hidden';
                    rejectInput.name = 'admin_action';
                    rejectInput.value = 'reject';
                    form.appendChild(rejectInput);
                    form.submit();
                }
            });

            // Hide the main submit button for admin users
            // In a real implementation, you would check if the user is an admin
            submitBtn.style.display = 'none';

            // Visual feedback for input fields
            const inputFields = document.querySelectorAll('input, textarea');

            inputFields.forEach(function(field) {
                field.addEventListener('focus', function() {
                    this.classList.add('focused');
                });

                field.addEventListener('blur', function() {
                    this.classList.remove('focused');

                    // Validate on blur
                    if (this.value.trim() === '' && this.hasAttribute('required')) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });

                // Remove error class when user starts typing
                field.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('error');
                        const fieldId = this.id;
                        const errorElement = document.getElementById(fieldId + '-error');
                        if (errorElement) {
                            errorElement.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>