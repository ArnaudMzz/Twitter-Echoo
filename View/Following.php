<?php
session_start();
require_once '../Model/User.php';
require_once '../Model/Database.php';
include 'Navbar.php';

if (!isset($_SESSION['user'])) {
    header('Location: Login.php');
    exit();
}

$username = $_GET['username'] ?? null;

if (!$username) {
    die("Erreur : Aucun utilisateur spécifié.");
}

$db = Database::getConnection();
$stmt = $db->prepare("SELECT * FROM User WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    die("Erreur : Utilisateur non trouvé.");
}

$followings = User::getFollowing($user['id_user']);
$default_avatar = '../uploads/avatars/DefaultAvatar.png';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Homepage</title>
  <link rel="stylesheet" href="./assets/Following.css" />
  <link
    href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script
    src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    type="text/javascript"></script>
</head>

<body>
<div class="contain-all">
    <div class="sidebar">
      <!-- Sidebar Start -->
      <div
        class="flex flex-col items-center w-80 h-full overflow-hidden border-r border-purple-200 rounded">
        <div class="w-full px-2">
          <div
            class="flex flex-col items-center w-full mt-3">
            <a
              class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-purple-900 hover:text-gray-300"
              href="Home.php">
              <svg
                class="w-6 h-6 stroke-current"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 576 512"
                stroke="white"
                fill="white">
                <path
                  d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
              </svg>
              <span class="ml-4 text-md font-bold">Accueil</span>
            </a>
            <a
              class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-purple-900 hover:text-gray-300"
              href="../View/Error.php">
              <svg
                class="w-6 h-6 stroke-current"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"
                stroke="white"
                fill="white">
                <path
                  d="M224 0c-17.7 0-32 14.3-32 32l0 19.2C119 66 64 130.6 64 208l0 18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416l384 0c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8l0-18.8c0-77.4-55-142-128-156.8L256 32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3l-64 0-64 0c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z" />
              </svg>
              <span class="ml-4 text-md font-bold">Notifications</span>
            </a>
            <a
              class="flex items-center w-full h-12 px-3 mt-2 hover:bg-purple-900 hover:text-gray-300 rounded"
              href="../View/Error.php">
              <svg
                class="w-6 h-6 stroke-current message-button"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512"
                stroke="white"
                fill="white">
                <path
                  d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
              </svg>
              <span class="ml-4 text-md font-bold">Messages</span>
            </a>
            <a
              class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-purple-900 hover:text-gray-300"
              href="Conversations.php?id_receiver=<?= $user['id_user'] ?>">
              <svg
                class="w-6 h-6 stroke-current"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 618 512"
                stroke="white"
                fill="white">
                <path
                  d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z" />
              </svg>
              <span class="ml-4 text-md font-bold">Amis</span>
            </a>
          </div>
        </div>
        <a
          class="flex items-center justify-center w-full h-16 mt-auto hover:bg-purple-900 hover:text-gray-300"
          href="Profile.php?username=<?= urlencode($_SESSION['user']['username']) ?>">
          <svg
            class="w-6 h-6 stroke-current"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="ml-2 text-md font-bold">Compte</span>
        </a>
      </div>
    </div>
  <div class="contain-contact">
    <div class="sidebar">
    </div>
    <div class="main-content">
      <h1>Abonnements de <?= htmlspecialchars($user['username']) ?></h1>
      <?php if (empty($followings)) : ?>
          <p>Aucun abonnement trouvé.</p>
      <?php else : ?>
          <ul class="conversation-list">
              <?php foreach ($followings as $following) : ?>
                  <li class="conversation-item">
                      <a class="box-account" href="Profile.php?username=<?= urlencode($following['username']) ?>">
                          <div class="user-avatar">
                              <img class="tweet-image" src="<?= !empty($following['avatar_url']) ? $following['avatar_url'] : $default_avatar; ?>" alt="Photo de profil">
                          </div>
                          <div class="user-info">
                              <h2><?= htmlspecialchars($following['username']) ?></h2>
                              <p class="user-bio"><?= !empty($following['bio']) ? htmlspecialchars($following['bio']) : 'Aucune bio disponible.'; ?></p>
                          </div>
                      </a>
                  </li>
              <?php endforeach; ?>
          </ul>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>