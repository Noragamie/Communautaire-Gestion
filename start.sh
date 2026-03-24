#!/bin/bash
# Script de démarrage rapide pour le projet

echo "🚀 Démarrage du projet Gestion Communautaire..."

# Vérifier que la base de données existe
if [ ! -f "database/database.sqlite" ]; then
    echo "📦 Création de la base de données SQLite..."
    touch database/database.sqlite
    php artisan migrate
fi

# Lancer le serveur
echo "✅ Serveur Laravel démarré!"
echo "🌐 Accédez à l'application sur: http://127.0.0.1:8000"
echo "   (ou http://127.0.0.1:8001 ou http://127.0.0.1:8002 si le port est occupé)"
echo ""
echo "Appuyez sur Ctrl+C pour arrêter le serveur"
php artisan serve
