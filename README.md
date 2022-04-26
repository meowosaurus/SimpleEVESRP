# SimpleEVESRP
PHP based SRP system for EVE Online. Designed around dynamic lossmails, 
meaning you don't have to generate an SRP link to submit losses.

## Requirements
- PHP 8.x
- MySQL/Maria DB Database

### And the following sql tables
- https://www.fuzzwork.co.uk/dump/latest/invTypes.sql.bz2
- https://www.fuzzwork.co.uk/dump/latest/mapRegions.sql.bz2
- https://www.fuzzwork.co.uk/dump/latest/mapSolarSystems.sql.bz2

## Installation
1. Download and unpack the project on your webserver
2. Make sure "<your_domain.something>/install/index.php" has write permissions. 
3. Navigate to "<your_domain.something>/install/index.php"
4. Run the installation script. It will ask you to give details about the SQL connection, your website and your email. 
Please add your real email, as it will be sent to zKillboard and CCP when you pull data from their website. It's a 
common thing so they can reach out to you if you abuse their system.
5. Once the installation script finished, deleted it immediately.