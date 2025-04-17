
# Table Generator for Constats Amiables

This script generates three related tables for storing accident report data:

## Database Structure

### constats_main
- Contains general accident information
- Primary table with basic details
- Contains fields from sections 1, 4, 5, 8-11

### constats_vehicle_a 
- Contains all Vehicle A specific information
- Links to constats_main via foreign key
- Contains all fields prefixed with 's2_'

### constats_vehicle_b
- Contains all Vehicle B specific information
- Links to constats_main via foreign key
- Contains all fields prefixed with 's3_'

## Field Types
- Canvas/Signatures: LONGTEXT
- Radio/Checkboxes: VARCHAR(1)
- Text inputs: VARCHAR based on data-maxlength attribute
- Default: VARCHAR(255)

## Usage
Table generation is triggered via the "Generate Tables" button in the form interface. This will:
1. Drop existing tables if they exist
2. Create new tables with proper relationships
3. Set up all fields with appropriate types and lengths
4. Return success/error message to user

## Dependencies
- Requires Configurations_bdd.php for database connection
- Scans all Section_*.php files for field definitions
- Uses data-db-name attributes to identify database fields