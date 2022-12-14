# SimpleDiscuss
A simple discussion forum system

## Configuring
1. Edit "config.php", configured database info
2. Create a database according to what you configured in step 1
3. Execute "reset.php" to get reset token in newly created file "reset_token.php"
4. Execute "reset.php" with query string "?token=(Token in 'reset_token.php')", database will be automatically initialized
