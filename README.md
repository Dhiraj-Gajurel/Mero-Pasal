

### 2️⃣ Configure the Database

1. Create a new MySQL database named `ecommerce`.
2. Import the provided SQL dump in XAMPP:

This will create the required tables and insert initial sample data.

### 3️⃣ Set Up Configuration

1. Rename `config.sample.php` to `config.php`.
2. Open `config.php` and update the following values:

```php
$servername = "localhost";
$username = "your_mysql_username";
$password = "your_mysql_password";
$database = "ecommerce";
```

### 4️⃣ Set Up the Web Server

* Place the entire `PHP---Ecommerce-Learning-Project/` folder in your server's root directory:

  * For XAMPP: `htdocs/`
* Ensure the `uploads/` folder has **write permissions** (for product image uploads).

---

## 🌐 Accessing the Application

1. Start your Apache and MySQL server.
2. Open your browser and visit:

```
http://localhost/PHP---Ecommerce-Learning-Project/
```

## 👤 Author

**Dhiraj Gajurel**

---

## 🗓️ Project Completion Date

**NOV 02, 2025**



