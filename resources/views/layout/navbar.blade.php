<nav class="navbar navbar-expand-lg bg-body-tertiary no-print sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{Auth::check() ? '/'. Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch() .'/dashboard' : '/'}}">
            <img src="{{isset(session()->all()['_old_input']['branch']) ? session()->all()['_old_input']['branch']->logo : '/logo.png'}}" alt="{{env('NAME')}}" height="30px">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav nav-pills nav-fill me-auto mb-2 mb-lg-0">
            @guest
                <li class="nav-item">
                    <a class="nav-link {{isset($active_tab) ? ($active_tab === 'welcome' ? 'active' : '') : ''}}" aria-current="page" href="/">Accueil</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">À propos</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/about_us/history">Histoire</a></li>
                        <li><a class="dropdown-item" href="/about_us/mission">Mission</a></li>
                        <li><a class="dropdown-item" href="/about_us/our_team">Notre équipe</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{isset($active_tab) ? ($active_tab === 'picture_gallery' ? 'active' : '') : ''}}" aria-current="page" href="/picture_gallery">Gallerie d'images</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{isset($active_tab) ? ($active_tab === 'tools' ? 'active' : '') : ''}}" aria-current="page" href="/tools">Outils</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{isset($active_tab) ? ($active_tab === 'contact_us' ? 'active' : '') : ''}}" aria-current="page" href="/contact_us">Nous joindre</a>
                </li>
                @endguest
                @auth
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i> Tableau de bord</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/transactions">Transactions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/items">Articles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/inventory">Inventaire</a>
                            </li>
                    </li>
                @endauth
            </ul>
            @auth
            <form class="d-flex" method="get" action="/logout">
                <button class="nav-link" aria-disabled="true">Déconnexion</button>
            </form>
            @endauth
            @guest
                <form class="d-flex" method="get" action="/login">
                    <button class="nav-link" aria-disabled="true">Connexion</button>
                </form>
            @endguest
        </div>
    </div>
</nav>