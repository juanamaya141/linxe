function totalConciliado(valor) {
    var total = 0;
    valor = parseInt(valor); // Convertir el valor a un entero (número).


    total = document.getElementById('totalC').innerHTML;
    // Aquí valido si hay un valor previo, si no hay datos, le pongo un cero "0".
    total = (total == null || total == undefined || total == "") ? 0 : total;
    total = total.replace(/\./g, '');
    console.log(total);
    /* Esta es la suma. */
    total = (parseInt(total) + parseInt(valor));
    total = humanizeNumber(total);
    document.getElementById('totalC').innerHTML = total;
    console.log(total);

}

function humanizeNumber(n) {
    n = n.toString()
    while (true) {
        var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1.$2$3')
        if (n == n2) break
        n = n2
    }
    return n
}