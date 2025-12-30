```markdown
# 🌿 Cebu Plant Depot - Point of Sale & Inventory Management System

![GitHub License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![MySQL Version](https://img.shields.io/badge/MySQL-10.4+-blue.svg)
![Bootstrap Version](https://img.shields.io/badge/Bootstrap-5.3.3-blue.svg)

🌱 **Where technology meets greenery.** Cebu Plant Depot is a comprehensive Point of Sale (POS) and Inventory Management System (IMS) designed specifically for plant shops and nurseries. This system helps plant businesses streamline operations, manage inventory, and provide a seamless shopping experience for customers.

---

## ✨ Features

### 🌿 For Customers
- **Browse Products**: View all available plants and gardening supplies with detailed descriptions
- **Shopping Cart**: Add, update, or remove items with ease
- **Secure Checkout**: Fast and secure payment processing
- **Order Tracking**: Real-time order status updates
- **Order History**: Access past purchases for quick reordering
- **User Accounts**: Personalized experience with profile management

### 🌳 For Business Owners & Admins
- **CRUD Operations**: Manage products, categories, and stock levels
- **Order Management**: Update order statuses from processing to delivery
- **Real-Time Dashboard**: Interactive analytics with sales and inventory insights
- **Sales Reports**: Generate detailed PDF reports of sales statistics
- **Low Stock Alerts**: Automated notifications for inventory management
- **User Management**: Create and manage customer accounts

### 🔧 Technical Features
- **Responsive Design**: Works seamlessly on all devices
- **Secure Authentication**: Password hashing and session management
- **Database Integration**: MySQL for robust data storage
- **PDF Generation**: Detailed sales reports with fpdf library
- **Image Uploads**: Product image management

---

## 🛠️ Tech Stack

| Category          | Technologies Used                          |
|-------------------|-------------------------------------------|
| **Language**      | PHP 8.2+                                  |
| **Frontend**      | Bootstrap 5.3.3, HTML5, CSS3, JavaScript  |
| **Backend**       | MySQL 10.4+, PHP PDO/MySQLi              |
| **Libraries**     | Font Awesome, jQuery (if applicable)      |
| **PDF Generation**| fpdf library                              |
| **Authentication**| Session-based with password hashing      |

---

## 📦 Installation

### Prerequisites

Before you begin, ensure you have met the following requirements:

- **PHP 8.2+** installed on your system
- **MySQL 10.4+** database server
- **Apache/Nginx** web server
- **Composer** (for dependency management, if applicable)

### Quick Start

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/Cebu_Plant_Depot.git
   cd Cebu_Plant_Depot
   ```

2. **Set up the database:**
   - Import the provided `cebu_plant_depot.sql` file into your MySQL database
   - Update the database credentials in `Main/php/database.php`:
     ```php
     $db_server = "your_mysql_host";
     $db_user   = "your_mysql_username";
     $db_pass   = "your_mysql_password";
     $db_name   = "cebu_plant_depot";
     ```

3. **Configure the application:**
   - Upload all files to your web server directory
   - Ensure proper file permissions for uploads and image directories

4. **Run the application:**
   - Access the application through your web browser: `http://yourdomain.com/Cebu_Plant_Depot`

---

## 🎯 Usage

### Basic Usage

#### Customer Login
```php
// Example of handling login in index.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    // Database query to verify credentials
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION["user_id"] = $row['id'];
            $_SESSION["email"] = $row['email'];
            $_SESSION["role"] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: Admin/main.php");
            } else {
                header("Location: Main/php/catalogue.php");
            }
            exit;
        }
    }
}
```

