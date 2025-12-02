# Guide de Test - Application SPA Laravel + Vue.js

## Prérequis

Avant de tester, assurez-vous d'avoir installé toutes les dépendances :

```bash
# Installer les dépendances npm si ce n'est pas déjà fait
npm install

# Si le plugin Vue pour Vite n'est pas installé
npm install --save-dev @vitejs/plugin-vue
```

## Étapes pour tester dans le navigateur

### Étape 1 : Démarrer le serveur de développement Vite

Ouvrez un **premier terminal** et exécutez :

```bash
npm run dev
```

Cette commande va :
- Compiler vos fichiers Vue.js, CSS et JavaScript
- Démarrer le serveur de développement Vite (généralement sur le port 5173)
- Surveiller les changements et recompiler automatiquement

**Attendez** que vous voyiez un message comme :
```
  VITE v7.x.x  ready in xxx ms

  ➜  Local:   http://localhost:5173/
  ➜  Network: use --host to expose
```

### Étape 2 : Démarrer le serveur Laravel

Ouvrez un **deuxième terminal** (gardez le premier ouvert) et exécutez :

```bash
php artisan serve
```

Cette commande va démarrer le serveur Laravel (généralement sur `http://localhost:8000` ou `http://127.0.0.1:8000`)

**Attendez** que vous voyiez un message comme :
```
Laravel development server started: http://127.0.0.1:8000
```

### Étape 3 : Ouvrir l'application dans le navigateur

1. Ouvrez votre navigateur web (Chrome, Firefox, Edge, etc.)
2. Allez à l'adresse : **http://127.0.0.1:8000** ou **http://localhost:8000**

## Ce que vous devriez voir

### Page d'accueil (non connecté)
- Un message "Welcome"
- Un message "You are not logged in"
- Des liens de navigation : "Home" et "Login"

### Test de navigation
1. Cliquez sur le lien **"Login"** - vous devriez voir le formulaire de connexion
2. Cliquez sur **"Home"** - vous devriez revenir à la page d'accueil
3. Testez la navigation directe en tapant dans la barre d'adresse :
   - `http://127.0.0.1:8000/` → Page d'accueil
   - `http://127.0.0.1:8000/login` → Page de connexion
   - `http://127.0.0.1:8000/dashboard` → Page dashboard (redirigera si non connecté)

### Test de connexion (si vous avez des utilisateurs)
1. Allez sur `/login`
2. Entrez vos identifiants
3. Après connexion, vous devriez être redirigé vers `/dashboard`
4. Vous devriez voir vos informations utilisateur

## Vérifications à faire

### ✅ Vérifier que Vue.js fonctionne
1. Ouvrez les **Outils de développement** du navigateur (F12)
2. Allez dans l'onglet **Console**
3. Vous ne devriez **pas** voir d'erreurs JavaScript
4. Si vous voyez des erreurs, notez-les et vérifiez la configuration

### ✅ Vérifier que le routage fonctionne
1. Naviguez entre les pages
2. La page ne devrait **pas** se recharger complètement (pas de flash blanc)
3. L'URL devrait changer dans la barre d'adresse
4. Le contenu devrait changer sans rechargement

### ✅ Vérifier l'authentification
1. Dans la console du navigateur, tapez : `window.__USER_AUTH__`
2. Vous devriez voir un objet avec `isLoggedin` et `user`
3. Si connecté : `isLoggedin: true` et `user` contient vos données
4. Si non connecté : `isLoggedin: false` et `user: null`

## Problèmes courants et solutions

### ❌ Erreur : "Cannot find module '@vitejs/plugin-vue'"
**Solution :**
```bash
npm install --save-dev @vitejs/plugin-vue
```

### ❌ Erreur : "Vite manifest not found"
**Solution :**
- Assurez-vous que `npm run dev` est en cours d'exécution
- Vérifiez que le fichier `resources/views/monopage.blade.php` utilise `@vite()` et non `mix()`

### ❌ La page reste blanche ou affiche "Loading..."
**Solution :**
1. Vérifiez la console du navigateur pour les erreurs
2. Vérifiez que `npm run dev` fonctionne correctement
3. Vérifiez que `App.vue` contient bien `<router-view>`

### ❌ Les routes ne fonctionnent pas (404)
**Solution :**
1. Vérifiez que la route `{any}` dans `routes/web.php` est bien en dernier
2. Vérifiez que `resources/js/routes/index.js` contient toutes les routes

### ❌ Erreur CORS ou problèmes de requêtes
**Solution :**
1. Vérifiez que `resources/js/bootstrap.js` configure bien Axios
2. Vérifiez que le token CSRF est présent dans le `<head>` de `monopage.blade.php`

## Commandes utiles

### Arrêter les serveurs
- Dans chaque terminal, appuyez sur **Ctrl + C**

### Recompiler les assets
```bash
npm run build
```

### Voir les erreurs de compilation
- Regardez la sortie de `npm run dev` dans le terminal
- Regardez la console du navigateur (F12)

## Structure des fichiers importants

```
resources/
├── views/
│   └── monopage.blade.php    ← Point d'entrée SPA
├── js/
│   ├── app.js                 ← Point d'entrée Vue.js
│   ├── App.vue                ← Composant racine
│   ├── bootstrap.js           ← Configuration Axios
│   ├── routes/
│   │   └── index.js           ← Routes Vue.js
│   └── components/
│       ├── Home.vue
│       ├── Login.vue
│       └── Dashboard.vue
└── sass/
    └── app.scss               ← Styles globaux
```

## Notes importantes

- ⚠️ **Gardez les deux terminaux ouverts** pendant le développement
- ⚠️ **Ne fermez pas `npm run dev`** - il surveille les changements
- ⚠️ Si vous modifiez des fichiers Vue.js, la page devrait se recharger automatiquement
- ⚠️ Si vous modifiez des fichiers PHP, vous devrez peut-être rafraîchir manuellement

## Test de production (optionnel)

Pour tester comme en production :

```bash
# Compiler les assets pour la production
npm run build

# Démarrer le serveur Laravel
php artisan serve
```

En production, Vite n'est pas nécessaire car les fichiers sont précompilés.

