#!/usr/bin/env sh

# Runme with: docker-compose  run --rm wp-cli install.sh

# Install WordPress.
wp core install \
  --title="Display Metadata sandbox" \
  --admin_user="wordpress" \
  --admin_password="wordpress" \
  --admin_email="holamundo@manuelcanga.dev" \
  --url="http://localhost" \
  --skip-email

# Update permalink structure.
wp option update permalink_structure "/%year%/%monthnum%/%postname%/" --skip-themes --skip-plugins

# Activate plugin.
wp plugin activate display-metadata