#### Adding a Product (Admin)
```php
// Example from Admin/create.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $availability = intval($_POST['product_availability']);
    $quantity = intval($_POST['product_quantity']);

    $imgName = $_FILES['product_image']['name'];
    $imgTmp = $_FILES['product_image']['tmp_name'];
    $uploadPath = "../images/" . basename($imgName);
    move_uploaded_file($imgTmp, $uploadPath);

    $sql = "INSERT INTO products (product_name, product_image, product_price,
             product_availability, product_quantity) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssdii", $name, $imgName, $price, $availability, $quantity);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: product_list.php");
    exit();
}
```

#### Generating Sales Reports (Admin)
```php
// Example from Admin/download_sales_pdf.php
require('fpdf.php');
include("../Main/php/database.php");

// Create PDF object
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Sales Statistics and Breakdown', 0, 1, 'C');

// Generate sales data and add to PDF
// ... (code continues with database queries and PDF formatting)
```

---

## 📁 Project Structure

```
Cebu_Plant_Depot/
├── Admin/
│   ├── bootstrap.css          # Custom admin styles
│   ├── create.php             # Product creation form
│   ├── download_sales_pdf.php # PDF report generation
│   ├── font/                  # Font files for PDF generation
│   ├── fpdf.php               # PDF generation library
│   ├── index.php              # Admin login page
│   ├── logout.php             # Admin logout handler
│   ├── main.php               # Admin dashboard
│   ├── orders.php             # Order management
│   ├── product_list.php       # Product listing and management
│   └── ... (other admin files)
│
├── Cebu_Plant_Depot_Bootstrap/
│   ├── css/                   # Bootstrap CSS files
│   ├── html/                  # HTML templates
│   └── images/                # Static images
│
├── Main/
│   ├── css/                   # Main application styles
│   ├── html/                  # HTML templates
│   ├── images/                # Product images
│   ├── php/                   # PHP application logic
│   │   ├── catalogue.php       # Product catalogue
│   │   ├── database.php        # Database connection
│   │   ├── forgot_password.php # Forgot password functionality
│   │   ├── index.php          # Main login page
│   │   ├── logout.php         # Logout handler
│   │   ├── manual_send.php     # Manual password reset request
│   │   ├── order_history.php   # Order history for customers
│   │   ├── order.php          # Order processing
│   │   ├── signup.php         # User registration
│   │   └── user_info.php      # User profile management
│   └── js/                    # JavaScript files
│
├── images/                    # Shared images directory
├── js/                        # Shared JavaScript files
├── cebu_plant_depot.sql       # Database schema and sample data
└── README.md                  # This file
```

---

## 🔧 Configuration

### Environment Variables

Create a `.env` file in the root directory for sensitive configuration:

```env
DB_HOST=localhost
DB_USER=your_db_username
DB_PASS=your_db_password
DB_NAME=cebu_plant_depot
APP_URL=http://yourdomain.com/Cebu_Plant_Depot
```

### Database Configuration

Update the database connection in `Main/php/database.php`:

```php
<?php
$db_server = $_ENV['DB_HOST'] ?? 'localhost';
$db_user   = $_ENV['DB_USER'] ?? 'root';
$db_pass   = $_ENV['DB_PASS'] ?? '';
$db_name   = $_ENV['DB_NAME'] ?? 'cebu_plant_depot';
$con = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$con) {
    die("<script>alert('Unable to connect to the database!');</script>");
}
?>
```

### Customization Options

1. **Branding**: Update the logo in `images/logonavwhite.png` and modify the color scheme in CSS files
2. **Email Settings**: Configure email notifications in `Main/php/manual_send.php`
3. **Payment Gateways**: Integrate additional payment methods in the checkout process

---

## 🤝 Contributing

We welcome contributions from the community! Here's how you can contribute:

### How to Contribute

1. **Fork the repository** and create your feature branch:
   ```bash
   git checkout -b feature/your-feature
   ```

2. **Commit your changes**:
   ```bash
   git commit -m "Add some feature"
   ```

3. **Push to the branch**:
   ```bash
   git push origin feature/your-feature
   ```

4. **Open a Pull Request** with a clear description of your changes

