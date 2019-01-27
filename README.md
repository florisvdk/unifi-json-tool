# configtool

This tool imports unifi site information using the Art-of-WiFi/UniFi-API-client and makes it easier to do some advanced configuration.

# Possible in the latest version:

- Import information form unifi controller
- Add a unifi site to other unifi site vpn with bgp for routing with generated passwords
- IGMP proxy configuration
- Display the generated json only, you can copy and paste this into the correct file on the controller. Api to automate this is planned.

More is in development.

# Installation:

1. install an apache or nginx webserver with php 5.6 or higher.
2. install a mysql or mariadb server and the php module for it.
3. copy or move the settings template in includes/php/ to settings.php in the same folder and edit the configuration.
4. Import the sql file in the root directory.
5. Done.
