# 🐟 SzczupakHub – forum internetowe

Aplikacja webowa forum wędkarskiego stworzona we frameworku Symfony.
Projekt umożliwia użytkownikom tworzenie pytań, dodawanie odpowiedzi, ocenianie treści oraz zarządzanie swoim kontem.
System posiada mechanizm autoryzacji, role użytkowników oraz kontrolę uprawnień.

## Technologie

- PHP 8.5
- Symfony
- Doctrine ORM
- MySQL
- Twig
- Bootstrap
- Symfony Security
- Docker & Docker Compose
- Composer

## Funkcjonalności

### Użytkownicy

- Rejestracja nowego konta
- Logowanie i wylogowanie
- Zmiana danych profilowych użytkownika
- Dodawanie i edycja awataru
- Zmiana hasła

### Forum

- Tworzenie nowych pytań i dodawanie odpowiedzi
- Dodawanie zdjęć do pytań
- Edycja i usuwanie własnych pytań i odpowiedzi
- Oznaczanie najlepszej odpowiedzi w drodze głosowania
- Przeglądanie listy tematów z filtrowaniem i stronicowaniem

### Bezpieczeństwo

- System ról użytkowników
- Kontrola dostępu do ścieżek (URL) przez Symfony Security
- Zabezpieczenie operacji przy pomocy Voterów (np. edycja tylko własnych postów)

### Dostępne role:

| Rola | Uprawnienia |
|---|---|
| `ROLE_USER` | Przeglądanie forum, tworzenie pytań, odpowiadanie, głosowanie, edycja własnych treści |
| `ROLE_ADMIN` | Pełny dostęp do panelu administracyjnego, zarządzanie użytkownikami, moderacja i usuwanie dowolnych treści |

---

## Instalacja i Uruchomienie

### Wymagania

Przed uruchomieniem projektu upewnij się, że masz zainstalowane:
- Docker Desktop (wraz z wtyczką docker-compose) – wymagany do uruchomienia środowiska i bazy danych.
- Git – wymagany do sklonowania repozytorium.
- Dowolne IDE (rekomendowany PhpStorm z wtyczką Symfony Support) – do wygodnego przeglądania kodu i zarządzania projektem.

### Instalacja i uruchomienie

1.	Sklonuj repozytorium:
      `git clone https://github.com/IcySilhouette/SI-projekt.git`
2.	Wejdź do pobranego folderu w konsoli:
      `cd szczupak_hub`
3.	Uruchom środowisko Docker w tle:
      `docker-compose up -d`
4.	Wejdź do powłoki kontenera PHP: (Jeśli Twój kontener ma inną nazwę, np. php-fpm, app lub www, podmień poniższe słowo php)
      `docker-compose exec php bash`
5.	Przejdź do folderu z aplikacją Symfony:
      `cd app`
6.	Przygotuj plik środowiskowy i uzupełnij zmienne konfiguracyjne:
      `cp .env.dev .env`
      `echo "DEFAULT_URI=http://localhost:8000" >> .env`
      `echo 'DATABASE_URL="mysql://symfony:symfony@mysql:3306/symfony?serverVersion=8.3&charset=utf8mb4"' >> .env`
7.	Będąc wewnątrz kontenera, zainstaluj zależności:
      `composer install`
8.	Uruchom migracje bazy danych:
      `bin/console doctrine:migrations:migrate --no-interaction`
9.	Załaduj dane testowe (fixtures):
      `bin/console doctrine:fixtures:load --no-interaction`
10.	Gotowa aplikacja jest dostępna w przeglądarce pod adresem: http://localhost:8000

### Dane do logowania

#### Administratorzy:
- *Admin0:* admin0@example.com    *Hasło:* admin1234
- *Admin1:* admin1@example.com    *Hasło:* admin1234

#### Użytkownicy
- *User1:* user1@example.com    *Hasło:* user1234
- *User2:* user2@example.com    *Hasło:* user1234
- *User3:* user3@example.com    *Hasło:* user1234
- *User4:* user4@example.com    *Hasło:* user1234
- *User5:* user5@example.com    *Hasło:* user1234 **użytkownik zablokowany**
- *User6:* user6@example.com    *Hasło:* user1234
- *User8:* user8@example.com    *Hasło:* user1234


