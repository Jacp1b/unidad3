<?php
declare(strict_types=1);


// Parametros con tipo definido
function sumar(int $a, int $b): int {
    return $a + $b;
}
echo sumar(3, 4); // 7


// parametros con valor por defecto
function saludarA(string $nombre = "invitado"): string {
    return "Hola, $nombre";
}
echo saludarA();        // Hola, invitado
echo saludarA("Laura"); // Hola, Laura


// Argumento nombrado
function crearUsuario(string $nombre, int $edad = 18, string $pais = "Colombia") {
    return "$nombre, $edad años, $pais";
}
echo crearUsuario(nombre: "Ana", pais: "Perú");


// Funcion por valor o por referencia
function incrementar(int $n)     { $n++; } // no cambia el original
function incrementarRef(int &$n) { $n++; } // sí cambia
$num = 1;
incrementar($num);    echo $num; // 1
incrementarRef($num); echo $num; // 2