# Work Utilities

A collection of simple PHP utilities designed to assist with common tasks:

1. **Bulk Image Resizing and Conversion**: Resize and convert images in bulk, ideal for preparing design content.
2. **QR Code Generation**: Generate QR codes from an Excel file containing links.

## Features
- **Image Resizing and Conversion**:
  - Resize images to specified dimensions.
  - Convert images to different formats.
  - Process multiple images at once.
  
- **QR Code Generator**:
  - Automatically generate QR codes for URLs stored in an Excel file.
  - QR codes are generated and saved as image files.

## Requirements
- PHP 7.0+ 
- Required PHP extensions: 
  - `php-gd` for image processing.
  - `phpqrcode` for generating QR codes.
  - `php-spreadsheet` for Excel file reading.

## Installation
1. Clone the repository:
```bash
git clone https://github.com/yourusername/work-utilities.git
```
2. Navigate to the project folder:
```bash
cd work-utilities
```
3. Install required PHP libraries via Composer:
```bash
composer install
```
