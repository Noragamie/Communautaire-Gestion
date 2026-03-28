#!/bin/bash
# Script pour configurer les variables d'environnement sur Railway
# Exécutez ce script après avoir fait 'railway init'

echo "Configuration des variables d'environnement Railway..."

# Application
railway variables set APP_NAME="Laravel"
railway variables set APP_ENV="production"
railway variables set APP_KEY="base64:wb83BnIlPn2zwFdO77A/ia7P+DQ6kFO6zcFG/jVnVWc="
railway variables set APP_DEBUG="false"
railway variables set APP_URL="https://\${{RAILWAY_PUBLIC_DOMAIN}}"

# Locale
railway variables set APP_LOCALE="en"
railway variables set APP_FALLBACK_LOCALE="en"
railway variables set APP_FAKER_LOCALE="en_US"

# Logging
railway variables set LOG_CHANNEL="stack"
railway variables set LOG_STACK="single"
railway variables set LOG_LEVEL="error"

# Database (Railway fournira automatiquement une base MySQL)
railway variables set DB_CONNECTION="mysql"

# Session
railway variables set SESSION_DRIVER="database"
railway variables set SESSION_LIFETIME="120"
railway variables set SESSION_ENCRYPT="false"

# Cache & Queue
railway variables set CACHE_STORE="database"
railway variables set QUEUE_CONNECTION="database"
railway variables set FILESYSTEM_DISK="local"

# Mail (Brevo/Sendinblue)
# Configure ces variables manuellement dans le dashboard Railway
# railway variables set MAIL_MAILER="smtp"
# railway variables set MAIL_HOST="smtp-relay.brevo.com"
# railway variables set MAIL_PORT="587"
# railway variables set MAIL_ENCRYPTION="tls"
# railway variables set MAIL_USERNAME="your_brevo_username"
# railway variables set MAIL_PASSWORD="your_brevo_password"
# railway variables set MAIL_FROM_ADDRESS="your_email@example.com"
# railway variables set MAIL_FROM_NAME="Laravel"

echo "✓ Variables d'environnement configurées avec succès!"
echo "Vous pouvez maintenant déployer avec: railway up"
