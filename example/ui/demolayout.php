<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{TITLE}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .callout {
            --color: blue;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/json">JSON</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="csv">CSV</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reroute">Reroute</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Products
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/product/Product1.html">Product 1</a></li>
                            <li><a class="dropdown-item" href="/product/Product2.html">Product 2</a></li>
                            <li><a class="dropdown-item" href="/product/Product3.html">Product 3</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="card col-4 m-2">
            <div class="card-body">
                <h5 class="card-title">Defined variables</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text">
                    <?php
                    echo "LAYOUT";
                    $vars = get_defined_vars();
                    foreach ($vars as $name => $var) {
                        if ($name == "content") {
                            continue;
                        }
                        echo "<br>var <b>\$" . $name . "</b> = " . $var;
                    }
                    ?>
                  </p>
            </div>
        </div>
        <div class="card col m-2">
            <div class="card-body">
                <h5 class="card-title">Content from the demo.html</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text">
                    <!--[content:content]-->
                    <!--[content:content2]-->
                </p>
            </div>
        </div>
        <div class="card col m-2">
            <div class="card-body">
                <h5 class="card-title">Content from the load from demo.html</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text"><!--[content:fromload]--></p>
            </div>
        </div>
        <div class="card col m-2">
            <div class="card-body">
                <h5 class="card-title">Content from the loadtolayout.html</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text"><!--[load:loadtolayout.html]--></p>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous"></script>
</body>
</html>


