function number_format (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function format(number){
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return parts.join(".");
}

function bulan_indo(M){
    bulan = {
        1 : 'Januari',
        2 : 'Februari',
        3 : 'Maret',
        4 : 'April',
        5 : 'Mei',
        6 : 'Juni',
        7 : 'Juli',
        8 : 'Agustus',
        9 : 'September',
        10: 'Oktober',
        11: 'November',
        12: 'Desember'
    }

    return bulan[parseInt(M)]

}