<?php

function usuarioLogado()
{

    return isset(($_SESSION['nome']));
}

?>