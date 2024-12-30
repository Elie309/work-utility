# GCS Work Utilities

## Overview

GCS Work Utilities is a collection of tools designed to assist with various tasks such as generating QR codes, resizing images, managing product lists, and editing product descriptions. This project is built using PHP and integrates with libraries like PhpSpreadsheet and phpqrcode.

## Features

- **QR Code Generator**: Upload an Excel file to generate QR codes for each entry.
- **Image Resizer**: Upload or paste images to resize and optimize them for web use.
- **Product List Manager**: Upload and compare product lists based on SKU.
- **Description Editor**: Manage and edit product descriptions.

## Setup

### Prerequisites

- PHP 7.4 or higher
- Composer
- Web server (e.g., Apache, Nginx)

### Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/Elie309/workutility.git
    cd workutility
    ```

2. Install dependencies using Composer:
    ```sh
    composer install
    ```

3. Ensure the `uploads` and `outputs` directories are writable by the web server:
    ```sh
    chmod -R 775 uploads outputs
    ```

4. Configure your web server to serve the project directory.

## Usage

### QR Code Generator

1. Navigate to the QR Code Generator page:
    ```
    http://yourserver/qrcodegen.php
    ```

2. Upload an Excel file containing the data for QR code generation.

3. The generated QR codes will be displayed on the page.

### Image Resizer

1. Navigate to the Image Resizer page:
    ```
    http://yourserver/imageresizer.php
    ```

2. Upload images to resize them.

3. Download the resized images from the provided links.

### Product List Manager

1. Navigate to the Product List Manager page:
    ```
    http://yourserver/products.php
    ```

2. Upload product lists to compare them based on SKU.

### Description Editor

1. Navigate to the Description Editor page:
    ```
    http://yourserver/descriptions.php
    ```

2. Manage and edit product descriptions.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Acknowledgements

- [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)
- [phpqrcode](https://sourceforge.net/projects/phpqrcode/)
