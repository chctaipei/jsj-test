function xxx(aaa)
{
    console.log(aaa);
}

function ddd(a, callback) {
    callback(a);
}


ddd('123', xxx);
