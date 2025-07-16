<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script defer type="text/javascript" src="customScript.js"></script>
    <?php 
        include_once('Functions.php');
    ?>
    <title>...</title>
</head>
<body>

    <nav class="navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="#">...</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Start</a>
                </li>
            </ul>
        </div>
    </nav>

    <?php 
        if(isset($_COOKIE['errorEdit'])){
            CheckErrors("errorEdit=true", json_decode($_COOKIE['errorEdit']));
        }
    ?>

    <div class="container">
        <input type="text" class="form-control" Id="searchBook" oninput="getBooks()"/>
        <input type="button" class="btn btn-success" value="+" onclick="showDialog()"/>
    </div>

    <div class="container">
        <table class="table table-borderless table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody id="tableValues">
                <tr>
                    <td></td>
                    <td>
                        <form method="post" action="Frontend/Edit.php">
                            <input type="hidden" name="Id" value="" />
                            <input type="submit" class="btn btn-warning" value="✏️"/>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="Backend/delete.php">
                            <input type="hidden" name="Id" value="" />
                            <input type="submit" class="btn btn-danger" value="🗑️"/>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>