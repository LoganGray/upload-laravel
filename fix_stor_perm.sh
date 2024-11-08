# Navigate to Laravel project root

# Set directory ownership to web server user (www-data)
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache

# Set directory permissions
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# If you need to ensure all future files have correct permissions
sudo chmod g+s storage
sudo chmod g+s bootstrap/cache

# Make sure your user is in www-data group (if not already done)
sudo usermod -a -G www-data $USER

# If you want to be more specific, you can set permissions for key storage subdirectories:
sudo chmod -R 775 storage/app/public
sudo chmod -R 775 storage/framework
sudo chmod -R 775 storage/logs
