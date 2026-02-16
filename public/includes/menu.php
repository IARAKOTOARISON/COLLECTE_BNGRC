<h5 class="mb-3">Menu</h5>
<ul class="nav flex-column">
    <li class="nav-item mb-2">
        <a class="nav-link text-white active" href="/accueil">
            <img src="/assets/icons/home.png" alt="home" class="icon-menu">
            Accueil
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white" href="/others">
            <img src="/assets/icons/boxes.png" alt="home" class="icon-menu">
            Tous les objets
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white" href="/mine">
            <img src="/assets/icons/object1.png" alt="home" class="icon-menu">
            Mes Objets
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white" href="/propositions">
            <img src="/assets/icons/proposition.png" alt="propositions" class="icon-menu">
            Propositions
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white" href="/historique">
            <img src="/assets/icons/boxes.png" alt="historique" class="icon-menu">
            Historique proprio
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white" href="/accueil">
            <img src="/assets/icons/profile.png" alt="home" class="icon-menu">
            <?php echo isset($_SESSION['user_nom']) ? htmlspecialchars($_SESSION['user_nom']) : 'GUEST'; ?>
        </a>
    </li>
</ul>