<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Expand at lg</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
            aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample05">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"
                   href="http://example.com"
                   id="dropdown05"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false"
                >Dropdown</a>
                <div class="dropdown-menu" aria-labelledby="dropdown05">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
        <form name="search" id="search" action="{{ route('places.index') }}" method="GET" class="form-inline">
            <input class="form-control"
                   type="text"
                   placeholder="@lang('placeshub.search')&hellip;"
                   name="name"
                   autocomplete="off">
            <button type="submit"><i class="fas fa-search"></i></button>
            <div id="results"></div>
        </form>
    </div>
</nav>