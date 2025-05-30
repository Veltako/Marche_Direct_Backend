# 🔙 Marché Direct — Backend API

> Backend en Symfony/API Platform pour la plateforme Marché Direct.  
> 🎓 Développé dans le cadre d’un projet pédagogique visant à créer un système de commande en ligne pour marchés locaux.

---

## 📝 Consigne du projet

Concevoir et développer une plateforme en ligne permettant aux commerçants de marché d'exposer leurs produits, et aux utilisateurs de passer des commandes en ligne qu'ils pourront récupérer le jour même directement sur le marché.

🔗 **Documentation complète et conception détaillée du projet** :  
[Notion – Marché Direct](https://www.notion.so/March-Direct-113d12f0bba8806f8f9ae94a229fb79a?pvs=4)

---

## 🎯 Objectifs du backend

- Gérer les utilisateurs, commerçants, produits, commandes, etc.
- Exposer une API sécurisée pour le frontend Angular
- Implémenter l’authentification JWT
- Fournir des outils d’analyse et de gestion pour les administrateurs

---

## ⚙️ Technologies utilisées

- **Symfony 6.4**
- **API Platform**
- **Doctrine ORM**
- **JWT Authentication**
- **PHP 8.3**
- **MySQL**
- **CORS**

---

## 🚀 Lancer l’API localement

```bash
git clone https://github.com/Veltako/Marche_Direct_Backend.git
cd Marche_Direct_Backend
composer install

# Configurer .env.local avec vos identifiants
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony serve
```

---

## 🔁 Lien vers le frontend

👉 Accéder au dépôt frontend : [Marche_Direct_Frontend](https://github.com/Veltako/Marche_Direct_Frontend)

---

## 👨‍💻 Réalisé par

- **Baptiste Dupache** — Projet réalisé en équpe dans le cadre de ma formation de développeur
