function altNivel(v,o){
    nome = "";
    switch(o){
        case 1: nome="Secular"; break;
        case 2: nome="Biblico"; break;
        case 3: nome="Autoral"; break;
        case 4: nome="SecSecular"; break;
    }
    $('#objPorcBar'+nome).html(v+"%");
    if(v!=0 && v!=25 && v!=35 && v!=50 && v!=70 && v!=75 && v!=100){
        if(v<25){ v = 25; }else if(v<35){ v = 35; }else if(v<50){ v = 50; }else
        if(v<70){ v = 70; }else if(v<99){ v = 75; }else { v = 100; }
    }
    switch(v){
        case 100: case 75:case 70:case 50:
            document.getElementById('pbr'+nome).style="animation: loading-100 1.8s linear forwards;";
            document.getElementById('pbl'+nome).style="animation: loading-"+v+" 1.5s linear forwards 1.8s;";
            break;
        case 35:case 25:
            document.getElementById('pbr'+nome).style="animation: loading-"+v+" 1.8s linear forwards;";
            document.getElementById('pbl'+nome).style="animation: loading-50 1.5s linear forwards 1.8s;";
            break;
        case 0:
            document.getElementById('pbr'+nome).style="animation: loading-0 1.8s linear forwards;";
            document.getElementById('pbl'+nome).style="animation: loading-50 1.5s linear forwards 1.8s;";
            break;
    }
}