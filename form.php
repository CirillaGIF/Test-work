<!DOCTYPE html>
<html lang="ru">
<style>
    html,
    form {
        text-align: center;
        font-size: 20px;
    }

    input {
        font-size: 20px;
        display: block;
        margin: 0 auto;
        margin-top: 10px;
    }

    input[type="radio"] {
        display: inline;
    }

    div {
        height: 10px;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>testovoe</title>
</head>

<body>
    <div></div>
    <form action="test.php" method="post">
        <input type="text" placeholder="Имя" name="name"></input>
        <input type="text" placeholder="Телефон" name="phone"></input>
        <input type="text" placeholder="Комментарий" name="comment"></input>
        <input type="radio" name="r1" value="amo">amoCRM</input>
        <input type="radio" name="r1" value="btrx">Bitrix24</input>
        <input type="submit"></input>
    </form>
</body>


</html>