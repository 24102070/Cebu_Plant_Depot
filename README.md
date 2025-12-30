# 🌿 Cebu Plant Depot  
### Point of Sale & Inventory Management System

<p align="center">
  🌱 <strong>Where technology meets greenery</strong> 🌱
</p>

<p align="center">
  <img src="https://img.shields.io/badge/license-MIT-blue.svg" />
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" />
  <img src="https://img.shields.io/badge/MySQL-10.4+-blue.svg" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3.3-blue.svg" />
</p>

Cebu Plant Depot is a **web-based Point of Sale (POS) and Inventory Management System** built specifically for plant shops and nurseries.  
It streamlines sales, inventory tracking, and order management while delivering a smooth customer shopping experience.

---

## 📚 Table of Contents
- [✨ Features](#-features)
- [🛠 Tech Stack](#-tech-stack)
- [📦 Installation](#-installation)
- [🎯 Usage](#-usage)
- [📁 Project Structure](#-project-structure)
- [🔧 Configuration](#-configuration)
- [🤝 Contributing](#-contributing)
- [📝 License](#-license)
- [👥 Authors & Contributors](#-authors--contributors)
- [🐛 Issues & Support](#-issues--support)
- [🗺️ Roadmap](#-roadmap)

---

## ✨ Features

### 🌿 Customer Features
- Browse plants and gardening products
- Shopping cart management
- Secure checkout flow
- Real-time order tracking
- Order history for easy reordering
- User accounts and profile management

### 🌳 Admin & Business Features
- Full CRUD operations for products and inventory
- Order processing and status management
- Interactive sales & inventory dashboard
- PDF sales report generation
- Low-stock alerts
- User and role management

### 🔧 Technical Highlights
- Fully responsive UI
- Secure authentication with password hashing
- MySQL database integration
- PDF report generation using **FPDF**
- Image upload and management

---

## 🛠 Tech Stack

| Layer | Technologies |
|------|-------------|
| **Language** | PHP 8.2+ |
| **Frontend** | HTML5, CSS3, JavaScript, Bootstrap 5.3.3 |
| **Backend** | PHP (PDO/MySQLi), MySQL 10.4+ |
| **Libraries** | Font Awesome, jQuery (optional) |
| **Reports** | FPDF |
| **Auth** | Session-based authentication |

---

## 📦 Installation

### Prerequisites
- PHP **8.2+**
- MySQL **10.4+**
- Apache or Nginx
- Composer (optional)

### Quick Start

```bash
git clone https://github.com/24102070/Cebu_Plant_Depot.git
cd Cebu_Plant_Depot


Import Database

Import cebu_plant_depot.sql into MySQL

Configure Database

// Main/php/database.php
$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "cebu_plant_depot";


Deploy

Upload files to your web server

Set write permissions for image/upload folders

Run

cebuplantdepot.dcism.org


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

## 👥 Authors & Contributors

### Core Team

- **Avryl Arranguez** - Frontend Development
- **Dustin Balansag** - Frontend Development
- **Osbev Cabucos** - BackendD evelopment
- **Ynaki Galve** - UI/UX
- **Drixyl Nacu** - Backend Development

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
- **Email Support**: rezuesan@gmail.com
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