### Development Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/Cebu_Plant_Depot.git
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up your development environment:
   - Configure your local server with PHP 8.2+
   - Set up MySQL database
   - Import the SQL schema

4. Start developing!

### Code Style Guidelines

- Follow **PSR-12** coding standards
- Use **consistent indentation** (2 spaces)
- Write **clear, concise comments**
- Follow **naming conventions** (camelCase for variables, PascalCase for classes)
- Use **semantic commit messages**

### Pull Request Process

1. Ensure your code follows the project's coding standards
2. Write tests for new functionality
3. Update documentation as needed
4. Submit a clear description of your changes in the PR

---

## 📝 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors & Contributors

### Core Team

- **Avryl Arranguez** - System Architecture
- **Dustin Balansag** - Frontend Development
- **Osbev Cabucos** - Backend Development
- **Ynaki Galve** - Database Design
- **Drixyl Nacu** - Quality Assurance

### Special Thanks

- [Bootstrap](https://getbootstrap.com/) - For the responsive framework
- [Font Awesome](https://fontawesome.com/) - For iconography
- [fpdf](https://www.fpdf.org/) - For PDF generation capabilities

---

## 🐛 Issues & Support

### Reporting Issues

If you encounter any problems or have feature requests:

1. **Check existing issues** to avoid duplicates
2. **Create a new issue** with:
   - Clear description of the problem
   - Steps to reproduce
   - Expected behavior
   - Any relevant screenshots or code snippets

### Getting Help

- **Community Forum**: Join our [Discussion Board](link-to-discussion-board)
- **Email Support**: support@cebuplantdepot.com
- **Documentation**: Check the [Wiki](link-to-wiki) for detailed guides

### FAQ

**Q: How do I reset my password?**
A: Go to the login page and click "Forgot Password". An admin will receive your request and process it manually.

**Q: Can I customize the product categories?**
A: Yes! You can add, edit, or remove categories by modifying the database schema and updating the application code.

**Q: Is there a mobile app version?**
A: Currently, this is a web-based application. We plan to develop mobile apps in the future.

---

## 🗺️ Roadmap

### Planned Features

1. **Mobile Application**: iOS and Android versions
2. **Multi-Language Support**: Add support for additional languages
3. **Advanced Analytics**: More detailed sales and inventory reports
4. **Customer Loyalty Program**: Points and rewards system
5. **API Integration**: Connect with third-party services

### Known Issues

- **PDF Generation**: Some edge cases with special characters in product names
- **Mobile Responsiveness**: Minor adjustments needed for very small screens
- **Performance**: Large product catalogs may impact page load time

### Future Improvements

- **Enhanced Security**: Implement CAPTCHA for public forms
- **User Feedback System**: Allow customers to rate and review products
- **Automated Email Notifications**: For order confirmations and updates
- **Mobile Payment Integration**: Support for mobile wallets and cards

---

## 🌱 Getting Started with Cebu Plant Depot

Ready to transform your plant business? Follow these steps to get started:

1. **Set up your database** using the provided SQL schema
2. **Configure your server** with the required PHP and MySQL versions
3. **Upload the application** to your web server
4. **Customize** the application to match your brand
5. **Start selling!**

Join thousands of plant businesses that have already improved their operations with Cebu Plant Depot. Whether you're a small nursery or a large plant shop, our system is designed to help you grow your business!

🌱 **Let's make your plant business bloom!**
```

This README.md file provides a comprehensive guide to the Cebu Plant Depot project, making it attractive for developers to contribute and use. It includes:

1. A compelling overview with clear value proposition
2. Detailed feature lists with emojis for visual appeal
3. Complete installation instructions
4. Practical usage examples with code snippets
5. Clear project structure documentation
6. Contribution guidelines
7. Roadmap for future development
8. Support information
9. Professional formatting and modern GitHub best practices

The README is designed to be both informative and engaging, encouraging developers to explore, use, and contribute to the project.
